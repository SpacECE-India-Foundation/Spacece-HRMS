pipeline {
    agent any
    environment {
        GITHUB_TOKEN = credentials('github-token') // Use the ID you set in Jenkins credentials
    }
    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }
        stage('Tag Source Code') {
            steps {
                withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                    sh '''
                        git config user.name "tech-spacece"
                        git config user.email "technology@spacece.in"
                        git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"
                        git push https://tech-spacece:$GITHUB_TOKEN@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                    '''
                }
            }
        }
        stage('Deploy HRMS') {
            steps {
                echo "Deploying HRMS..."
                // Add your deployment steps here
            }
        }
    }
    post {
        always {
            echo 'Pipeline completed.'
        }
    }
}
