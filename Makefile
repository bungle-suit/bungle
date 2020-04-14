.phony: test lint format regen-autoload

test:
	cd ./Framework; $(MAKE) test
	cd ./BungleBundle; $(MAKE) test
	cd ./DingTalk; $(MAKE) test

lint:
	cd ./Framework; $(MAKE) lint
	cd ./BungleBundle; $(MAKE) lint
	cd ./DingTalk; $(MAKE) lint

format:
	cd ./Framework; $(MAKE) format
	cd ./BungleBundle; $(MAKE) format
	cd ./DingTalk; $(MAKE) format

publish:
	git subtree push --prefix=Framework framework master
	git subtree push --prefix=BungleBundle bunglebundle master
	git subtree push --prefix=DingTalk dingtalk master

regen-autoload:
	composer dump-autoload
