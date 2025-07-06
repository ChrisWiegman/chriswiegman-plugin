%:
	@:

.PHONY: clean
clean:
	@echo "Cleaning up development artifacts"
	rm -rf \
		node_modules \
		wordpress \
		vendor \
		.vscode/*.log \
		artifacts \
		.phpunit.result.cache

.PHONY: destroy
destroy: ## Destroys the developer environment completely (this is irreversible)
	if [ -d ./wordpress/ ]; then \
		kana destroy --force; \
	fi
	$(MAKE) clean

.PHONY: help
help:  ## Display help
	@awk -F ':|##' \
		'/^[^\t].+?:.*?##/ {\
			printf "\033[36m%-30s\033[0m %s\n", $$1, $$NF \
		}' $(MAKEFILE_LIST) | sort

.PHONY: install
install: | install-composer install-npm

.PHONY: install-composer
install-composer:
	composer install

.PHONY: install-npm
install-npm:
	npm ci

.PHONY: reset
reset: destroy start ## Resets a running dev environment to new

.PHONY: start
start: ## Starts the development environment including downloading and setting up everything it needs
	if [ ! -d ./wordpress/ ]; then \
		$(MAKE) install; \
	fi
	if [ ! "$$(docker ps | grep kana-chriswiegman-plugin-wordpress)" ]; then \
		kana start; \
	fi

.PHONY: stop
stop: ## Stops the development environment. This is non-destructive.
	if [ "$$(docker ps | grep kana-chriswiegman-plugin-wordpress)" ]; then \
		kana stop; \
	fi

.PHONY: test
test: test-lint test-phpunit  ## Run all testing

.PHONY: test-lint
test-lint: test-lint-php ## Run linting on both PHP and JavaScript

.PHONY: test-lint-php
test-lint-php: ## Run linting on PHP only
	@echo "Running PHP linting"
	if [ ! -d ./vendor/ ]; then \
		$(MAKE) install-composer; \
	fi
	./vendor/bin/phpcs --standard=./phpcs.xml

.PHONY: test-phpunit
test-phpunit: ## Run PhpUnit
	@echo "Running Unit Tests Without Coverage"
	if [ ! -d ./vendor/ ]; then \
		$(MAKE) install-composer; \
	fi
	./vendor/bin/phpunit

.PHONY: update
update: | update-composer update-npm

.PHONY: update-composer
update-composer:
	composer update

.PHONY: update-npm
update-npm:
	npm update

.PHONY: watch
watch:
	@echo "Building and watching plugin assets"
	npm run watch