help: ## Show this help message.
	$(info Available targets)
	@awk '/^[a-zA-Z\-\_0-9]+:/ {                    \
	  nb = sub( /^## /, "", helpMsg );              \
	  if(nb == 0) {                                 \
		helpMsg = $$0;                              \
		nb = sub( /^[^:]*:.* ## /, "", helpMsg );   \
	  }                                             \
	  if (nb)                                       \
		print  $$1 "\t" helpMsg;                    \
	}                                               \
	{ helpMsg = $$0 }'                              \
	$(MAKEFILE_LIST) | column -ts $$'\t' |          \
	grep --color '^[^ ]*'

install: ## Installs all dependencies and prerequisites.
	docker-compose run --rm php composer install --no-interaction --no-progress --no-suggest --prefer-dist

up: install ## Brings all containers up.
	docker-compose up -d

down: ## Brings all containers down (and removes any orphans)
	docker-compose down --remove-orphans

test: ## Runs all tests.
	docker-compose run --rm php vendor/bin/phpunit -c tests/phpunit.xml.dist

deptrac: ## Verifies contexts are not crossing boundaries
	docker-compose run --rm php vendor/bin/deptrac

cs: ## Verifies contexts are not crossing boundaries
	docker-compose run --rm php vendor/bin/php-cs-fixer fix --no-interaction --dry-run

compile: ## Compiles the package into a PHAR file for release purposes
	@test $(APP_VERSION) || (echo "You must pass the version to compile by appending APP_VERSION=YOUR_VERSION"; exit 1)
	@test -f ./box.phar || wget -q https://github.com/humbug/box/releases/download/3.8.0/box.phar && chmod u+x ./box.phar
	@test -f box.json || cp box.json.dist box.json
	@docker-compose run -e APP_VERSION=$(APP_VERSION) --rm php ./build/version-writer.php
	@docker-compose run --rm php ./box.phar compile -q
	@echo "Successfully compiled a new PHAR"

demo: ## Runs a check on the example directory
	@test -f ./bin/env-checker.phar || make compile APP_VERSION=DEMO
	docker-compose run --rm php ./bin/env-checker.phar check --informative ./example/specification/.env.dist ./example/implementation

.PHONY: install up down test deptrac cs compile demo
