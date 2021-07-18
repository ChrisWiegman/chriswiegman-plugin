#!groovy
@Library('wpshared') _

node('docker') {

    workspace.cleanUp()

    wpe.pipeline('chriswiegman-plugin-build') {

        withEnv(["COMPOSE_PROJECT_NAME=chriswiegman-plugin-${BUILD_TAG}"]) {
            stage('Build') {
                sh 'make --debug=vji install-composer'
                sh 'make --debug=vji install-npm'
                sh 'make --debug=vji build-assets'
            }

            stage('Test') {
                sh 'make --debug=vji test-lint-php'
                sh 'make --debug=vji test-lint-javascript'
                sh 'make --debug=vji test-unit'
            }
        }
    }
}
