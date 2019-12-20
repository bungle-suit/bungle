.phony: test test-watch lint format lint-fix

test:
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/

test-watch:
	noisy.py -d tests -e .php -- './vendor/bin/phpunit --bootstrap vendor/autoload.php tests/'

lint:
	./vendor/bin/phpcs --standard=PSR2 src tests

lint-fix:
	./vendor/bin/phpcbf --standard=PSR2 src tests

format:
	# ./vendor/bin/php-cs-fixer fix tests
	./vendor/bin/php-cs-fixer fix src

