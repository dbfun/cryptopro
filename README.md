# КриптоПро 4.0 в докер контейнере

Содержимое контейнера:

* PHP7 с установленным расширением `libphpcades` (`CPStore`, `CPSigner`, `CPSignedData`)
* инструменты КриптоПро: `certmgr`, `cpverify`, `cryptcp`, `csptest`, `csptestf`, `der2xer`, `inittst`, `wipefile`, `cpconfig`
* вспомогательные скрипты командой строки
* HTTP REST-сервер

Есть 3 варианта использования контейнера:

* [через интерфейс командной строки](#cli) (и ssh-клиент для удаленных машин)
* [через HTTP REST-сервер](#http)
* добавить свои обработчики внутрь контейнера

# Структура проекта

```
├── assets        - материалы для README.md
├── devel         - devel скрипты
├── certificates  - тестовые сертификаты
├── dist          - пакеты КриптоПро (необходимо скачать с официального сайта)
├── Dockerfile    - файл сборки образа
├── README.md     - этот файл
└── scripts       - вспомогательные скрипты командой строки
└── www           - HTTP REST-сервер
````

# Создание образа из исходного кода

Скачать с официального сайта в `dist/` (необходимо быть залогиненым в системе):

* [КриптоПро CSP 4.0 для Linux (x64, deb)](https://www.cryptopro.ru/products/csp/downloads) => `dist/linux-amd64_deb.tgz`
* Browser plug-in [версия 2.0 для пользователей](https://www.cryptopro.ru/products/cades/plugin) => `dist/cades_linux_amd64.tar.gz` - Linux версию
* [Патч для PHP7](https://www.cryptopro.ru/forum2/default.aspx?g=posts&m=79589#post79589) => `dist/php7_support.patch.zip`

Запустить:

```
docker build --tag required/cryptopro .
```

## Возможные проблемы

В `Dockerfile` содержатся названия пакетов, например `lsb-cprocsp-devel-4.0.9921-5.noarch.rpm`, которые могут заменить новой версией. Следует поправить названия пакетов в `Dockerfile`.

# Запуск контейнера

Запустим контейнер под именем `cryptopro`, к которому будем обращаться в примерах:

```
docker run -it --rm -p 8095:80 --name cryptopro required/cryptopro
```

# Работа с контейнером через интерфейс командной строки<a name="cli"></a>

## Лицензия

Установка серийного номера:

```
docker exec -i cryptopro cpconfig -license -set <серийный_номер>
```

Просмотр:

```
docker exec -i cryptopro cpconfig -license -view
```

![license](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/license.gif)


## Установка корневых сертификатов

Для установки корневых сертификатов нужно на `stdin` скрипта `/scripts/root` передать файл с сертификатами. Если в файле несколько сертификатов, все они будут установлены.

### Через скачивание на диск

Скачаем сертификат на диск с помощью `curl` и передадим полученный файл на `stdin` с запуском команды его установки:

```
curl -sS http://cpca.cryptopro.ru/cacer.p7b > certificates/cacer.p7b
cat certificates/cacer.p7b | docker exec -i cryptopro /scripts/root
```

### Без скачивания на диск

```
# сертификаты УЦ
curl -sS http://cpca.cryptopro.ru/cacer.p7b | docker exec -i cryptopro /scripts/root
# сертификаты тестового УЦ
curl -sS http://testca2012.cryptopro.ru/cert/rootca.cer | docker exec -i cryptopro /scripts/root
curl -sS http://testca2012.cryptopro.ru/cert/subca.cer | docker exec -i cryptopro /scripts/root
```

![cacer](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/cacer.gif)

Примечание: по какой-то причине иногда "заедает", но при повторном запуске - срабатывает.

## Установка сертификатов пользователя для проверки и подписания

Необходимо специальным образом сформировать zip-архив `bundle.zip` и отправить его на `stdin` скрипта `/scripts/my`. Пример такого zip-файла:

```
├── certificate.cer - файл сертификата (не обязательно)
└── le-09650.000 - каталог с файлами закрытого ключа (не обязательно)
    ├── header.key
    ├── masks2.key
    ├── masks.key
    ├── name.key
    ├── primary2.key
    └── primary.key
```

[Как получить сертификат КриптоПро](http://pushorigin.ru/cryptopro/real-cert-crypto-pro-linux).

Первый найденный файл в корне архива будет воспринят как сертификат, а первый найденный каталог - как связка файлов закрытого ключа. Пароль от контейнера, если есть, передается первым параметром командной строки.

В каталоге `certificates/` содержатся различные комбинации тестового сертификата и закрытого ключа, с PIN кодом и без:

```
├── bundle-cert-only.zip          - только сертификат
├── bundle-cosign.zip             - сертификат + закрытый ключ БЕЗ пин-кода (для добавления второй подписи)
├── bundle-cyrillic.zip           - сертификат + закрытый ключ, название контейнера "тестовое название контейнера" (кириллица)
├── bundle-no-pin.zip             - сертификат + закрытый ключ БЕЗ пин-кода
├── bundle-pin.zip                - сертификат + закрытый ключ с пин-кодом 12345678
└── bundle-private-key-only.zip   - только закрытый ключ
```

Примеры:

```
# сертификат + закрытый ключ с пин-кодом
cat certificates/bundle-pin.zip | docker exec -i cryptopro /scripts/my 12345678

# сертификат + закрытый ключ БЕЗ пин-кода
cat certificates/bundle-no-pin.zip | docker exec -i cryptopro /scripts/my

# только сертификат
cat certificates/bundle-cert-only.zip | docker exec -i cryptopro /scripts/my

# только закрытый ключ
cat certificates/bundle-private-key-only.zip | docker exec -i cryptopro /scripts/my

# сертификат + закрытый ключ, название контейнера "тестовое название контейнера" (кириллица)
cat certificates/bundle-cyrillic.zip | docker exec -i cryptopro /scripts/my
```

![my-cert](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/my-cert.gif)

## Просмотр установленных сертификатов

Сертификаты пользователя:

```
docker exec -i cryptopro certmgr -list
```

![show-certs](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/show-certs.gif)

Корневые сертификаты:

```
docker exec -i cryptopro certmgr -list -store root
```

## Подписание документа

Для примера установим этот тестовый сертификат:

```
# сертификат + закрытый ключ с пин-кодом
cat certificates/bundle-pin.zip | docker exec -i cryptopro /scripts/my 12345678
```

Его SHA1 Hash равен `dd45247ab9db600dca42cc36c1141262fa60e3fe` (узнать: `certmgr -list`), который будем использовать как указатель нужного сертификата.

Теперь передадим на `stdin` файл, в качестве команды - последовательность действий, и на `stdout` получим подписанный файл:

```
cat README.md | docker exec -i cryptopro sh -c 'tmp=`mktemp`; cat - > "$tmp"; cryptcp -sign -thumbprint dd45247ab9db600dca42cc36c1141262fa60e3fe -nochain -pin 12345678 "$tmp" "$tmp.sig" > /dev/null 2>&1; cat "$tmp.sig"; rm -f "$tmp" "$tmp.sig"'
```

Получилось довольно неудобно. Скрипт `scripts/sign` делает то же самое, теперь команда подписания будет выглядеть так:

```
cat README.md | docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678
```

![sign](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/sign.gif)

Об ошибке можно узнать через стандартный `$?`.

## Проверка подписи

Подпишем файл из примера выше и сохраним его на диск:

```
cat README.md | docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678 > certificates/README.md.sig
```

Тогда проверка подписанного файла будет выглядеть так:

```
cat certificates/README.md.sig | docker exec -i cryptopro sh -c 'tmp=`mktemp`; cat - > "$tmp"; cryptcp -verify -norev -f "$tmp" "$tmp"; rm -f "$tmp"'
```

То же самое, но с использованием скрипта:

```
cat certificates/README.md.sig | docker exec -i cryptopro scripts/verify
```

![verify](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/verify.gif)

## Получение исходного файла из sig-файла

Возьмем файл из примера выше:

```
cat certificates/README.md.sig | docker exec -i cryptopro sh -c 'tmp=`mktemp`; cat - > "$tmp"; cryptcp -verify -nochain "$tmp" "$tmp.origin" > /dev/null 2>&1; cat "$tmp.origin"; rm -f "$tmp" "$tmp.origin"'
```

То же самое, но с использованием скрипта:

```
cat certificates/README.md.sig | docker exec -i cryptopro scripts/unsign
```

![unsign](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/unsign.gif)

## Использование контейнера на удаленной машине

В примерах выше команды выглядят так: `cat ... | docker ...` или `curl ... | docker ...`, то есть контейнер запущен на локальной машине. Если же докер контейнер запущен на удаленной машине, то команды нужно отправлять через ssh клиент. Например, команда подписания:

```
cat README.md | ssh -q user@host 'docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678'
```

Опция `-q` отключает приветствие из файла `/etc/banner` (хотя оно все равно пишется в `stderr`). А `/etc/motd` при выполнении команды по ssh не выводится.

В качестве эксперимента можно отправить по ssh на свою же машину так:

```
# копируем публичный ключ на "удаленную машину" (на самом деле - localhost)
ssh-copy-id $(whoami)@localhost
# пробуем подписать
cat README.md | ssh -q $(whoami)@localhost 'docker exec -i cryptopro /scripts/sign dd45247ab9db600dca42cc36c1141262fa60e3fe 12345678'
```

# Работа с контейнером через HTTP REST-сервер<a name="http"></a>

Установка сертификатов осуществляется через командую строку. Все остальные действия доступны по HTTP.

* `/healthcheck` - проверка работоспособности (`GET`)
* `/certificates` - все установленные сертификаты пользователя (`GET`)
* `/sign` - подписание документов (`POST`)
* `/cosign` - добавление еще одной подписи к документу (`POST`)
* `/verify` - проверка подписанного документа (`POST`)
* `/unsign` - получение исходного файла без подписей (`POST`)

![rest](https://raw.githubusercontent.com/dbfun/cryptopro/master/assets/rest.gif)

## Формат данных

Для `POST` данные должны поступать в теле запроса в формате `x-www-form-urlencoded`.

Возвращаются данные в формате `JSON`.

## Обработка ошибок

Успешные действия возвращают код `200` и `"status": "ok"`.

Действия с ошибками возвращают `4xx` и `5xx` коды и `"status": "fail"`, в полях `errMsg` содержится описание ошибки, в `errCode` - ее код.

Например, обращение с неправильным методом

```sh
curl -sS -X POST --data-binary "bindata" http://localhost:8095/healthchecks
```

выведет такую ошибку:

```JSON
{"status":"fail","errMsg":"Method must be one of: GET","errCode":405}
```

## `/healthcheck` - проверка работоспособности

Используется, чтобы убедиться в работоспособности сервиса. Например, так: `docker ps -f name=cryptopro` или `curl http://localhost:8095/healthcheck`.

## `/certificates` - все установленные сертификаты пользователя

```
curl -sS http://localhost:8095/certificates
```

Если сертификатов нет:

```JSON
{"status":"fail","errMsg":"No certificates in store 'My'","errCode":404}
```

Если сертификаты есть:

```JSON
{
  "certificates": [
    {
      "privateKey": {
        "providerName": "Crypto-Pro GOST R 34.10-2012 KC1 CSP",
        "uniqueContainerName": "HDIMAGE\\\\eb5f6857.000\\D160",
        "containerName": "eb5f6857-a08a-4510-8a96-df2f75b6d65a"
      },
      "algorithm": {
        "name": "ГОСТ Р 34.10-2012",
        "val": "1.2.643.7.1.1.1.1"
      },
      "valid": {
        "to": "24.05.2019 08:13:16",
        "from": "24.02.2019 08:03:16"
      },
      "issuer": {
        "E": "support@cryptopro.ru",
        "C": "RU",
        "L": "Moscow",
        "O": "CRYPTO-PRO LLC",
        "CN": "CRYPTO-PRO Test Center 2",
        "raw": "CN=CRYPTO-PRO Test Center 2, O=CRYPTO-PRO LLC, L=Moscow, C=RU, E=support@cryptopro.ru"
      },
      "subject": {
        "C": "RU",
        "L": "Test",
        "O": "Test",
        "OU": "Test",
        "CN": "Test",
        "E": "test@test.ru",
        "raw": "E=test@test.ru, CN=Test, OU=Test, O=Test, L=Test, S=Test, C=RU"
      },
      "thumbprint": "982AA9E713A2F99B10DAA07DCDC94A4BC32A1027",
      "serialNumber": "120032C3567443029CC358FCDF00000032C356",
      "hasPrivateKey": true
    }
  ],
  "status": "ok"
}
```

## `/sign` - подписание документов

Для выбора сертификата для подписания нужно указать один критерии поиска `find_type`:

* `sha1` - по SHA1 сертификата
* `subject` - по `subject` подписанта

В `query` - параметры поиска, в `pin` - пин-код (если он установлен):

```sh
CERT_QUERY="find_type=sha1&query=82028260efc03eedc88dcb61c0f6a02e788e26e2&pin=12345678"
curl -sS -X POST --data-binary @- "http://localhost:8095/sign?$CERT_QUERY" < README.md
```

Вернется `JSON` - документ, в `signedContent` будет содержаться подписанный файл.

## `/cosign` - добавление еще одной подписи к документу

Не реализовано, столкнулся с проблемой: не получается заставить работать функцию `CPSignedData::CoSignCades()`.

## `/verify` - проверка подписанного документа

Подпишем файл и проверим его:

```sh
# подпишем файл
CERT_QUERY="find_type=sha1&query=82028260efc03eedc88dcb61c0f6a02e788e26e2&pin=12345678"
curl -sS -X POST --data-binary @- "http://localhost:8095/sign?$CERT_QUERY" < README.md > /tmp/file.json
# извлечем подписанный файл из "signedContent"
jq ".signedContent" --raw-output /tmp/file.json > /tmp/file.sig
# проверим подписанный файл
curl -sS -X POST --data-binary @- "http://localhost:8095/verify" < /tmp/file.sig
```

Если файл прошел проверку, вернется список подписантов `signers`.


## `/unsign` - получение исходного файла без подписей

Исходный файл вернется в поле `content`.

```sh
# получим из подписанного файла /tmp/file.sig оригинальный файл, он будет в "content" файла /tmp/unsig.json
curl -sS -X POST --data-binary @- "http://localhost:8095/unsign" < /tmp/file.sig > /tmp/unsig.json
# выведем на экран первые несколько строк
jq ".content" --raw-output /tmp/unsig.json | base64 -d | head
```


## Проблемы

Если pin-код не подходит, то в терминал выводится:

```
Wrong pin, 2 tries left.

CryptoPro CSP: Type password for container "eb5f6857-a08a-4510-8a96-df2f75b6d65a"
Password:
```

И подписание останавливается.


# Ссылки

* [Страница расширения для PHP](http://cpdn.cryptopro.ru/default.asp?url=content/cades/phpcades.html)
* [Тестовый УЦ](http://testca2012.cryptopro.ru/ui/), его сертификаты: [корневой](http://testca2012.cryptopro.ru/cert/rootca.cer), [промежуточный](http://testca2012.cryptopro.ru/cert/subca.cer)


# Аналоги

Существует аналогичный пакет [CryptoProCSP](https://github.com/taigasys/CryptoProCSP), он классный, но:

* давно не обновлялся, используется версия `PHP5.6`
* для запуска пришлось подредактировать `Dockerfile`
