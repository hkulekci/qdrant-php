# =====================================================================================================================
FROM php:7.2-cli-buster

ARG USER_UID=1000
ARG USER_GID=1000
ARG USERNAME=www-data
ARG CURRENT_ENVIRONMENT=production

# Install unzip utility and libs needed by zip PHP extension
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip

# install some base extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    IPE_GD_WITHOUTAVIF=1 install-php-extensions amqp apcu bcmath bz2 calendar exif gd gmp imagick imap intl ldap  \
    mysqli opcache pcntl pdo_mysql pdo_pgsql pgsql redis xmlrpc zip xsl

# install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# setup basic WORK DIRECTORY
WORKDIR /app

# copy base php configuration
RUN mv "$PHP_INI_DIR/php.ini-$CURRENT_ENVIRONMENT" "$PHP_INI_DIR/php.ini"

# Re-setup permissions
RUN groupmod --gid $USER_GID $USERNAME && usermod --uid $USER_UID --gid $USER_GID $USERNAME

# copy app
COPY --chown=$USER_UID:$USER_GID . /app

# create empty folder for vendor, setup folder permissions and render start.sh executable
RUN mkdir vendor && \
	mkdir /var/www/.composer && \
	chown -R $USER_UID:$USER_GID /var/www/.composer

# set default user when running exec into container
USER $USERNAME
# =====================================================================================================================
