# Я хочу оттестировать вообще всё!

Представим, что проект растет, команда ширится, и тут выясняется, что
вот тот разработчик, притащивший себе второй монитор из дома, пишет
строчки длиной в 500 символов. Разработчики-одномониторщики негодуют,
когда им приходится это править, а у вас в голове появляется мысль: это
же длина строки, ее можно высчитать, а расчеты - автоматизировать, так
почему бы не тестировать не только результат выполнения кода, но и
*сам код*, скорость его выполнения, эффективность кэширования? К
счастью, эта идея посещала уже многих, и в PHP есть большой набор
утилит, облегчающих жизнь команде разработчиков.

## PHP Code Sniffer

[вебсайт][php-cs] [packagist][php-cs-pkg]

Эта утилита занимается примерно той проблемой, что была описана выше:
она занимается поиском проблем форматирования кода. Некоторое время
назад, когда разработчики PHP замучились спорить, использовать табы или
пробелы для отступов в коде, была разработана базовая серия
[стандартов PSR][psr-list], касающихся форматирования кода и
автозагрузки классов (стандарты PSR разрабатываются и сейчас, но после
стандарта по docblock'ам, насколько понимаю, форматирование кода
затрагивать больше не будут). Именно следование этим стандартам и может
отслеживать Code Sniffer - длина строки не больше 80/120 символов,
неправильные отступы, недокументированные классы, методы и функции.

PHPCS, как и большинство других утилит, запускается из консоли:

```bash
vendor/bin/phpcs -s -p -d error_reporting=E_ALL -d display_errors=1 components
```

У PHPCS много ключей, поэтому (а еще потому, что других я не знаю) я
опишу только присутствующие в вызове:

* -s: Указывать названия конкретных сниффов (правил проверки)
* -p: Показывать процесс выполнения
* -d: Установить опцию `php.ini`
* components: последний аргумент указывает директорию/файл, в котором
нужно проводить проверку. В случае отсутствия PHPCS будет ожидать, что
код будет подаваться прямо на вход (stdin), т.е. вам будет предложено
ввести код самостоятельно (завершить ввод можно по Ctrl + D). Вывод
будет примерно следующим:

> ...
>
> FILE: /srv/http/src/playground/components/composer/ServerConfigGenerator.php
> --------------------------------------------------------------------------------
> FOUND 6 ERROR(S) AND 1 WARNING(S) AFFECTING 4 LINE(S)
> --------------------------------------------------------------------------------
>   2 | ERROR   | Missing file doc comment (PEAR.Commenting.FileComment.Missing)
>   8 | WARNING | Invalid version "0.1.0" in doc comment; consider "Release:
>     |         | <package_version>" instead
>     |         | (PEAR.Commenting.ClassComment.InvalidVersion)
>   8 | ERROR   | The @version tag is in the wrong order; the tag follows
>     |         | @license (PEAR.Commenting.ClassComment.WrongTagOrder)
>   9 | ERROR   | The @since tag is in the wrong order; the tag follows @see (if
>     |         | used) or @link (PEAR.Commenting.ClassComment.WrongTagOrder)
>  12 | ERROR   | Missing @category tag in class comment
>     |         | (PEAR.Commenting.ClassComment.MissingTag)
>  12 | ERROR   | Missing @license tag in class comment
>     |         | (PEAR.Commenting.ClassComment.MissingTag)
>  12 | ERROR   | Missing @link tag in class comment
>     |         | (PEAR.Commenting.ClassComment.MissingTag)
> --------------------------------------------------------------------------------
>
> ...

Упс, кажется, я не соблюдаю PEAR-стандарты форматирования PHPDoc.

## PHP Coding Standards Fixer

[вебсайт][php-cs-fixer] [packagist][php-cs-fixer-pkg]

[Fabien Potencier][fabpot], отец-основатель фреймворка Symfony, написал эту
утилиту, которая позволяет в автоматическом режиме приводить форматирование кода
к выбранному стандарту.

@todo пример

## PHP Mess Detector

[вебсайт][php-mess-detector] [packagist][phpmd-pkg]

## Lint / Parallel Lint

[вебсайт Parallel Lint][parallel-lint]
[Parallel Lint на packagist][parallel-lint-pkg]

