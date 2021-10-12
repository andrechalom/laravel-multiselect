# We initialize our image with Alpine linux
FROM alpine

# We install the necessary and some optional system dependencies
RUN apk --update add wget \
                     curl \
                     php8 \
                     php8-phar \
                     php8-mbstring \
                     php8-openssl \
                     php8-curl \
                     php8-dom \
                     php8-tokenizer \
                     php8-xml \
                     php8-xmlwriter

# PHP binary is being installed as php8, so we provide a convenient link
RUN ln -s /usr/bin/php8 /usr/bin/php

# Here we install the Composer package manager
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# We make sure there is a /var/www directory and copy the current project there.
RUN mkdir -p /var/www
WORKDIR /var/www
COPY . /var/www
VOLUME /var/www

RUN composer install

CMD ["/bin/sh"]