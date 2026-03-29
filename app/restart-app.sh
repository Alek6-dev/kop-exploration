#!/bin/bash

docker compose down -v
source .env.local
DOCKER_NODE_PORT=$DOCKER_NODE_PORT docker compose up -d
