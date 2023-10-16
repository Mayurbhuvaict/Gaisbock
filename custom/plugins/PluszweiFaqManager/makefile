#
# Makefile
#

.PHONY: help
.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# ------------------------------------------------------------------------------------------------------------
install: ## Installs all production dependencies
	@composer validate
	@composer install --no-dev
	cd src/Resources/app/administration && npm install --production
	cd src/Resources/app/storefront && npm install --production

dev: ## Installs all dev dependencies
	@composer validate
	@composer install

phpunit: ## Starts all PHPUnit Tests
	php ./vendor/bin/phpunit --configuration=./phpunit.xml --log-junit report.xml

clean: ## Cleans all dependencies
	rm -rf vendor
	rm -rf ./src/Resources/app/administration/node_modules/*
	rm -rf ./src/Resources/app/administration/coverage/*
	rm -rf ./src/Resources/app/storefront/node_modules/*

build: ## Installs the plugin, and builds
	cd /var/www/html && php bin/console plugin:refresh
	cd /var/www/html && php bin/console plugin:install PluszweiFaqManager --activate | true
	cd /var/www/html && php bin/console plugin:refresh
	cd /var/www/html && php bin/console theme:dump
	cd /var/www/html && ./bin/build-js.sh
	cd /var/www/html && php bin/console theme:refresh
	cd /var/www/html && php bin/console theme:compile
	cd /var/www/html && php bin/console theme:refresh