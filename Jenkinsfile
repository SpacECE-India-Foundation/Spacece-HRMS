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

        // Other stages
        stage('Deploy HRMS') {
            steps {
                // Deployment logic here
            }
        }

        stage('Archive Artifacts') {
            steps {
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
