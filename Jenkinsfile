pipeline {
    agent { label 'hrms-dev' }  // Use your hrms-dev agent here

    environment {
        GITHUB_CREDENTIALS = credentials('github-token') // Using the Jenkins credential ID
    }

    stages {
        stage('Checkout Code') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/main']],
                    doGenerateSubmoduleConfigurations: false,
                    extensions: [],
                    submoduleCfg: [],
                    userRemoteConfigs: [[
                        url: 'https://github.com/SpacECE-India-Foundation/Spacece-HRMS.git',
                        credentialsId: 'github-token' // Using the Jenkins credential ID
                    ]]
                ])
            }
        }

        stage('Tag Source Code') {
            steps {
                script {
                    // Use the withCredentials block to inject the GitHub token
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        // Debugging - printing the GitHub username and token
                        echo "GitHub Username: tech-spacece"
                        echo "GitHub Token: ${GITHUB_TOKEN}"

                        // Now use the token for git operations
                        sh '''
                        git config --global --add safe.directory '*'
                        git config user.name "tech-spacece"
                        git config user.email "technology@spacece.in"
                        git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"

                        # Use GitHub token for push
                        git push https://tech-spacece:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                        '''
                    }
                }
            }
        }

        stage('Deploy HRMS') {
            steps {
                parallel {
                    // Using SSH agent to deploy
                    deployUsingSSHAgent: {
                        sshagent(['hrms-dev']) {
                            sh '''
                            # Deployment command using Jenkins agent (hrms-dev)
                            rsync -avz /var/lib/jenkins/workspace/hrms-cicd/*.php user@remote-server:/var/www/html/Spacece-HRMS/
                            '''
                        }
                    }

                    // Using Publish Over SSH for deployment
                    deployUsingPublishOverSSH: {
                        sshPublisher(publishers: [
                            sshPublisherDesc(
                                configName: 'hrms-server', // Your SSH config name
                                transfers: [
                                    sshTransfer(
                                        cleanRemote: false,
                                        excludes: '',
                                        execCommand: '',
                                        execTimeout: 120000,
                                        flatten: false,
                                        makeEmptyDirs: false,
                                        noDefaultExcludes: false,
                                        patternSeparator: '[, ]+',
                                        remoteDirectory: '/var/www/html/Spacece-HRMS',
                                        remoteDirectorySDF: false,
                                        removePrefix: '',
                                        sourceFiles: '**/*.php'
                                    )
                                ],
                                usePromotionTimestamp: false,
                                useWorkspaceInPromotion: false,
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
