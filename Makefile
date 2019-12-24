.phony: test test-watch lint format lint-fix regen-autoload

test:
	./vendor/bin/phpunit --bootstrap Tests/bootstrap.php Tests/

test-watch:
	noisy.py -d '.' -e .php -- './vendor/bin/phpunit --bootstrap Tests/bootstrap.php Tests/'

lint:
	./vendor/bin/phpcs --standard=PSR2 --ignore=vendor .

lint-fix:
	./vendor/bin/phpcbf --standard=PSR2 --ignore=vendor .

format:
	# ./vendor/bin/php-cs-fixer fix tests
	./vendor/bin/php-cs-fixer fix .

regen-autoload:
	composer dump-autoload
