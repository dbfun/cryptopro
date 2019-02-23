#!/bin/bash

#
# Установка сертификатов для разработки
#

cd "$(dirname "$0")"

echo 'Установка корневого сертификата'
curl -sS http://testca2012.cryptopro.ru/cert/rootca.cer | docker exec -i cryptopro /scripts/root

echo 'Установка сертификата + закрытый ключ'
cat ../certificates/bundle-pin.zip | docker exec -i cryptopro /scripts/my 12345678
