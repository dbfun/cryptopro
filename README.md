# КриптоПро 4.0 в докер контейнере

Возможности:

* PHP7 с установленным расширением `libphpcades` (`CPStore`, `CPSigner`, `CPSignedData`)
* удобная установка сертификатов:
  * корневых
  * пользователя

# Структура проекта

```
├── certificates  - тестовые сертификаты
├── dist          - пакеты КриптоПро (необходимо скачать с официального сайта)
├── Dockerfile    - файл сборки образа
├── README.md     - этот файл
└── scripts       - скрипты
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

В `Dockerfile` содержатся названия пакетов, например `lsb-cprocsp-devel-4.0.9921-5.noarch.rpm`, которые могут заменить новой версией. Следует поправить названия пакетов.

# Работа с контейнером

## Запуск

Запустим контейнер под именем `cryptopro`:

```
docker run -it --rm -p 8095:80 --name cryptopro required/cryptopro
```

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

Первый найденный файл в корне архива будет воспринят как сертификат, а первый найденный каталог - как связка файлов закрытого ключа.

В каталоге `certificates/` содержатся различные комбинации тестового сертификата и закрытого ключа, с PIN кодом и без:

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

# Ссылки

* [Страница расширения для PHP](http://cpdn.cryptopro.ru/default.asp?url=content/cades/phpcades.html)
* [Тестовый УЦ](http://testca2012.cryptopro.ru/ui/), его сертификаты: [корневой](http://testca2012.cryptopro.ru/cert/rootca.cer), [промежуточный](http://testca2012.cryptopro.ru/cert/subca.cer)


# Аналоги

Существует аналогичный пакет [CryptoProCSP](https://github.com/taigasys/CryptoProCSP), он классный, но:

* давно не обновлялся, используется версия `PHP5.6`
* для запуска пришлось подредактировать `Dockerfile`
