# Развертывание стека в своем workspace

**NB**: на самом деле эта секция содержит огромное количество
**личных предпочтений**, и должна рассматриваться только как **пример**
развертывания стека. Кроме того, никто не запрещает вам использовать travis или
еще какие-то более интересные решения, чем указаны здесь.

Начнем с самого простого - установки php, git и других пакетов,
распространяющихся через обычные репозитории пакетов ОС.

```bash
aptitude install php5 php5-mcrypt php-mysql php5-xdebug php5-xhprof git curl mysql-server vagrant docker.io
```

После этого установим Composer в `/usr/local/bin`, после чего можно
будет выполнять `composer команда` из любой директории

```bash
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

По умолчанию в Composer уровень global соответствует текущему
пользователю: кэш и глобальные исполняемые файлы пишутся в домащний
каталог к текущему пользователю. Мне привычней думать о system-wide
установке, поэтому я с помощью пары трюков создаю общие кэш и хранилище
исполняемых файлов для всех пользователей группы `webdev`. Сначала надо
создать общую директорию:

```bash
sudo mkdir /usr/local/composer
sudo chmod 775 /usr/local/composer
sudo chmod g+s /usr/local/composer # все создаваемые файлы будут так же
                                   # иметь группу webdev
sudo chgrp webdev /usr/local/composer
```

Затем отредактировать файл `/etc/environment`, чтобы установить
переменные среды, которые сообщат Composer об использовании именно этой
папки:

```bash
# Добавляем в конец PATH `:/usr/local/composer/bin`
# НИЧЕГО НЕ УДАЛЯЕМ ИЗ ИСХОДНОГО PATH. Если вы напутаете с синтаксисом,
# вас ждет веселье до тех пор, пока вы не отредактируете /etc/environment
# еще раз.
PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/usr/local/composer/vendor/bin"

# Composer initialization
COMPOSER_HOME=/usr/local/composer # скажет Composer, где теперь должны сохраняться глобальные файлы
```

После этого любой зашедший в систему пользователь в группе webdev будет
пользоваться общим кэшем и устанавливать пакеты для всех участников
webdev. При желании это можно вынести в файл `~/.bashrc`, что задаст
такие настройки только для пользователя-владельца этого `.bashrc`.

После этого можно устанавливать пакеты глобально, и они будут доступны как
обычные консольные команды для любого пользователя:

```bash
fike@v-lubuntu:~$ composer global require jakub-onderka/php-parallel-lint:*
Changed current directory to /usr/local/composer
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)
  - Installing jakub-onderka/php-parallel-lint (v0.7.1)
    Downloading: 100%

jakub-onderka/php-parallel-lint suggests installing jakub-onderka/php-console-highlighter (Highlight syntax in code snippet)
Writing lock file
Generating autoload files
fike@v-lubuntu:~$ parallel-lint /srv/http/src/playground/ --exclude vendor
PHP 5.5.9 | 10 parallel jobs
............................................................   60/2111 (2 %)
............................................................  120/2111 (5 %)
-----------------------------------------------------------------------------
............................................................ 2100/2111 (99 %)
...........

Checked 2111 files in 18.7 second, no syntax error found
```

Теперь можно установить все те вышеописанные утилиты как глобальные пакеты.
Версии пакетов можно не указывать (\*), потому что фактических зависимостей в
этом случае нет - использоваться будут только исполняемые файлы:

```bash
composer global require codeception/codeception:* phpmd/phpmd:* \
squizlabs/php_codesniffer:* athletic/athletic:* behat/behat:* \
fabpot/php-cs-fixer:* sebastian/phpcpd:* sebastian/phpdcd:* \
pdepend/pdepend:* phploc/phploc:* halleck45/phpmetrics:* \
jakub-onderka/php-parallel-lint:* phpspec/phpspec:*
```

Теперь вместо `vendor/bin/codecept` можно писать просто `codecept` (при условии,
что в пакете не используется сильно устаревшая версия).

Далее нужно установить Selenium. Selenium доступен для скачивания на
[официальном вебсайте][selenium-hq]:

```bash
sudo mkdir -p /usr/local/selenium/drivers
sudo wget http://selenium-release.storage.googleapis.com/2.43/selenium-server-standalone-2.43.1.jar \
-O /usr/local/selenium/selenium-server-2.43.1.jar
sudo aptitude install openjdk-7-jdk # для запуска selenium потребуется java @todo verify

# устанавливаем дополнительные драйвера
sudo wget http://chromedriver.storage.googleapis.com/2.10/chromedriver_linux64.zip \
-O /usr/local/selenium/drivers/chromedriver.zip
sudo unzip /usr/local/selenium/drivers/chromedriver.zip -d /usr/local/selenium/drivers
sudo rm /usr/local/selenium/drivers/chromedriver.zip

#https://github.com/downloads/operasoftware/operadriver/operadriver-v1.1.zip @todo
#http://selenium-release.storage.googleapis.com/2.43/IEDriverServer_x64_2.43.0.zip @todo

sudo apt-get install phantomjs
```



@todo phpci

## PHPCI

PHPCI - слегка корявый, но вполне рабочий CI-сервер, и сейчас мы его установим в
папку `/srv/ci/php`.

Для начала стоит создать соответствующего пользователя и добавить его в группу
webdev для общего доступа к рабочим файлам Composer:

```bash
sudo adduser --system --no-create-home --disabled-login --disabled-password \
--shell=/bin/bash --home /srv/ci/php ci webdev
```
**NB: свежесозданному пользователю будет предоставлен доступ к bash. На
открытом для внешнего мира сервере рекомендуется использовать chroot или
аналог.**

Далее создается файловая структура:

```bash
sudo mkdir -p /srv/ci
sudo chown ci:webdev /srv/ci
sudo chmod g+ws /srv/ci
```

```bash
sudo -u ci git clone https://github.com/Block8/PHPCI.git /srv/ci/php
sudo -u ci composer install -d /srv/ci/php
sudo -u ci composer update -d /srv/ci/php
```

```bash
sudo -u ci composer -d /srv/ci/php require \
block8/php-docblock-checker:* phpmd/phpmd:* sebastian/phpcpd:* \
squizlabs/php_codesniffer:* phpspec/phpspec:* fabpot/php-cs-fixer:* \
phploc/phploc:* atoum/atoum:* jakub-onderka/php-parallel-lint:* behat/behat:* \
robmorgan/phinx:*
```

  [selenium-hq]: http://www.seleniumhq.org/download/