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

        stage('Build') {
            steps {
                sh '''
                # Build your project
                make build
                # Package the build artifact
                tar -czf hrms_build_${BUILD_NUMBER}.tar.gz *
                '''
            }
        }

        stage('Upload Build Artifact') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Upload the build artifact to the server
                    mv hrms_build_${BUILD_NUMBER}.tar.gz /var/www/html/Spacece-HRMS/builds/
                    '''
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Keep only the latest 5 builds
                    ls /var/www/html/Spacece-HRMS/builds/ | sort -V | head -n -5 | xargs -I {} rm /var/www/html/Spacece-HRMS/builds/{}
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
                    for version in $(ls /var/www/html/Spacece-HRMS/builds/ | sort -V | tail -n 5); do
                        echo "<li><a href='/builds/$version'>$version</a></li>" >> /var/www/html/Spacece-HRMS/index.html
                    done
                    echo "</ul></body></html>" >> /var/www/html/Spacece-HRMS/index.html
                    '''
                }
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
