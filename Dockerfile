#
# Weblabs Dockerfile
# Copyrights to Wajdi Jurry <github.com/wajdijurry>
#
FROM php:8.0.2-cli

ARG DEBIAN_FRONTEND=noninteractive

# Important! To prevent this warning "debconf: unable to initialize frontend"
RUN apt-get install -yq apt-utils git 2>&1 | grep -v "debconf: delaying package configuration, since apt-utils is not installed"

# Update apt repos
RUN echo "Updating repos ..." && apt-get -y update > /dev/null

# Return working directory to its default state
WORKDIR /app

# Copy project files
ADD . ./

# Permit artisan to be executable
RUN chmod +x artisan

# Install required tools and dependencies
RUN chmod +x utilities/install-dependencies.sh && ./utilities/install-dependencies.sh

# Install required PHP extensions
RUN chmod +x utilities/install-php-extensions.sh && ./utilities/install-php-extensions.sh

# Install composer & dependencies
RUN echo "Installing Composer" && rm -rf vendor composer.lock && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer clearcache && \
    composer install

ENTRYPOINT ["/bin/bash", "utilities/docker-entrypoint.sh"]
