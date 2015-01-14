# PHPUnit

XDebug хорош в том случае, когда надо посмотреть

PHPUnit - одна из первых реализаций фреймворка для unit-тестирования в
PHP. Unit-тестирование (или модульное тестирование) подразумевает под
собой довольно простые операции подтверждения работоспосбности отдельных
компонентов приложения: например, что после вызова функции добавления
нового комментария этот комментарий появляется в базе данных, или что
функция автоформатирования возвращает желаемый результат. Несмотря на
простоту операций, PHPUnit предоставляет на выбор огромное количество
различных проверок, причем не только точного соответствия результата
ожидаемому, но и проверки поиска, соответствия регулярному выражению,
проверки вывода (т.е. проверяется именно то, что пишется в основной
вывод и при обычном исполнении выводится на веб-страницу) и даже
проверка типа, кода и сообщения выбрасываемого исключения.

PHPUnit не требует от пользователя строго фиксированной файловой
структуры, но обычно тестирование в проекте с поддержкой PHPUnit
организуется так: в корне проекта создается папка tests, содержимое
которой повторяет структуру проекта; тесты же офрмляются по названию
класса (или файла, при соблюдении PSR это одно и то же), который они
тестируют, с суффиксом `Test`. Пример:

    package
     ├─cli
     │  ╰─ArgParser.php
     ├─web
     │  ╰─FrontController.php
     ├─components
     │  ├─Parser.php
     │  ╰─Formatter.php
     ╰─tests
        ├─cli
        │  └─ArgParserTest.php
        ╰─components
           ├─ParserTest.php
           ╰─FormatterTest.php

*(Отдельно замечу, что вовсе необязательно иметь тест на каждый класс)*

Каждый тестовый файл должен содержать класс, наследующий класс
`PHPUnit_Framework_TestCase`. Этот класс - это *сборник* тестов
(тестовый кейс), касающихся отдельного (тестируемого) класса (это
довольно похоже на контроллеры в MVC: реальными контроллерами являются
именно *действия*, а класс, в котором они лежат, выполняет роль
логического объединения). PHPUnit проанализирует этот класс и запишет
все методы, начинающиеся с `test` или имеющие аннотацию `@test` как
тесты и выполнит их в следующей фазе. Непосредственно тест формируется
из *утверждений* - вызовов методов с префиксом `assert`, которые и
проверяют соответствие утверждения. Если все утверждения теста
выполняются - этот тест успешно пройден компонентом, как только любое
утверждение проваливается - тест помечается как "проваленный" и тут же
прекращает свое выполнение (т.е. последующие утверждения выполнены не
будут).
Проще это будет пояснить на примере:

```php
/**
 * Предположим, что тестируется класс-форматтер, который должен
 * переводить Markdown в HTML
 */
public function testMarkdownFormatting()
{
    $markdown = '**woohoo**';
    $formatter = new MarkdownFormatter;
    $html = $formatter->format($markdown);
    // Первое утверждение: тест утверждает, что форматтер вернет хотя бы
    // что-нибудь
    $this->assertNotEmpty($html);
    // Второе утверждение: тест утверждает, что форматтер вернет именно
    // такую строку
    $this->assertSame($html, '<b>woohoo</b>');
    // Третье утверждение: тест утверждает, что при декодировании будет
    // получена исходная строка
    $this->assertSame($formatter->decode($html), $markdown);
}
```

Собственно, само тестирование строится из вот таких методов - мы берем
интерфейс того компонента, в котором не уверены, и пишем тест для каждой
точки входа этого интерфейса, проверяя с помощью утверждений, что
компонент на выходе дает именно то, что от него ожидалось: валидаторы
возвращают true для правильных значений и false для неправильных,
форматтеры не пропускают токены, класс для подчистки устаревшего
файлового кэша и вправду чистит директории. Формально тест может
тестировать что угодно, не обязательно класс, и не обязательно
низкоуровневый компонент, однако это считается плохой практикой. Чем
больше вбирает в себя тест, тем хуже у него с изоляцией (т.е. выполнение
теста зависит от многих других компонентов, что в конечном счете
упирается в убитое время для настройки теста); тестировать же функции
вместо классов абсолютно нормально, разве что это потребует собственного
механизма загрузки этих функций.

@todo запуск

Конечно, это далеко не весь функционал PHPUnit.

@todo группировка
@todo дата-провайдеры
@todo зависимости
@todo ожидаемые исключения

 При запуске PHPUnit проверит этот класс на
следующие методы:

* Все методы с префиксом `test` (например, `testFormatting()`) или
предваренные аннотацией `@test` будут записаны в список тестов. NB:
одиночный тест - это не класс, это именно метод класса, агрегирующего
произвольное количество тестов.
* Методы `setUp()` и `tearDown()`, в случае их наличия, будут
выполняться перед и после **каждого** теста соответственно.
* Статичные методы `setUpBeforeClass()` и `tearDownAfterClass`, в случае
их наличия, будут выполнены единственный раз до и после всего тестового
кейса (перед созданием тестового класса и после выполнения последнего
метода)
* Также PHPUnit соберет информацию о методах, указанных в аннотациях
`@dataProvider`, `@depends` и других (об этом позже).

Таким образом, следующий класс:

```php
<?php

class FormatterTest extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		// ...
	}
	public static function tearDownAfterClass()
	{
	    // ...
	}
	public function setUp()
	{
	    // ...
	}
	public function tearDown()
	{
	    // ...
	}
	public function testA()
	{
	    // ...
	}
	/**
	 * @test
	 */
	public function dummy()
	{
	    // ...
	}
}
```

Будет иметь такой порядок выполнения методов:

1. setUpBeforeClass()
2. setUp()
3. testA()
4. tearDown()
5. setUp()
6. dummy()
7. tearDown()
8. tearDownAfterClass()