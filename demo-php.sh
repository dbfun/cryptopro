#!/bin/bash

#
# Демонстрация работы PHP
#

cd "$(dirname "$0")"
source scripts/lib/colors.sh
source scripts/lib/functions.sh

warning 'Установка корневого сертификата'
info 'curl -sS http://testca.cryptopro.ru/CertEnroll/test-ca-2014_CRYPTO-PRO%20Test%20Center%202.crt | docker exec -i cryptopro /scripts/root'
sleep 5
echo
curl -sS http://testca.cryptopro.ru/CertEnroll/test-ca-2014_CRYPTO-PRO%20Test%20Center%202.crt | docker exec -i cryptopro /scripts/root

echo
sleep 2

warning 'Установка сертификата + закрытый ключ'
info 'cat certificates/bundle-Test.zip | docker exec -i cryptopro /scripts/my'
sleep 5
echo
cat certificates/bundle-Test.zip | docker exec -i cryptopro /scripts/my

echo
sleep 2

warning 'Проверка cryptopro в PHP'
info 'docker exec -i cryptopro php /www/test_extension.php'
sleep 5
echo
docker exec -i cryptopro php /www/test_extension.php