## Athletic

[вебсайт][athletic] [packagist][athletic-pkg]

Athletic - это пакет для измерения производительности, работающий по
аналогии с PHPUnit: тесты производительности оформляются  в виде классов
с аннотациями, которые анализируются и выполняются при запуске. Athletic
позволит произвести сравнительный анализ алгоритмов и выбрать из них
наилучший, либо выделить критически важные участки кода и изолированно
отслеживать их производительность. Пожалуй, надо сразу сказать, что
Athletic имеет смысл применять для однократного сравнения
производительности запросов к БД, но не для измерения с целью выяснить
"устраивает время выполнения или не устраивает": на боевом серваке будет
другая БД с другими настройками (если не другой версии и вообще не
другого движка), которая наверняка будет вести себя иначе под нагрузкой.

В данный момент Athletic является довольно простой консольной утилитой
без большого количества настроек. Для использования вам потребуется
создать непосредственно анализируемый код, отнаследованный от
`Athletic\AthleticEvent` тестовый класс, в котором будут описаны
измерения, и натравить Athletic на директорию с этим классом.

```bash
vendor/bin/athletic -p tests/benchmarks -f DefaultFormatter -b vendor/bin/autoload.php
```

Ключ -p соответствует параметру path, начальной директории, которая
будет рекурсивно просканирована на наличие benchmark'ов - классов,
отнаследованных от `Athletic\AthleticEvent`; ключ -f позволяет задать
класс форматтера, который будет выводить данные; ключ -b соответствует
bootstrap-файлу, который будет исполнен перед прогоном тестов.

Подводя итог - Athletic является довольно узкоспециализированным
инструментом, который, тем не менее, наглядно покажет вам, каким
алгоритмом лучше пользоваться, и замедлился ли одиночный вызов
приложения.

## PHP Lines Of Code

[вебсайт][php-loc] [packagist][php-loc-pkg]

## Apache Benchmarks

[вебсайт][apache-benchmarks]

## Снова XDebug и XHProf

[вебсайт XDebug][xdebug] [вебсайт XHProf][xhprof]

## vfsStream

[вебсайт][vfs-stream] [packagist][vfs-stream-pkg]

vfsStream - это библиотека, позволяющая создавать виртуальную файловую
систему. Любое unit-тестирование в определенный момент утыкается во
внешние зависимости: существование файлов, доступность сервисов,
регистрация приложения по какому-то конкретному доменному имени. Само
добавление таких зависимостей идет наперекор принципу изолированности
тестов, и, зачастую, требует от теста подчищать за собой, чтобы следы
работы не мешали выполнению других тестов. vfsStream, как уже можно было
понять, использует так называемые врапперы PHP, чтобы перехватывать
вызовы к файлам и выдавать содержимое виртуальной файловой системы, если
стандартные функции вроде `fopen()` обращаются по виртуальному адресу.

Использование vfsStream слегка запутанное, и проще сразу начать с
документации. В общем виде использование выглядит вот так:

Пакеты-аналоги: [php-vfs][php-vfs]

## Другие полезные утилиты

Кроме вышеописанных утилит и пакетов есть еще ряд известного ПО для
тестирования, к которому я не прикоснулся никак, поэтому могу только
проаноносировать. Сюда входят

 * [Behat][behat] - BDD фреймворк, специализирующийся на максимальном
 приближении BDD-сценариев к реальным описаниям use-case'ов. Из минусов
 можно отметить то, что большую часть функционала придется дописывать.
 * [Atoum][atoum] - Фреймворк для модульного тестирования, аналог
 PHPUnit. Отличается отсутствием аннотаций и подходом к утверждениям
 (проверке данных).
 * [PHPSpec][phpspec] - BDD фреймворк, однако организация сценариев
 производится в виде методов класса.
 * [PHP Copy/Paste Detector][php-cpd] - тулза, позволяющая вовремя
 выявлять нерадивых разработчиков и выставлять их за дверь.
 * [PDepend][pdepend] - одна из самых полезных утилит из перечисленных.
 Эта утилита производит статитический анализ кода и умеет определять
 связанность классов, повышенную сложность методов (избыточность циклов,
 управляющих конструкций) и всякую мелкую полезность типа общего
 количества строк кода, количества переопределнных методов в классе и
 прочее.
 * [PHP Dead Code Detecor][php-dcd] - как понятно из названия, этот
 детектор позволит найти код, который больше не используется в проекте.

