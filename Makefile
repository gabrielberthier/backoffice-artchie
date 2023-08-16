CONTAINER=roadrunner
ccontainer=""

include ./.docker/roadrunner/conf/rr.env

up: docker-compose.yml
	docker compose up -d 

up-rr: roadrunner-docker-compose.yml
	PHP_IMAGE_VERSION=${PHP_IMAGE_VERSION} RR=true docker compose -f roadrunner-docker-compose.yml up -d
	echo ${PHP_IMAGE_VERSION}

build: docker-compose.yml
	docker compose rm -vsf
	docker compose down -v --remove-orphans
	docker compose build
	docker compose up -d

down:
	docker compose down

init:
	echo "Stating Initialization..."
ifeq ("$(wildcard $(.env))","")
	echo "Copy .env file..."
	cp -n .env.example .env
endif
	echo "Build docker images..."
	build up
	
login-rr:
	docker compose exec ${CONTAINER} bash

access:
	docker compose exec ${CONTAINER} bash

logs: 
	docker compose logs $1

exec-rr:
	docker compose exec roadrunner rr -c /etc/rr.yaml $1

migrate:
	docker compose exec ${CONTAINER} sh -c "vendor/bin/doctrine-migrations migrate;" 

test:
	composer run test

doctrine-test:
	composer run test --group doctrine