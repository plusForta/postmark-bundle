pipeline {
    agent {
        docker {
            image 'plusforta/php-ci:7.3'
            registryUrl "https://dock.pfdev.de"
            registryCredentialsId "cec23a25-eb2e-4331-bb78-940508d74d39"
            reuseNode true
        }
    }
    stages {
        stage('Build') {
            steps {
                sh 'composer install'
            }
        }
        stage('Psalm') {
           steps {
               sh './vendor/bin/psalm'
           }
        }
        stage('PHPUnit') {
            steps {
                sh './vendor/bin/simple-phpunit'
            }
        }
    }
 }