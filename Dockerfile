FROM composer:2 AS dependencias
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader
COPY src/ src/
RUN composer dump-autoload --no-dev --optimize

FROM php:8.2-cli-alpine
WORKDIR /app
ARG APP_VERSION=dev
ENV APP_VERSION=${APP_VERSION}
COPY --from=dependencias /app/vendor/ vendor/
COPY src/ src/
COPY public/ public/
EXPOSE 8000
HEALTHCHECK --interval=30s --timeout=3s CMD wget -qO- http://localhost:8000/salud || exit 1
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
