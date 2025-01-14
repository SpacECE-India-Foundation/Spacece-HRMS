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
                script {
                    // Using the SSH agent to deploy
                    sshagent(['hrms-dev']) {
                        sh '''
                        # Deployment command using rsync (or use any other deployment method)
                        rsync -avz --exclude '.git' --exclude 'node_modules' --exclude '.env' ./ user@remote-server:/var/www/html/Spacece-HRMS
                        '''
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
