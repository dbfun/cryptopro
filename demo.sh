#!/bin/bash

#
# Демонстрация основных возможностей
#

cd "$(dirname "$0")"
source scripts/lib/colors.sh
source scripts/lib/functions.sh

warning 'Установка корневого сертификата'
info 'curl -sS http://testca2012.cryptopro.ru/cert/rootca.cer | docker exec -i cryptopro /scripts/root'
sleep 5
echo
curl -sS http://testca2012.cryptopro.ru/cert/rootca.cer | docker exec -i cryptopro /scripts/root

echo
sleep 2

warning 'Установка сертификата + закрытый ключ'
info 'cat certificates/bundle-pin.zip | docker exec -i cryptopro /scripts/my 12345678'
sleep 5
echo
cat certificates/bundle-pin.zip | docker exec -i cryptopro /scripts/my 12345678

echo
sleep 2

warning 'Подписание документа'
info 'cat README.md | docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678'
sleep 5
echo
cat README.md | docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678

echo
sleep 2

warning 'Проверка подписи документа'
info 'cat /tmp/README.md.sig | docker exec -i cryptopro scripts/verify'
cat README.md | docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678 > /tmp/README.md.sig
sleep 5
echo
cat /tmp/README.md.sig | docker exec -i cryptopro scripts/verify
