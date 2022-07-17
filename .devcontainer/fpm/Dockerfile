FROM php:8-fpm

# This Dockerfile adds a non-root user with sudo access. Update the “remoteUser” property in
# devcontainer.json to use it. More info: https://aka.ms/vscode-remote/containers/non-root-user.
ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN apt-get update && apt-get install -y \
    git \
    && rm -rf /var/lib/apt/lists/*

# Create a non-root user to use if preferred - see https://aka.ms/vscode-remote/containers/non-root-user.
RUN addgroup --gid $USER_GID $USERNAME \
    && adduser --uid $USER_UID --ingroup $USERNAME $USERNAME \
    && sed -i "/user =/c\user = $USERNAME" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "/group =/c\group = $USERNAME" /usr/local/etc/php-fpm.d/www.conf

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY php.ini "$PHP_INI_DIR/conf.d/php.ini"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer clear-cache \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

# Install extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions gd pdo_mysql zip bcmath \
    && rm /usr/local/bin/install-php-extensions
    #
    # Configure xdebug
    # && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    # && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    # && echo "xdebug.remote_port=9100" >> /usr/local/etc/php/conf.d/xdebug.ini

# RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
#     && pecl install xdebug-2.8.1 \
#     && docker-php-ext-enable xdebug \
#     && apk del pcre-dev ${PHPIZE_DEPS}

# RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo "xdebug.remote_port=9100" >> /usr/local/etc/php/conf.d/xdebug.ini

# RUN apk add --no-cache zlib zlib-dev libpng libpng-dev libjpeg-turbo \
#     git libjpeg-turbo-dev \
#     && docker-php-ext-configure gd --with-jpeg=/usr/include/ \
#     && docker-php-ext-install -j$(nproc) gd \
#     && apk del zlib-dev libpng-dev libjpeg-turbo-dev

USER $USERNAME
