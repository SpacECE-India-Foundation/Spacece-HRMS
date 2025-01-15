pipeline {
    agent { label 'hrms-dev' }

    environment {
        GITHUB_CREDENTIALS = credentials('github-token') 
    }

    stages {
        stage('Checkout Code') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/main']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/SpacECE-India-Foundation/Spacece-HRMS.git',
                        credentialsId: 'github-token'
                    ]]
                ])
            }
        }

        stage('Tag Source Code') {
            steps {
                script {
                    withCredentials([string(credentialsId: 'github-token', variable: 'GITHUB_TOKEN')]) {
                        sh '''
                        git config --global --add safe.directory '*'
                        git config user.name "tech-spacece"
                        git config user.email "technology@spacece.in"
                        git tag -a build_${BUILD_NUMBER} -m "Build version build_${BUILD_NUMBER}"
                        git push https://tech-spacece:${GITHUB_TOKEN}@github.com/SpacECE-India-Foundation/Spacece-HRMS.git build_${BUILD_NUMBER}
                        '''
                    }
                }
            }
        }

        stage('Deploy Build Artifacts') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Ensure the build_version directory exists
                    mkdir -p /var/www/html/Spacece-HRMS/build_version/build_${BUILD_NUMBER}

                    # Extract the .tar.gz file and copy its content into the build directory
                    tar -xzf hrms_build_${BUILD_NUMBER}.tar.gz -C /var/www/html/Spacece-HRMS/build_version/build_${BUILD_NUMBER}
                    '''
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Keep only the latest 5 builds
                    ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | head -n -5 | xargs -I {} rm -rf /var/www/html/Spacece-HRMS/build_version/{}
                    '''
                }
            }
        }

        stage('Update Webpage') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Generate a webpage with the latest 5 builds
                    echo "<html><body><h1>HRMS Development Builds</h1><ul>" > /var/www/html/Spacece-HRMS/index.html
                    for version in $(ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | tail -n 5); do
                        echo "<li><a href='/build_version/$version'>$version</a></li>" >> /var/www/html/Spacece-HRMS/index.html
                    done
                    echo "</ul></body></html>" >> /var/www/html/Spacece-HRMS/index.html
                    '''
                }
            }
        }

        stage('Send Email Notification') {
            steps {
                emailext(
                    subject: "Build ${BUILD_NUMBER} - Successful",
                    body: """The build version ${BUILD_NUMBER} has been successfully deployed.
                    Access the latest builds at: http://43.204.210.9/
                    """,
                    recipientProviders: [[$class: 'CulpritsRecipientProvider']],
                    to: 'aishwaryagaikwad7376@gmail.com'
                )
            }
        }
    }

    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            echo 'Pipeline executed successfully!'
        }
        failure {
            echo 'Pipeline failed. Check logs for details.'
        }
    }
}
