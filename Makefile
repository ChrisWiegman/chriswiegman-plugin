DOCKER_RUN     := @docker run --rm
COMPOSER_IMAGE := -v "$$(pwd):/app" --user $$(id -u):$$(id -g) composer
HAS_LANDO      := $(shell command -v lando 2> /dev/null)
PLUGIN_VERSION := $$(grep "^ \* Version" chriswiegman-plugin.php| awk -F' ' '{print $3}' | cut -d ":" -f2 | sed 's/ //g')
HIGHLIGHT      :=\033[0;32m
END_HIGHLIGHT  :=\033[0m # No Color

.PHONY: build
build: build-pot-file  ## Generates a .pot file for use in translations.

.PHONY: build-pot-file
build-pot-file: | lando-start
	@echo "Generating .pot file"
	lando wp --path=./wordpress i18n make-pot --exclude="vender,wordpress,tests,node_modules,languages,.vscode,Docker" . ./languages/chriswiegman-plugin.pot

.PHONY: clean
clean: clean-assets clean-build  ## Removes all build files and the plugin files. This is destructive.

.PHONY: clean-assets
clean-assets:
	@echo "Cleaning up plugin assets"
	rm -rf \
		languages/*.pot

.PHONY: clean-build
clean-build:
	@echo "Cleaning up build-artifacts"
	rm -rf \
		node_modules \
		wordpress \
		vendor

.PHONY: destroy
destroy: ## Destroys the developer environment completely (this is irreversible)
	lando destroy -y
	$(MAKE) clean

.PHONY: flush-cache
flush-cache: ## Clears all server caches enabled within WordPress
	@echo "Flushing cache"
	lando wp cache flush --path=./wordpress

.PHONY: delete-transients
delete-transients: ## Deletes all WordPress transients stored in the database
	@echo "Deleting transients"
	lando wp transient delete --path=./wordpress --all

.PHONY: help
help:  ## Display help
	@awk -F ':|##' \
		'/^[^\t].+?:.*?##/ {\
			printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF \
		}' $(MAKEFILE_LIST) | sort

.PHONY: install
install: | clean-assets clean-build
	$(MAKE) install-composer

.PHONY: install-composer
install-composer:
	$(DOCKER_RUN) $(COMPOSER_IMAGE) install

.PHONY: lando-start
lando-start:
ifdef HAS_LANDO
	if [ ! -d ./wordpress/ ]; then \
		$(MAKE) install; \
	fi
	if [ ! "$$(docker ps | grep chriswiegmanplugin_appserver)" ]; then \
		echo "Starting Lando"; \
		lando start; \
	fi
	if [ ! -f ./wordpress/wp-config.php ]; then \
		$(MAKE) setup-wordpress; \
		$(MAKE) setup-wordpress-plugins; \
		$(MAKE) build-pot-file; \
		echo "Your dev site is at: ${HIGHLIGHT}https://chriswiegman-plugin.lndo.site${END_HIGHLIGHT}"; \
		echo "See the readme for further details."; \
	fi
endif

.PHONY: lando-stop
lando-stop:
ifdef HAS_LANDO
	if [ "$$(docker ps | grep chriswiegmanplugin_appserver)" ]; then \
		echo "Stopping Lando"; \
		lando stop; \
	fi
endif

.PHONY: open
open: ## Open the development site in your default browser
	open https://chriswiegman-plugin.lndo.site

.PHONY: open-db
open-db: ## Open the database in TablePlus
	@echo "Opening the database for direct access"
	open mysql://wordpress:wordpress@127.0.0.1:$$(lando info --service=database --path 0.external_connection.port | tr -d "'")/wordpress?enviroment=local&name=$database&safeModeLevel=0&advancedSafeModeLevel=0

.PHONY: open-site
open-site: open

.PHONY: release
release: | build-pot-file chriswiegman-plugin-version.zip ## Generates a release zip of the plugin

.PHONY: reset
reset: destroy start ## Resets a running dev environment to new

.PHONY: setup-wordpress
setup-wordpress:
	@echo "Setting up WordPress"
	lando wp core download --path=./wordpress --version=latest
	lando wp config create --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=database --path=./wordpress
	lando wp core install --path=./wordpress --url=https://chriswiegman-plugin.lndo.site --title="ChrisWiegman.com Functionality Development" --admin_user=admin --admin_password=password --admin_email=contact@chriswiegman.com

.PHONY: setup-wordpress-plugins
setup-wordpress-plugins:
	lando wp plugin install --path=./wordpress debug-bar --activate
	lando wp plugin install --path=./wordpress query-monitor --activate

.PHONY: start
start: lando-start open-site ## Starts the development environment including downloading and setting up everything it needs

.PHONY: stop
stop: lando-stop ## Stops the development environment. This is non-destructive.

.PHONY: test
test: test-lint test-phpunit  ## Run all testing

.PHONY: test-lint
test-lint: test-lint-php ## Run linting on both PHP and JavaScript

.PHONY: test-lint-php
test-lint-php: ## Run linting on PHP only
	@echo "Running PHP linting"
	./vendor/bin/phpcs --standard=./phpcs.xml

.PHONY: test-phpunit
test-phpunit: ## Run PhpUnit
	@echo "Running Unit Tests Without Coverage"
	docker run \
		-v "$$(pwd):/app" \
		--workdir /app \
		--rm \
		php:7.4-cli \
		/app/vendor/bin/phpunit

.PHONY: trust-lando-cert-mac
trust-lando-cert-mac: ## Trust Lando's SSL certificate on your mac
	@echo "Trusting Lando cert"
	sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ~/.lando/certs/lndo.site.pem

.PHONY: update-composer
update-composer:
	$(DOCKER_RUN) $(COMPOSER_IMAGE) update

.PHONY: chriswiegman-plugin-version.zip
chriswiegman-plugin-version.zip:
	@echo "Building release file: chriswiegman-plugin.$(PLUGIN_VERSION).zip"
	rm -rf chriswiegman-plugin.$(PLUGIN_VERSION).zip
	PLUGIN_VERSION=$(PLUGIN_VERSION) && cd ../ && zip --verbose -r -x=@chriswiegman-plugin/.zipignore chriswiegman-plugin/chriswiegman-plugin.$$PLUGIN_VERSION.zip chriswiegman-plugin/*
	if [ ! -f ./chriswiegman-plugin.$(PLUGIN_VERSION).zip  ]; then \
		echo "file not available"; \
		exit 1; \
	fi
