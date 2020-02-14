.phony: test lint format regen-autoload

test:
	cd ./Framework; $(MAKE) test
	cd ./BungleBundle; $(MAKE) test

lint:
	cd ./Framework; $(MAKE) lint
	cd ./BungleBundle; $(MAKE) lint

format:
	cd ./Framework; $(MAKE) format
	cd ./BungleBundle; $(MAKE) format

regen-autoload:
	composer dump-autoload
