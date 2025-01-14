pipeline {
    agent { label 'hrms-dev' }

    environment {
        GITHUB_CREDENTIALS = credentials('github-token') 
    }

    stages {
        stage('Checkout Code') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/main']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/SpacECE-India-Foundation/Spacece-HRMS.git',
                        credentialsId: 'github-token'
                    ]]
                ])
            }
        }

        stage('Tag Source Code') {
            steps {
                script {
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        echo "GitHub Username: tech-spacece"
                        echo "GitHub Token: ${GITHUB_TOKEN}"

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

        stage('Deploy HRMS') {
            parallel {
                stage('Deploy Using SSH Agent') {
                    steps {
                        sshagent(['hrms-dev']) {
                            sh '''
                            rsync -avz /var/lib/jenkins/workspace/hrms-cicd/*.php user@43.204.210.9:/var/www/html/Spacece-HRMS/
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
