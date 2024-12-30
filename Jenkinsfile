pipeline {
    agent any

    stages {
        stage('Deploy HRMS') {
            steps {
                sshPublisher(
                    publishers: [
                        sshPublisherDesc(
                            configName: 'hrms-server', 
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
                                    remoteDirectory: '/var/www/html/Spacece-HRMS', 
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
            }
        }
    }
}
