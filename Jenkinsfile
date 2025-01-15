pipeline {
    agent any
    environment {
        GITHUB_TOKEN = credentials('github-token')
        BUILD_NUMBER = "${env.BUILD_NUMBER}"
    }
    stages {
        stage('Declarative: Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Checkout Code') {
            steps {
                script {
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh '''
                            git config --global user.name "tech-spacece"
                            git config --global user.email "technology@spacece.in"
                            git fetch --tags --force --progress
                        '''
                    }
                }
            }
        }

        stage('Tag Source Code') {
            steps {
                script {
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh '''
                            if git rev-parse "refs/tags/build_${BUILD_NUMBER}" >/dev/null 2>&1; then
                                echo "Tag build_${BUILD_NUMBER} already exists, skipping tag creation."
                            else
                                git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"
                                git push https://tech-spacece:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                            fi
                        '''
                    }
                }
            }
        }

        stage('Deploy Build Artifacts') {
            steps {
                echo "Deploying build artifacts..."
                // Deploy to web-accessible location here for QA to trigger
                // Example: Deploying to /build_version/build_${BUILD_NUMBER} folder on the web server
            }
        }

        stage('Run Build') {
            steps {
                echo "Running build ${BUILD_NUMBER}..."
                // Add the commands to trigger running the build based on the tag created
                // This could involve starting a job that uses the artifact tagged with build_${BUILD_NUMBER}
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                echo "Cleaning up old builds..."
            }
        }

        stage('Update Webpage') {
            steps {
                echo "Updating webpage..."
                // Add any webpage update steps, e.g., linking to the newly created build
            }
        }

        stage('Send Email Notification') {
            steps {
                echo "Sending email notification..."
            }
        }
    }

    post {
        always {
            echo 'Cleaning up after the pipeline...'
        }

        success {
            echo 'Pipeline succeeded!'
        }

        failure {
            echo 'Pipeline failed!'
            mail to: 'aishwaryagaikwad7376@gmail.com',
                 subject: "Jenkins Build Failure: ${env.JOB_NAME}",
                 body: "The build ${env.BUILD_NUMBER} failed. Please check the logs."
        }
    }
}
