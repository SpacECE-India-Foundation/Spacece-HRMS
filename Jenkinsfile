pipeline {
    agent any

    environment {
        BUILD_NUMBER = "67"  // Set build number dynamically or statically
        TAR_FILE = "hrms_build_${BUILD_NUMBER}.tar.gz"
        BUILD_DIR = "/var/www/html/Spacece-HRMS/build_version/build_${BUILD_NUMBER}"
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
                    withCredentials([usernamePassword(credentialsId: 'github-token', passwordVariable: 'GITHUB_TOKEN', usernameVariable: 'GITHUB_USERNAME')]) {
                        sh """
                            git config --global --add safe.directory *
                            git config user.name 'tech-spacece'
                            git config user.email 'technology@spacece.in'
                            git fetch --tags --force --progress
                        """
                    }
                }
            }
        }

        stage('Tag Source Code') {
            steps {
                script {
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh """
                            git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"
                            git push https://${GITHUB_USERNAME}:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                        """
                    }
                }
            }
        }

        stage('Deploy Build Artifacts') {
            steps {
                sshagent(['hrms-dev']) {
                    script {
                        // Check if the tar file exists
                        if (fileExists("${TAR_FILE}")) {
                            // Create directory for the build version if not exists
                            sh """
                                mkdir -p ${BUILD_DIR}
                                # Extract the tar.gz file into the build directory
                                tar -xzf ${TAR_FILE} -C ${BUILD_DIR}
                            """
                        } else {
                            error "Tar file ${TAR_FILE} not found!"
                        }
                    }
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                sshagent(['hrms-dev']) {
                    script {
                        sh """
                            # List all build folders and remove old ones
                            ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | head -n -5 | xargs -I {} rm -rf /var/www/html/Spacece-HRMS/build_version/{}
                        """
                    }
                }
            }
        }

        stage('Update Webpage') {
            steps {
                sshagent(['hrms-dev']) {
                    script {
                        // Update the webpage with the new build information
                        sh """
                            echo "<html><body><h1>HRMS Development Builds</h1><ul>" > /var/www/html/Spacece-HRMS/index.html
                            ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | tail -n 5 | while read build; do
                                echo "<li><a href='/build_version/${build}'>${build}</a></li>" >> /var/www/html/Spacece-HRMS/index.html
                            done
                            echo "</ul></body></html>" >> /var/www/html/Spacece-HRMS/index.html
                        """
                    }
                }
            }
        }

        stage('Send Email Notification') {
            steps {
                emailext(
                    subject: "Build ${BUILD_NUMBER} Notification",
                    body: "The build ${BUILD_NUMBER} has been successfully deployed.",
                    to: "aishwaryagaikwad7376@gmail.com"
                )
            }
        }
    }

    post {
        always {
            echo "Pipeline completed"
        }
        success {
            echo "Pipeline executed successfully!"
        }
        failure {
            echo "Pipeline failed! Check logs for details."
        }
    }
}
