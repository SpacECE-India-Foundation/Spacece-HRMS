pipeline {
    agent any

    environment {
        REMOTE_DIR = "/var/www/html/Spacece-HRMS/build_version"  // Directory to store versions
        BUILD_VERSION = "build_${BUILD_NUMBER}"  // Unique folder name for each build
        SSH_CREDENTIALS = 'hrms-server'  // Jenkins credentials ID for SSH
        REMOTE_USER = 'devopsadmin'  // SSH user
        REMOTE_SERVER = '43.204.210.9'  // Remote server IP
    }

    stages {
        stage('Deploy HRMS') {
            steps {
                script {
                    // Create the build version directory on the remote server
                    sh """
                        ssh -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_SERVER} 'mkdir -p ${REMOTE_DIR}/${BUILD_VERSION}'
                    """
                    
                    // Copy the build files (e.g., PHP files) to the versioned directory
                    sshPublisher(
                        publishers: [
                            sshPublisherDesc(
                                configName: SSH_CREDENTIALS,
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
                                        remoteDirectory: "${REMOTE_DIR}/${BUILD_VERSION}",
                                        remoteDirectorySDF: false,
                                        removePrefix: '',
                                        sourceFiles: '**/*.php'
                                    )
                                ],
                                usePromotionTimestamp: false,
                                useWorkspaceInPromotion: false,
                                verbose: false
                            )
                        ]
                    )

                    // Clean up old builds, keeping only the latest 10
                    sh """
                        ssh -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_SERVER} <<EOF
                            cd ${REMOTE_DIR}
                            TOTAL_BUILDS=\$(ls -dt build_* | wc -l)
                            if [ "\$TOTAL_BUILDS" -gt 10 ]; then
                                ls -dt build_* | tail -n +11 | xargs rm -rf
                                echo "Old builds removed. Retained last 10 builds."
                            fi
                        EOF
                    """
                }
            }
        }
    }
}
