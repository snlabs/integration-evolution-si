user := $(shell id -u)
group := $(shell id -g)

dc := USER_ID=$(user) GROUP_ID=$(group) docker compose
RUN = $(dc) run --rm api

 
KAFKA_SERVERS=kafka:9092
KAFKA_CONTAINER=kafka
EXEC_KAFKA=$(dc) exec $(KAFKA_CONTAINER)


.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

##
## Project run
##----------------------------------

up: ## Run containers
	$(dc) up -d --remove-orphans
	@echo "[Kafka] Create order_topic_test topic"
	 

start: up ## Start containers

stop: ## Remove containers
	$(dc) kill
	$(dc) rm -v --force

reset: stop start ## Reset containers

##
## Project setup
##----------------------------------
composer-install: ## Run composer install on all projects
	$(RUN) rm -rf compose.lock && $(RUN) rm -rf vendor/ && $(RUN) composer install -n


ssh:
	$(dc) exec api bash

ssh2:
	$(dc) exec app2 bash

db:
	$(dc) exec mysql bash
 