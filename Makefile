php-cs-fixer:
	./vendor/bin/php-cs-fixer check src -v

phpstan:
	./vendor/bin/phpstan analyse src tests

test:
	php bin/phpunit

play:
	php bin/console app:game
