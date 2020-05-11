FROM archlinux

COPY mirrorlist /etc/pacman.d/

RUN pacman -Syu --noconfirm
RUN pacman -S --noconfirm composer nginx wget php-fpm php-intl community/xdebug unzip community/php-mongodb pacman-contrib && paccache -ruk0
RUN sed -i 's/;extension=iconv/extension=iconv/; s/;extension=intl/extension=intl/; s/;zend_extension=opcache/zend_extension=opcache/; s/;opcache.enable=1/opcache.enable=1/; s/;opcache.revalidate_freq=2/;opcache.revalidate_freq=0/; s/; process.max = 128/process.max = 128/' /etc/php/php.ini

RUN groupadd -g 1000 work && useradd --uid 1000 --gid 1000 --create-home work

USER work
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

ENV PATH=/home/work/.symfony/bin/:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
EXPOSE 8000
VOLUME /home/work/app
VOLUME /home/work/.config
WORKDIR /home/work/app
ENTRYPOINT ["/home/work/app/bin/console"]
CMD ["server:start"]
