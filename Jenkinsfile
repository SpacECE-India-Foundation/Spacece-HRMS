pipeline { 
    agent any
    environment {
        GITHUB_TOKEN = credentials('github-token')
        BUILD_NUMBER = "${env.BUILD_NUMBER}"
    }
    stages {
        stage('Checkout SCM') {
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
                echo "Deploying PHP application files..."
                script {
                    def backendPath = '/home/devopsadmin/workspace/hrms-cicd/application/'
                    def frontendPath = '/home/devopsadmin/workspace/hrms-cicd/assets/'
                    def targetDir = "/var/www/html/Spacece-HRMS/build_version/build_${BUILD_NUMBER}"

                    // Ensure the target directory exists
                    sh "mkdir -p ${targetDir}"

                    // Copy PHP application files (backend)
                    sh "cp -r ${backendPath}/* ${targetDir}/"

                    // Copy frontend assets (CSS, JS, images, etc.)
                    sh "cp -r ${frontendPath}/* ${targetDir}/"
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                echo "Cleaning up old builds..."
                script {
                    // Delete directories older than 15 days
                    sh "find /var/www/html/Spacece-HRMS/build_version/ -type d -mtime +15 -exec rm -rf {} \\;"
                }
            }
        }

        stage('Update Webpage') {
            steps {
                echo "Updating webpage..."
                // Webpage update logic can be added here
            }
        }

        stage('Send Email Notification') {
            steps {
                echo "Sending email notification..."
                // Email logic can be added here
            }
        }
    }

    post {
        always {
            echo 'Cleaning up after the pipeline...'
        }

        success {
            echo 'Pipeline succeeded!'
            mail to: 'aishwaryagaikwad7376@gmail.com',
                 subject: "Jenkins Build Success: ${env.JOB_NAME}",
                 body: "The build ${env.BUILD_NUMBER} was successful."
        }

        failure {
            echo 'Pipeline failed!'
            mail to: 'aishwaryagaikwad7376@gmail.com',
                 subject: "Jenkins Build Failure: ${env.JOB_NAME}",
                 body: "The build ${env.BUILD_NUMBER} failed."
        }
    }
}
