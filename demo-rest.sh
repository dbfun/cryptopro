#!/bin/bash

#
# Демонстрация работы REST сервера
#

cd "$(dirname "$0")"
source scripts/lib/colors.sh
source scripts/lib/functions.sh

REST_URI="http://localhost:8095"
FILE_TO_SIGN="README.md"
CERT_QUERY="find_type=sha1&query=82028260efc03eedc88dcb61c0f6a02e788e26e2&pin=12345678"

warning 'Установка корневого сертификата'
curl -sS http://testca.cryptopro.ru/CertEnroll/test-ca-2014_CRYPTO-PRO%20Test%20Center%202.crt | docker exec -i cryptopro /scripts/root

warning 'Установка сертификатов пользователя'
cat certificates/bundle-Test.zip | docker exec -i cryptopro /scripts/my
cat certificates/bundle-cosign.zip | docker exec -i cryptopro /scripts/my 12345678

warning "Подписание файла $FILE_TO_SIGN"
curl -sS -X POST --data-binary @- "$REST_URI/sign?$CERT_QUERY" < "$FILE_TO_SIGN" > /tmp/file.json

jq .status "/tmp/file.json"

warning "Проверка подписи"
jq ".signedContent" --raw-output /tmp/file.json > /tmp/file.sig
curl -sS -X POST --data-binary @- "$REST_URI/verify" < /tmp/file.sig | jq .status

warning "Получение исходного файла"
curl -sS -X POST --data-binary @- "$REST_URI/unsign" < /tmp/file.sig > /tmp/unsig.json
jq ".content" --raw-output /tmp/unsig.json | base64 -d | head -n5
