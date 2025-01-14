pipeline {
    agent any

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
                    // Set Git user configuration
                    sh '''
                    git config --global --add safe.directory '*'
                    git config user.name "tech-spacece"
                    git config user.email "technology@spacece.in"
                    git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"
                    git push https://${GITHUB_CREDENTIALS_USR}:${GITHUB_CREDENTIALS_PSW}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                    '''
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
