# Базовый образ с КриптоПро
FROM debian:stretch-slim as cryptopro-generic

# Устанавливаем timezone
ENV TZ="Europe/Moscow" \
    docker="1"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone

# необходимо скачать со страницы https://www.cryptopro.ru/products/csp/downloads
# `КриптоПро CSP 4.0 для Linux (x64, deb)` и скопировать `linux-amd64_deb.tgz` в каталог `dist`

ADD dist /tmp/src
RUN cd /tmp/src && \
    tar -xf linux-amd64_deb.tgz && \
    linux-amd64_deb/install.sh && \
    # делаем симлинки
    cd /bin && \
    ln -s /opt/cprocsp/bin/amd64/certmgr && \
    ln -s /opt/cprocsp/bin/amd64/cpverify && \
    ln -s /opt/cprocsp/bin/amd64/cryptcp && \
    ln -s /opt/cprocsp/bin/amd64/csptest && \
    ln -s /opt/cprocsp/bin/amd64/csptestf && \
    ln -s /opt/cprocsp/bin/amd64/der2xer && \
    ln -s /opt/cprocsp/bin/amd64/inittst && \
    ln -s /opt/cprocsp/bin/amd64/wipefile && \
    ln -s /opt/cprocsp/sbin/amd64/cpconfig && \
    # прибираемся
    rm -rf /tmp/src

# Образ с PHP cli и скриптами
FROM cryptopro-generic
ADD dist /tmp/src

RUN apt-get update && \
    apt-get install -y --no-install-recommends expect alien php7.0-cli php7.0-dev libboost-dev unzip g++ curl && \
    cd /tmp/src && \
    tar -xf cades_linux_amd64.tar.gz && \
    alien -kci lsb-cprocsp-devel-4.0.9921-5.noarch.rpm && \
    alien -kci cprocsp-pki-2.0.0-amd64-phpcades.rpm && \
    alien -kci cprocsp-pki-2.0.0-amd64-cades.rpm && \
    # меняем Makefile.unix
    PHP_BUILD=`php -i | grep 'PHP Extension => ' | awk '{print $4}'` && \
    EXT_DIR=`php -i | grep 'extension_dir => ' | awk '{print $3}'` && \
    # /usr/include/php/20151012/
    sed -i "s#PHPDIR=/php#PHPDIR=/usr/include/php/$PHP_BUILD#g" /opt/cprocsp/src/phpcades/Makefile.unix && \
    # копируем недостающую библиотеку
    ln -s /opt/cprocsp/lib/amd64/libcppcades.so.2 /opt/cprocsp/lib/amd64/libcppcades.so && \
    # начинаем сборку
    cd /opt/cprocsp/src/phpcades && \
    # применяем патч
    unzip /tmp/src/php7_support.patch.zip && \
    patch < php7_support.patch && \
    # собираем
    eval `/opt/cprocsp/src/doxygen/CSP/../setenv.sh --64`; make -f Makefile.unix && \
    # делаем симлинк собранной библиотеки
    mv libphpcades.so "$EXT_DIR" && \
    # включаем расширение
    echo "extension=libphpcades.so" > /etc/php/7.0/cli/conf.d/20-libphpcades.ini && \
    # проверяем наличие класса CPStore
    php -r "var_dump(class_exists('CPStore'));" | grep -q 'bool(true)' && \
    # прибираемся
    cd / && \
    apt-get purge -y php7.0-dev cprocsp-pki-phpcades lsb-cprocsp-devel g++ && \
    apt-get autoremove -y && \
    rm -rf /opt/cprocsp/src/phpcades && \
    rm -rf /tmp/src && \
    rm -rf /var/lib/apt/lists/

ADD scripts /scripts
ADD www /www

# composer
# RUN apt-get update && \
#     apt-get install -y --no-install-recommends curl ca-certificates && \
#     # composer
#     curl "https://getcomposer.org/installer" > composer-setup.php && \
#     php composer-setup.php && \
#     rm -f composer-setup.php && \
#     chmod +x composer.phar && \
#     mv composer.phar /bin/composer && \
#     # прибираемся
#     cd / && \
#     apt-get purge -y curl ca-certificates && \

HEALTHCHECK --interval=60s --timeout=5s CMD ["curl", "-m5", "-f", "http://localhost:8080/healthcheck"]

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/www/public/"]
