pipeline {
    agent any

    environment {
        GITHUB_CREDENTIALS = credentials('github-token') // This will pull the secret text token
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
                    // Debugging - print the username and token from Jenkins credentials
                    echo "GitHub Username: tech-spacece"
                    echo "GitHub Token: ${GITHUB_CREDENTIALS_PSW}"

                    // Using withCredentials block to inject token properly
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh '''
                        git config --global --add safe.directory '*'
                        git config user.name "tech-spacece"
                        git config user.email "technology@spacece.in"
                        git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"

                        # Using the GitHub token for authentication
                        git push https://tech-spacece:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                        '''
                    }
                }
            }
        }

        stage('Deploy HRMS') {
            steps {
                sshPublisher(publishers: [
                    sshPublisherDesc(
                        configName: 'hrms-server',
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
