#!/bin/bash

#
# REST
#

cd "$(dirname "$0")"
source ../scripts/lib/colors.sh
source ../scripts/lib/functions.sh

REST_URI="http://localhost:8095"
FILE_TO_SIGN="../README.md"

CERT_QUERY="find_type=sha1&query=82028260efc03eedc88dcb61c0f6a02e788e26e2&pin=12345678"

# Неверный метод
# curl -sS -X POST --data-binary "bindata" "$REST_URI/healthcheck"

# подписание документа
curl -sS -X POST --data-binary @- "$REST_URI/sign?$CERT_QUERY" < "$FILE_TO_SIGN" > /tmp/file.json

# проверка подписи
jq ".signedContent" --raw-output /tmp/file.json > /tmp/file.sig

# Не подходящий формат
# curl -sS -X POST --data-binary @- "$REST_URI/verify" < /tmp/file.json

# корректная проверка
curl -sS -X POST --data-binary @- "$REST_URI/verify" < /tmp/file.sig

# исходный файл
curl -sS -X POST --data-binary @- "$REST_URI/unsign" < /tmp/file.sig > /tmp/unsig.json
jq ".content" --raw-output /tmp/unsig.json | base64 -d | head

# добавление еще одной подписи
# CERT_QUERY="find_type=subject&query=Test"
# curl -sS -X POST --data-binary @- "$REST_URI/cosign?$CERT_QUERY" < /tmp/file.sig
