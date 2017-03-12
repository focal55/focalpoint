cc:
	php bin/console cache:clear

init:
	php bin/console doctrine:schema:drop --force
	php bin/console doctrine:schema:create
	php bin/console doctrine:fixtures:load --no-interaction

serve:
	php bin/console server:run

webpack-watch:
	npm run webpack-watch
