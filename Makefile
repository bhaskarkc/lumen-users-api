# Makefile
#
#
# Author: Bhaskar K <xlinkerz@gmail.com>


## Load .env
ifneq (,$(wildcard ./.env))
    include .env
    export
endif

.DEFAULT_GOAL := help
SHELL := /usr/bin/env bash

containerDbCred =  -h$(DB_HOST) -u$(DB_USERNAME) -p$(DB_PASSWORD)
appContainer = lumen-user-api
testImg = test-img
testContainer = test-lumen-user-api


export UID=$(shell id -u)
export GID=$(shell id -g)

up: ## Spins up docker container
	docker-compose up --build

down: ## Tear down the docker container
	docker-compose down

recreate: ## Force recreate and start the docker container
	docker-compose up --build --force-recreate

dbshow: ## Show tables
	docker exec -it $(appContainer) bash -c 'mysqlshow $(containerDbCred) $(filter-out $@,$(MAKECMDGOALS))'

dbschema: ## Dump mysql db
	docker exec -it $(appContainer) bash -c 'mysqldump ${containerDbCred}  ${DB_DATABASE}'

artisan: ## Runs php artisan commands
	docker exec -it $(appContainer) bash -c "php artisan $(filter-out $@,$(MAKECMDGOALS))"

testbuild: ## Build test docker image
	# https://docs.docker.com/engine/reference/commandline/images/#filtering
	docker images -aq --filter='reference=$(testContainer)' --format "{{.ID}}" | xargs -r -P $(procs) docker image rm -f || true
	docker build -t $(testImg) -f  Dockerfile-test .

phpunit: testbuild ## Builds a new test container and runs phpunit test on it
	# https://docs.docker.com/engine/reference/commandline/ps/#filtering
	docker ps -aq --filter name=^$(testContainer) | xargs -r -P $(procs) docker container rm -f || true
	docker run --rm --name $(testContainer) $(testImg)  sh -c '/var/www/html/tests/phpunit/phpunit -c /var/www/html/tests/phpunit/phpunit.xml'

phpunit-local: ## Runs phpunit from the application container.
	docker exec -it $(appContainer) sh -c 'composer install --dev && vendor/bin/phpunit -c tests/phpunit/phpunit.xml'

help: ## Prints this help screen.
	@printf "================================================\t\t\n"
	@printf "\t\t Lumen Users API \t\t\n"
	@printf "================================================\t\t\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sed -e "s/Makefile://" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

.PHONY: help artisan \-\-queue
