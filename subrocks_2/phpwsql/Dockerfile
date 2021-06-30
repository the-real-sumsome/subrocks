FROM php:7.4-apache
RUN docker-php-ext-install mysqli

# RUN pacman -S zlib1g-dev libicu-dev g++
# RUN docker-php-ext-configure intl
# RUN docker-php-ext-install intl
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git unzip libicu-dev libmcrypt-dev && \ 
    docker-php-ext-configure intl && \ 
    docker-php-ext-install intl && \ 
    docker-php-ext-install bcmath
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install packages
RUN composer require wixel/gump
RUN composer require twig/twig
# RUN composer require klein/klein 
# may not use klein