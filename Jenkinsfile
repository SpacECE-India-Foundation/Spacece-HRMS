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

        stage('Package and Deploy Build Artifacts') {
            steps {
                echo "Packaging and deploying build artifacts..."
                script {
                    def artifactPath = '/home/devopsadmin/workspace/hrms-cicd/target/your_artifact.zip'  // Update this with the correct artifact path
                    def targetDir = "/var/www/html/Spacece-HRMS/build_version/build_${BUILD_NUMBER}"
                    
                    // Ensure the artifact exists
                    sh """
                        if [ ! -f ${artifactPath} ]; then
                            echo "Artifact not found at ${artifactPath}"
                            exit 1
                        fi
                    """
                    
                    // Create a directory for the build version
                    sh "mkdir -p ${targetDir}"
                    
                    // Unzip the build artifact into the respective folder
                    sh """
                        unzip ${artifactPath} -d ${targetDir}/
                    """
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
                script {
                    // Fetch the latest 5 build versions
                    def buildFiles = sh(script: "ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | tail -n 5", returnStdout: true).trim().split("\n")
                    
                    // Start generating the HTML content
                    def htmlContent = "<html><body><h1>HRMS Development Builds</h1><ul>\n"
                    
                    // Add each unzipped build folder as a link in the HTML page
                    buildFiles.each { file ->
                        htmlContent += "<li><a href='/build_version/${file}/'>${file}</a></li>\n"
                    }
                    
                    // Close the HTML tags
                    htmlContent += "</ul></body></html>"
                    
                    // Write the HTML content to the webpage
                    writeFile file: '/var/www/html/Spacece-HRMS/index.html', text: htmlContent
                }
            }
        }

        stage('Send Email Notification') {
            steps {
                echo "Sending email notification..."
                mail to: 'aishwaryagaikwad7376@gmail.com',
                     subject: "Jenkins Build Status: ${env.JOB_NAME} Build #${env.BUILD_NUMBER}",
                     body: "The build ${env.BUILD_NUMBER} has finished. Please check the logs or visit the webpage for the build details."
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
