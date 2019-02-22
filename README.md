# КриптоПро 4.0 в докер контейнере

Возможности:

* PHP7 с установленным расширением `libphpcades` (`CPStore`, `CPSigner`, `CPSignedData`)
* использование входящих в КриптоПро инструментов: `certmgr`, `cpverify`, `cryptcp`, `csptest`, `csptestf`, `der2xer`, `inittst`, `wipefile`, `cpconfig`
* удобная установка сертификатов:
  * корневых
  * пользователя

# Структура проекта

```
├── assets        - материалы для README.md
├── certificates  - тестовые сертификаты
├── dist          - пакеты КриптоПро (необходимо скачать с официального сайта)
├── Dockerfile    - файл сборки образа
├── README.md     - этот файл
└── scripts       - вспомогательные скрипты
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

# Работа с контейнером

## Запуск

Запустим контейнер под именем `cryptopro`, к которому будем обращаться в примерах:

```
docker run -it --rm -p 8095:80 --name cryptopro required/cryptopro
```

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
├── bundle-custom-name.zip        - сертификат + закрытый ключ, название контейнера закрытого ключа содержит кириллицу
├── bundle-no-pin.zip             - сертификат + закрытый ключ БЕЗ пин-кода
├── bundle-pin.zip                - сертификат + закрытый ключ с пин-кодом
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

# сертификат + закрытый ключ, название контейнера закрытого ключа содержит кириллицу
cat certificates/bundle-custom-name.zip | docker exec -i cryptopro /scripts/my
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

# Ссылки

* [Страница расширения для PHP](http://cpdn.cryptopro.ru/default.asp?url=content/cades/phpcades.html)
* [Тестовый УЦ](http://testca2012.cryptopro.ru/ui/), его сертификаты: [корневой](http://testca2012.cryptopro.ru/cert/rootca.cer), [промежуточный](http://testca2012.cryptopro.ru/cert/subca.cer)


# Аналоги

Существует аналогичный пакет [CryptoProCSP](https://github.com/taigasys/CryptoProCSP), он классный, но:

* давно не обновлялся, используется версия `PHP5.6`
* для запуска пришлось подредактировать `Dockerfile`
