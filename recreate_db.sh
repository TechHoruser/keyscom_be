#!/bin/sh

ENV=$1
bin/console doctrine:database:drop --force --env=$ENV 2&> /dev/null && \
bin/console doctrine:database:create --env=$ENV && \
rm -rf src/Infrastructure/Persistence/Doctrine/Migrations/*.php && \
bin/console doctrine:migrations:diff && \
bin/console --no-interaction doctrine:migrations:migrate --env=$ENV && \
yes | bin/console doctrine:fixtures:load --env=$ENV
