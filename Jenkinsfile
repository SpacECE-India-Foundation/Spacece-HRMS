pipeline {
    agent { label 'hrms-node' }

    environment {
        REMOTE_DIR = "/var/www/html/Spacece-HRMS/build_version"
        BUILD_VERSION = "build_${BUILD_NUMBER}"
    }

    stages {
        stage('Tag Source Code') {
            steps {
                script {
                    // Configure Git user details
                    sh "git config user.name 'tech-spacece'"
                    sh "git config user.email 'technology@spacece.in'"

                    // Use the stored token for authentication
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh """
                            git tag -a ${BUILD_VERSION} -m 'Build version ${BUILD_VERSION}'
                            git push https://tech-spacece:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git ${BUILD_VERSION}
                        """
                    }
                }
            }
        }

        stage('Deploy HRMS') {
            steps {
                script {
                    // Create the build version directory on the HRMS server
                    sh """
                        mkdir -p ${REMOTE_DIR}/${BUILD_VERSION}
                    """
                    // Copy the build files to the versioned directory on the HRMS server
                    sh """
                        cp -R ${WORKSPACE}/**/*.php ${REMOTE_DIR}/${BUILD_VERSION}/
                    """
                    // Clean up old builds, keeping only the latest 10
                    sh """
                        cd ${REMOTE_DIR}
                        TOTAL_BUILDS=\$(ls -dt build_* | wc -l)
                        if [ "\$TOTAL_BUILDS" -gt 10 ]; then
                            mkdir -p backups
                            mv build_* backups/
                            echo "Old builds backed up and retained the latest 10 builds."
                        fi
                    """
                }
            }
        }

        stage('Archive Artifacts') {
            steps {
                // Archive the build artifacts (PHP files in this case)
                archiveArtifacts artifacts: '**/*.php', allowEmptyArchive: true
            }
        }
    }

    post {
        success {
            echo "Deployment Successful! Build version is available at: http://43.204.210.9/build_version/"
        }
        failure {
            echo 'Deployment Failed!'
        }
    }
}
