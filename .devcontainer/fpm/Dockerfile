FROM php:8-fpm

# This Dockerfile adds a non-root user with sudo access. Update the “remoteUser” property in
# devcontainer.json to use it. More info: https://aka.ms/vscode-remote/containers/non-root-user.
ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN apt-get update && apt-get install -y \
    git \
    openssh-server \
    sudo \
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

# Create git user and group. Password is 12345678
COPY id_ed25519.pub /home/git/.ssh/authorized_keys
RUN groupadd git && useradd -ms /bin/bash -g git -G $USER_GID -p nCZINfKwi0X2c git \
    && mkdir -p /home/git/.ssh \
    && chown git:git /home/git/.ssh/authorized_keys && chmod 600 /home/git/.ssh/authorized_keys \
    && echo "    PubkeyAuthentication yes" >> /etc/ssh/ssh_config \
    && service ssh start

# Expose docker port 22
# EXPOSE 22
# EXPOSE 80
