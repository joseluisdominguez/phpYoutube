#!/bin/bash

BACKGROUND=$1

if [ "$BACKGROUND" = "1" ]
then
	echo "RUN docker-compose in background mode"
    docker-compose up -d
else
	echo "RUN docker-compose in foreground mode"
	docker-compose up
fi
