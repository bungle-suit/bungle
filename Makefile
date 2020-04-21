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

publish-framework:
	git subtree push --prefix=Framework framework master

publish-dingtalk:
	git subtree push --prefix=DingTalk dingtalk master

publish-bundle:
	git subtree push --prefix=BungleBundle bunglebundle master

publish: publish-framework publish-dingtalk publish-bundle

regen-autoload:
	composer dump-autoload
