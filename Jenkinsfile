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

        stage('Package Application') {
            steps {
                sh '''
                # Package the build artifact
                tar -czf hrms_build_${BUILD_NUMBER}.tar.gz ./Jenkinsfile ./README.md ./application ./assets ./composer.json ./contributing.md ./database ./error_log ./index.php ./license.txt ./readme.rst ./system
                '''
            }
        }

        stage('Upload Build Artifact') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Ensure the build_version directory exists
                    mkdir -p /var/www/html/Spacece-HRMS/build_version/
                    # Upload the build artifact
                    mv hrms_build_${BUILD_NUMBER}.tar.gz /var/www/html/Spacece-HRMS/build_version/
                    '''
                }
            }
        }

        stage('Cleanup Old Builds') {
            steps {
                sshagent(['hrms-dev']) {
                    sh '''
                    # Keep only the latest 5 builds
                    ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | head -n -5 | xargs -I {} rm /var/www/html/Spacece-HRMS/build_version/{}
                    '''
                }
            }
        }

        stage('Update Webpage') {
            steps {
                sshagent(['hrms-dev']) {
                    script {
                        // Fetch the latest 5 build versions
                        def buildFiles = sh(script: "ls /var/www/html/Spacece-HRMS/build_version/ | sort -V | tail -n 5", returnStdout: true).trim().split("\n")
                        
                        // Start generating the HTML content
                        def htmlContent = "<html><body><h1>HRMS Development Builds</h1><ul>\n"
                        
                        // Add each build file as a link in the HTML page
                        buildFiles.each { file ->
                            htmlContent += "<li><a href='/build_version/${file}'>${file}</a></li>\n"
                        }
                        
                        // Close the HTML tags
                        htmlContent += "</ul></body></html>"
                        
                        // Write the HTML content to the webpage
                        writeFile file: '/var/www/html/Spacece-HRMS/index.html', text: htmlContent
                    }
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
