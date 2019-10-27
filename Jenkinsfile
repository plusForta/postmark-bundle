pipeline {
     agent {
         docker {
             image 'onlineantrag-php'
             args '-p 3005:3001'
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