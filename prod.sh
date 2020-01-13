#!/bin/bash
set -e

mkdir "prod/"
OUTPUT_FILE="./prod/prod.zip"

export APP_ENV=prod
export APP_DEBUG=0

# Optimisations Symfony
composer install --no-dev --optimize-autoloader
php bin/console cache:clear

# Suppression fichiers inutiles
rm -rf var/cache
rm -rf var/log

# Si utilisation de Symfony Encore
if [[ -e "./package.json" ]]; then
    yarn run build
fi

# Construction de l'archive à envoyer sur le serveur web
if [[ -e "${OUTPUT_FILE}" ]]; then
    rm -v "${OUTPUT_FILE}"
fi

zip -9 -rqq "${OUTPUT_FILE}" . \
    -x=*docker/* \
    -x=*.idea/* \
    -x=*.bin/* \
    -x=*.assets/* \
    -x=*.templates/* \
    -x=*.tests/* \
    -x=*.dockerignore* \
    -x=*.git* \
    -x=*docker-compose.yaml* \
    -x=*Dockerfile* \
    -x=*README.md* \
    -x=*.phpunit** \
    -x=*./config/jwt/*.pem*

du -hs "${OUTPUT_FILE}"
md5sum "${OUTPUT_FILE}"