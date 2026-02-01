FROM php:8.3-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update \
    && apt-get install -y zlib1g-dev libicu-dev g++ libcurl4-openssl-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev libxml2-dev libwebp-dev  libxpm-dev \
    && apt-get install -y git less subversion unzip wget mariadb-client stow \
    && apt-get install -y libxrender1 libfontconfig \
    && apt-get install -y libc6 \
    && apt-get install -y libnss3-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y libonig-dev \
    && apt-get install -y tesseract-ocr \
    && apt-get install -y imagemagick \
    && apt-get install -y rsync

RUN docker-php-ext-configure bcmath && docker-php-ext-install bcmath
RUN docker-php-ext-configure curl && docker-php-ext-install curl
RUN docker-php-ext-configure exif && docker-php-ext-install exif
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && docker-php-ext-install gd
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-configure mbstring && docker-php-ext-install mbstring
RUN docker-php-ext-configure pdo && docker-php-ext-install pdo
RUN docker-php-ext-configure pdo_mysql && docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure soap && docker-php-ext-install soap
RUN docker-php-ext-configure zip && docker-php-ext-install zip
RUN docker-php-ext-configure ctype && docker-php-ext-install ctype

RUN echo "date.timezone = Europe/Paris" >> /usr/local/etc/php/php.ini \
    && echo "short_open_tag = Off" >> /usr/local/etc/php/php.ini

RUN cd /usr/local/bin \
    && curl -sS https://getcomposer.org/installer > composer-setup.php \
    && php ./composer-setup.php --2 \
    && mv composer.phar composer \
    && chmod a+x composer

RUN apt-get install libldap2-dev -y
RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure ldap
RUN docker-php-ext-install ldap

ENV COMPOSER_HOME /composer

COPY mysql.ini /usr/local/etc/php/conf.d/mysql.ini

RUN mkdir -p /home/kop && chmod a+rwx /home/kop
ENV HOME /home/kop
WORKDIR /var/www/kop

ENV GOSU_VERSION 1.10
RUN arch="$(dpkg --print-architecture | awk -F- '{ print $NF }')" \
    && wget -O gosu "https://github.com/tianon/gosu/releases/download/1.10/gosu-$arch" \
    && mv ./gosu /usr/local/bin/gosu \
    && chmod +x /usr/local/bin/gosu

RUN sed -i 's/\/proc\/self\/fd\/2/\/var\/log\/php-fpm.log/g' /usr/local/etc/php-fpm.d/docker.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod a+x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php-fpm"]
