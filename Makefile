.phony: test lint regen-autoload

docker-run = docker run --name bungle -it --rm --entrypoint ''  --mount type=volume,src=php-global,dst=/home/work/.config --mount type=bind,src=${PWD},dst=/home/work/app bungle/base-dev

test-framework:
	$(docker-run)	./vendor/bin/phpunit --bootstrap ./Framework/tests/bootstrap.php ./Framework/tests/

test-bungle:
	$(docker-run)	./vendor/bin/phpunit --bootstrap ./BungleBundle/tests/bootstrap.php ./BungleBundle/tests/

test-dingtalk:
	$(docker-run)	./vendor/bin/phpunit --bootstrap ./DingTalk/tests/bootstrap.php ./DingTalk/tests/

test: test-framework test-bungle test-dingtalk

lint:
	cd ./Framework; $(MAKE) lint
	cd ./BungleBundle; $(MAKE) lint
	cd ./DingTalk; $(MAKE) lint

publish-framework:
	git subtree push --prefix=Framework framework master

publish-dingtalk:
	git subtree push --prefix=DingTalk dingtalk master

publish-bundle:
	git subtree push --prefix=BungleBundle bunglebundle master

publish: publish-framework publish-dingtalk publish-bundle

build-docker-images:
	docker build --tag bungle/base-dev ./Dockerfiles/base-dev

phpstan:
	vendor/bin/phpstan analys

phpstan-baseline:
	vendor/bin/phpstan analys --generate-baseline
