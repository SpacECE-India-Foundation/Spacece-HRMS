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
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        // Debugging - printing the GitHub username and token
                        echo "GitHub Username: tech-spacece"
                        echo "GitHub Token: ${GITHUB_TOKEN}"

                        // Git operations
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
                // First parallel stage: deploy using SSH Agent
                deployUsingSSHAgent: {
                    stage('Deploy Using SSH Agent') {
                        steps {
                            sshagent(['hrms-dev']) {
                                sh '''
                                # Deployment command using Jenkins agent (hrms-dev)
                                rsync -avz /var/lib/jenkins/workspace/hrms-cicd/*.php user@remote-server:/var/www/html/Spacece-HRMS/
                                '''
                            }
                        }
                    }
                }

                // Second parallel stage: deploy using Publish Over SSH
                deployUsingPublishOverSSH: {
                    stage('Deploy Using Publish Over SSH') {
                        steps {
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
