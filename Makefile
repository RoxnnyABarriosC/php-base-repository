include config.mk
include default.mk

#########################       CONFIG      #######################

network:
	@echo '************                               ************'
	@echo '************         CREATE NETWORK        ************'
	@echo '************                               ************'
	sh network.sh $(PROJECT_NAME)

volume:
	@echo '************                               ************'
	@echo '************         CLEAN VOLUME          ************'
	@echo '************                               ************'
	sh volume.sh $(PROJECT_NAME)


#########################       API       #######################

api:
	@echo '************                               ************'
	@echo '************             API     	      ************'
	@echo '************                               ************'
	STAGE=$(STAGE) \
			API_PORT=$(API_PORT) \
            docker compose -f docker-compose.yml \
            $(if $(filter local,$(MAKECMDGOALS)),-f docker-compose-dev.yml,) \
            $(if $(filter down,$(MAKECMDGOALS)),down,$(if $(filter stop,$(MAKECMDGOALS)),stop, up -d))  \
            $(if $(filter build,$(MAKECMDGOALS)),--build,)

#########################      SERVER      #######################

server:
	@echo '************                               ************'
	@echo '************            SERVER  	          ************'
	@echo '************                               ************'
	$(if $(filter local,$(MAKECMDGOALS)),sh local-server.sh,$(if $(filter dev,$(MAKECMDGOALS)),sh dev-server.sh, sh prod-server.sh))


#########################       EXEC       #######################

exec:
	@if [ -z "$(service)" ]; then \
		echo "Error: service is not specified. Use 'make exec service=service_name'"; \
		exit 1; \
	fi
	@echo "************                               ************"
	@echo "************             EXEC              ************"
	@echo "************                               ************"
	docker compose exec $(service) $(if $(filter sh,$(MAKECMDGOALS)),sh,bash)


#########################       CLEAN       #######################

clean:
	docker compose down $(if $(filter hard,$(MAKECMDGOALS)),-v --remove-orphans,--remove-orphans && sh volume.sh $(PROJECT_NAME))
	docker rmi $$(docker images -f "dangling=true" -q)
	docker ps -a | grep _run_ | awk '{print $$1}' | xargs -I {} docker rm {}

#########################       DUMMY       #######################

%:
	@:
