pipeline {
    agent { label 'hrms-dev' }

    environment {
        GITHUB_CREDENTIALS = credentials('github-token')
        ARTIFACT_DIR = '/var/www/html/builds'  // Directory to store build artifacts
    }

    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }

        stage('Tag Source Code') {
            steps {
                script {
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh '''
                        git config --global --add safe.directory '*'
                        git config user.name "tech-spacece"
                        git config user.email "technology@spacece.in"
                        git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"
                        git push https://tech-spacece:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                        '''
                    }
                }
            }
        }

        stage('Build') {
            steps {
                sh 'make build'  // Your build command here
            }
        }

        stage('Deploy HRMS') {
            parallel {
                stage('Deploy Using SSH Agent') {
                    steps {
                        sshagent(['hrms-dev']) {
                            sh '''
                            mkdir -p ${ARTIFACT_DIR}/build_${BUILD_NUMBER}
                            cp /home/devopsadmin/workspace/hrms-cicd/*.php ${ARTIFACT_DIR}/build_${BUILD_NUMBER}/
                            '''
                        }
                    }
                }

                stage('Deploy Using Publish Over SSH') {
                    steps {
                        sshPublisher(publishers: [
                            sshPublisherDesc(
                                configName: 'hrms-server', 
                                transfers: [
                                    sshTransfer(
                                        cleanRemote: false,
                                        remoteDirectory: '/var/www/html/Spacece-HRMS',
                                        sourceFiles: '**/*.php'
                                    )
                                ],
                                verbose: false
                            )
                        ])
                    }
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                script {
                    def buildDir = '/var/www/html/builds/'
                    def allBuilds = sh(script: "ls -d ${buildDir}build_*", returnStdout: true).split("\n")
                    allBuilds.sort().reverse().drop(5).each { buildToDelete ->
                        sh "rm -rf ${buildToDelete}"
                    }
                }
            }
        }

        stage('Update Build Page') {
            steps {
                script {
                    def buildPagePath = '/var/www/html/builds/index.html'
                    def latestBuilds = sh(script: "ls -d ${buildDir}build_* | sort -r | head -n 5", returnStdout: true).split("\n")
                    def buildLinks = latestBuilds.collect { build ->
                        def version = build.split('_')[1]
                        return "<li><a href='/dev/${version}'>HRMS ${version}</a></li>"
                    }.join("\n")

                    def htmlContent = """
                    <!DOCTYPE html>
                    <html>
                        <head><title>HRMS Development Builds</title></head>
                        <body>
                            <h1>HRMS Module: Development Builds</h1>
                            <p>You can access even previous 5 development builds:</p>
                            <ul>
                                ${buildLinks}
                            </ul>
                        </body>
                    </html>
                    """

                    writeFile(file: buildPagePath, text: htmlContent)
                }
            }
        }
    }

    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            echo 'Pipeline executed successfully!'
        }
        failure {
            echo 'Pipeline failed. Check logs for details.'
        }
    }
}
