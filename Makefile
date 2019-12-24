.phony: test test-watch lint format lint-fix regen-autoload

test:
	./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/

test-watch:
	noisy.py -d 'tests src' -e .php -- './vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/'

lint:
	./vendor/bin/phpcs --standard=PSR2 src tests

lint-fix:
	./vendor/bin/phpcbf --standard=PSR2 src tests

format:
	# ./vendor/bin/php-cs-fixer fix tests
	./vendor/bin/php-cs-fixer fix src

regen-autoload:
	composer dump-autoload
