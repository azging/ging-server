git pull
bin/console assets:install

bin/console cache:clear
bin/console cache:clear --env=prod

chmod 777 var/cache -R
chmod 777 var/logs -R
chmod 777 var/sessions -R

bin/console doctrine:schema:update --dump-sql
bin/console doctrine:schema:update --force
