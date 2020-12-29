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

fixpermission: ## Fix permission for "database" and "logs" dir
	chmod -R g+wrX ./*

up: fixpermission ## Spins up docker container
	docker-compose up --build

down: ## Tear down the docker container
	docker-compose down

recreate: fixpermission ## Force recreate and start the docker container
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

tests: ## Runs phpunit
	# TODO: use test database and test container.
	docker exec -it $(appContainer) sh -c 'php artisan migrate && php artisan db:seed --class=UserTableSeeder && ./vendor/bin/phpunit -c phpunit.xml'

subscribe-channel: ## A redis cli tty to subscribe to users-event-channel: used for testing broadcasting events using redis websockets
	docker exec -it lumen-user-api-redis sh -c 'redis-cli SUBSCRIBE users-event-channel'

help: ## Prints this help screen.
	@printf "================================================\t\t\n"
	@printf "\t\t Lumen Users API \t\t\n"
	@printf "================================================\t\t\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sed -e "s/Makefile://" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

.PHONY: help artisan tests
