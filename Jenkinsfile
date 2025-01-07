pipeline {
    agent { label 'hrms-node' }  // Using HRMS server as Jenkins agent

    environment {
        REMOTE_DIR = "/var/www/html/Spacece-HRMS/build_version"  // Directory to store versions
        BUILD_VERSION = "build_${BUILD_NUMBER}"  // Unique folder name for each build
    }

    stages {
        stage('Tag Source Code') {
            steps {
                script {
                    // Set Git username and email for the pipeline
                    sh """
                        git config user.name "dhanushharsh"
                        git config user.email "mr.harshsaini108@gmail.com"
                    """
                    
                    // Tag the repository with the build version
                    sh "git tag -a ${BUILD_VERSION} -m 'Build version ${BUILD_VERSION}'"
                    sh "git push origin ${BUILD_VERSION}"
                }
            }
        }

        stage('Deploy HRMS') {
            steps {
                script {
                    // Create the build version directory on the HRMS server (Jenkins node)
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
                            # Backup old builds before deletion
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
