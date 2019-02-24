#!/bin/bash

#
# Установка сертификатов для разработки
#

cd "$(dirname "$0")"

echo 'Установка корневого сертификата'
# curl -sS http://testca2012.cryptopro.ru/cert/rootca.cer | docker exec -i cryptopro /scripts/root
curl -sS http://testca.cryptopro.ru/CertEnroll/test-ca-2014_CRYPTO-PRO%20Test%20Center%202.crt | docker exec -i cryptopro /scripts/root

echo 'Установка сертификатов'
cat ../certificates/bundle-cosign.zip | docker exec -i cryptopro /scripts/my 12345678
cat ../certificates/bundle-Test.zip | docker exec -i cryptopro /scripts/my
