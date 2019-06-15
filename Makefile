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
	#docker-compose run --rm php composer install
	composer install

up: install ## Brings all containers up.
	docker-compose up -d

down: ## Brings all containers down (and removes any orphans)
	docker-compose down --remove-orphans

test: ## Runs all tests.
	#docker-compose run --rm php vendor/bin/phpunit -c tests/unit/phpunit.xml.dist $(PHPUNIT_ARGS)
	php vendor/bin/phpunit -c tests/phpunit.xml.dist $(PHPUNIT_ARGS)

deptrac: ## Verifies contexts are not crossing boundaries
	#docker-compose run --rm php vendor/bin/deptrac
	php vendor/bin/deptrac

cs: ## Verifies contexts are not crossing boundaries
	#docker-compose run --rm php vendor/bin/deptrac
	php vendor/bin/php-cs-fixer fix --no-interaction --dry-run

.PHONY: install up down test deptrac cs
