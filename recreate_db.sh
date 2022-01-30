bin/console doctrine:database:drop --force && \
bin/console doctrine:database:create && \
rm -rf src/Infrastructure/Persistence/Doctrine/Migrations/*.php && \
bin/console doctrine:migrations:diff && \
bin/console doctrine:migrations:migrate && \
bin/console doctrine:fixtures:load --env=dev
