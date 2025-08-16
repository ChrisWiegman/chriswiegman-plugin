PLUGIN_VERSION  := $$(grep "Version:" chriswiegman-plugin.php | awk -F' ' '{print $3}' | cut -d ":" -f2 | sed 's/ //g')
ARGS            = `arg="$(filter-out $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

%:
	@:

.PHONY: change
change:
	docker run \
		--rm \
		--platform linux/amd64 \
		--mount type=bind,source=$(PWD),target=/src \
		-w /src \
		-it \
		ghcr.io/miniscruff/changie \
		new

.PHONY: changelog
changelog:
	docker run \
		--rm \
		--platform linux/amd64 \
		--mount type=bind,source=$(PWD),target=/src \
		-w /src \
		-it \
		ghcr.io/miniscruff/changie \
		batch $(call ARGS,defaultstring)
	docker run \
		--rm \
		--platform linux/amd64 \
		--mount type=bind,source=$(PWD),target=/src \
		-w /src \
		-it \
		ghcr.io/miniscruff/changie \
		merge

.PHONY: chriswiegman-plugin-version.zip
chriswiegman-plugin-version.zip: clean-release
	@echo "Building release file: chriswiegman-plugin.$(PLUGIN_VERSION).zip"
	PLUGIN_VERSION=$(PLUGIN_VERSION) && \
		cd ../ && \
		zip \
		--verbose \
		--recurse-paths \
		--exclude="*.changes/*" \
		--exclude="*.git/*" \
		--exclude="*.github/*" \
		--exclude="*.vscode/*" \
		--exclude="*node_modules/*" \
		--exclude="*.changie.yml" \
		--exclude="*.gitignore" \
		--exclude="*.kana.json" \
		--exclude="*.npmrc" \
		--exclude="*.nvmrc" \
		--exclude="*.wp-env.json" \
		--exclude="*CHANGELOG.md" \
		--exclude="*changie.yaml" \
		--exclude="*composer.json" \
		--exclude="*composer.lock" \
		--exclude="*Makefile" \
		--exclude="*package-lock.json" \
		--exclude="*package.json" \
		--exclude="*phpcs.xml" \
		--exclude="*phpunit.xml.dist" \
		--exclude="*README.md" \
		--exclude="*vendor/*" \
		--exclude="*wordpress/*" \
		--exclude="*database/*" \
		--exclude="*tests/*" \
		chriswiegman-plugin/chriswiegman-plugin.$$PLUGIN_VERSION.zip \
		chriswiegman-plugin/*
	if [ ! -f ./chriswiegman-plugin.$(PLUGIN_VERSION).zip  ]; then \
		echo "file not available"; \
		exit 1; \
	fi

.PHONY: clean
clean: clean-release
	@echo "Cleaning up development artifacts"
	rm -rf \
		node_modules \
		wordpress \
		vendor \
		.vscode/*.log \
		artifacts \
		.phpunit.result.cache \
		build

.PHONY: clean-release
clean-release:
	@echo "Cleaning up release file"
	rm -f chriswiegman-plugin*.zip

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

.PHONY: release
release: chriswiegman-plugin-version.zip

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