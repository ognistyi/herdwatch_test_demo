## This repository contains .env files, just to avoid wasting time trying to deploy this demo. 

docker-compose up -d --build

docker-compose exec server bash

## Server
server first run:
./bin/console make:migration
./bin/console doctrine:migrations:migrate


## Client
docker-compose exec client bash

./bin/console api:ListGroups
./bin/console api:ListUsers [group-name]
./bin/console api:CreateUser "John Doe" "john@example.com"
./bin/console api:DeleteUser [id]
./bin/console api:ViewUser [id]

./bin/console api:CreateGroup "TestGroup"

Test api:
curl "http://server:8000/api/users"