\* О части из вышеописанных утилит я узнал на сайте
[http://phpqatools.org/]() - если вы читаете это спустя года два,
не поленитесь заглянуть, наверняка появилось еще что-то.

## Использование самого приложения

Кроме всего прочего, часть анализа можно (и нужно) взвесить на само
приложение - например, тот самый процент "попадания в кэш", когда данные
берутся из кэша, или скорость выполнения тех или иных кучков кода. В
случае обнаружения тормозов в одном месте в первую очередь потребуется
не полная выкладка профилировщика, а время выполнения конкретных
запросов к БД - в этом случае проще всего профилировать (замерять время
выполнения) отдельных кусков кода. Например, в нашем Yii-приложении надо
получить наиболее активных пользователей - а для этого надо посчитать
количество их записей, реплаев, лайков и количество фоток с котиками на
стенке. Это будет довольно дорогая операция, время выполнения которой мы
хотели бы видеть. Тогда код будет выглядеть примерно следующим образом:

    class User extends CActiveRecord
    {
        public function findMostActive($amount = 5)
        {
            $profileToken = 'models.user.findMostActive';
            Yii::beginProfile($profileToken);
            // "тяжелый" код
            $query = $Yii::app()
                 ->db
                 ->createCommand()
                //...
            Yii::endProfile($profileToken);
        }
    }

Самое замечательное в этом коде то, что пока профилирование не включено
- он не несет практически никакой нагрузки. Кроме того, на самом деле не
существует другого способа *реальной* профилировки - подобный подход
позволит профилировать время выполнения кода на боевом сервере при
реальной нагрузке, которую довольно проблематично воссоздать
искусственно.

  [xdebug]: http://xdebug.org/
  [xhprof]: https://github.com/phacility/xhprof
  [apache-benchmarks]: http://httpd.apache.org/docs/2.2/programs/ab.html
  [fabpot]: https://github.com/fabpot
  [php-cs]: http://www.squizlabs.com/php-codesniffer
  [php-cs-pkg]:https://packagist.org/packages/squizlabs/php_codesniffer
  [php-cs-fixer]: https://github.com/fabpot/PHP-CS-Fixer
  [php-cs-fixer-pkg]: https://packagist.org/packages/fabpot/php-cs-fixer
  [php-loc]: https://github.com/sebastianbergmann/phploc
  [php-loc-pkg]: https://packagist.org/packages/phploc/phploc
  [parallel-lint]: https://github.com/JakubOnderka/PHP-Parallel-Lint
  [parallel-lint-pkg]: https://packagist.org/packages/jakub-onderka/php-parallel-lint
  [athletic]: https://github.com/polyfractal/athletic
  [athletic-pkg]: https://packagist.org/packages/athletic/athletic
  [php-md]: http://phpmd.org/
  [php-md-pkg]: https://packagist.org/packages/phpmd/phpmd
  [behat]: http://behat.org
  [behat-pkg]: https://packagist.org/packages/behat/behat
  [atoum]: https://github.com/atoum/atoum
  [atoum-pkg]: https://packagist.org/packages/atoum/atoum
  [phpspec]: http://phpspec.net
  [phpspec-pkg]: https://packagist.org/packages/phpspec/phpspec
  [php-cpd]: https://github.com/sebastianbergmann/phpcpd
  [php-cpd-pkg]:https://packagist.org/packages/sebastian/phpcpd
  [php-dcd]: http://github.com/sebastianbergmann/phpdcd
  [php-dcd-pkg]:https://packagist.org/packages/sebastian/phpdcd
  [pdepend]: http://pdepend.org/
  [pdepend-pkg]: https://packagist.org/packages/pdepend/pdepend
  [vfs-stream]: https://github.com/mikey179/vfsStream
  [vfs-stream-pkg]: https://packagist.org/packages/mikey179/vfsStream
  [php-vfs]: http://thornag.github.io/php-vfs/
  [php-vfs-pkg]: https://packagist.org/packages/php-vfs/php-vfs