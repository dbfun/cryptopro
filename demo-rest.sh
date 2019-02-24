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

# warning 'Установка корневого сертификата'
curl -sS http://testca.cryptopro.ru/CertEnroll/test-ca-2014_CRYPTO-PRO%20Test%20Center%202.crt | docker exec -i cryptopro /scripts/root > /dev/null

# warning 'Установка сертификатов пользователя'
cat certificates/bundle-Test.zip | docker exec -i cryptopro /scripts/my > /dev/null
cat certificates/bundle-cosign.zip | docker exec -i cryptopro /scripts/my 12345678 > /dev/null

warning "Подписание файла $FILE_TO_SIGN"
info "curl -sS -X POST --data-binary @- "$REST_URI/sign?$CERT_QUERY" < "$FILE_TO_SIGN" > /tmp/file.json"
curl -sS -X POST --data-binary @- "$REST_URI/sign?$CERT_QUERY" < "$FILE_TO_SIGN" > /tmp/file.json

jq .status "/tmp/file.json"

echo
sleep 3

warning "Проверка подписи"
jq ".signedContent" --raw-output /tmp/file.json > /tmp/file.sig
info "curl -sS -X POST --data-binary @- "$REST_URI/verify" < /tmp/file.sig | jq .status"
curl -sS -X POST --data-binary @- "$REST_URI/verify" < /tmp/file.sig | jq .status

echo
sleep 3

warning "Получение исходного файла"
info "curl -sS -X POST --data-binary @- "$REST_URI/unsign" < /tmp/file.sig > /tmp/unsig.json"
curl -sS -X POST --data-binary @- "$REST_URI/unsign" < /tmp/file.sig > /tmp/unsig.json
jq .status "/tmp/unsig.json"

echo
sleep 3

warning "Первые несколько строк исходного файла"
jq ".content" --raw-output /tmp/unsig.json | base64 -d | head -n5
