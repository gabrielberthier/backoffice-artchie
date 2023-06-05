

CONTAINER=roadrunner

up: docker-compose.yml
	docker compose up -d 

up-rr: roadrunner-docker-compose.yml
	docker-compose -f roadrunner-docker-compose.yml

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
	container=${1:-CONTAINER}

	echo "Attempt to login ${container} container..."
	docker compose exec ${container} bash

logs: 
	docker compose logs $1

exec-rr:
	docker compose exec roadrunner rr -c /etc/rr.yaml $1
