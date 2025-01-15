pipeline {
    agent { label 'hrms-dev' }  // Use your specific Jenkins agent 'hrms-dev'
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
                            # Set global git configurations
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
                            # Ensure the tag doesn't already exist
                            if git rev-parse "refs/tags/build_${BUILD_NUMBER}" >/dev/null 2>&1; then
                                echo "Tag build_${BUILD_NUMBER} already exists, skipping tag creation."
                            else
                                # Create and push the tag based on the current build number
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
                script {
                    // Create a directory for the build version
                    sh "mkdir -p /var/www/html/build_version/build_${BUILD_NUMBER}"

                    // Unzip the build artifact into the respective folder
                    sh """
                        unzip /path/to/your/artifact/your_artifact.zip -d /var/www/html/build_version/build_${BUILD_NUMBER}/
                    """
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                echo "Cleaning up old builds..."
                script {
                    // Delete directories older than 15 days
                    sh "find /var/www/html/build_version/ -type d -mtime +15 -exec rm -rf {} \\;"
                }
            }
        }

        stage('Update Webpage') {
            steps {
                echo "Updating webpage..."
                // Add your webpage update steps here
            }
        }

        stage('Send Email Notification') {
            steps {
                echo "Sending email notification..."
                // Add email notification logic here
            }
        }
    }

    post {
        always {
            echo 'Cleaning up after the pipeline...'
            // Any cleanup actions go here
        }

        success {
            echo 'Pipeline succeeded!'
            // Success-related actions go here
        }

        failure {
            echo 'Pipeline failed!'
            // Failure-related actions go here
            mail to: 'aishwaryagaikwad7376@gmail.com',
                 subject: "Jenkins Build Failure: ${env.JOB_NAME}",
                 body: "The build ${env.BUILD_NUMBER} failed. Please check the logs."
        }
    }
}
