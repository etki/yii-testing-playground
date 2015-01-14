# XDebug

Первая *штука*, с которой полезно столкнуться в процессе отладки
приложения - это [XDebug][xdebug]. XDebug - это расширение PHP, которое
позволяет подключиться к выполняемому процессу и посмотреть и/или
отредактировать содержимое переменных - вместо того, чтобы кидать в
файлы проекта `var_dump($var);die;`, часть из которых там будут
бесславно забыты, можно просто расставить breakpoints в IDE - строчки
кода, на которых PHP передаст управление xdebug - и проследить весь путь
изменения переменной и найти конкретно то место, в котором в ней
оказывается что-то непредвиденное.

Пускай я, разработчик проекта **Notawordpress™**, решил сделать
отдельные страницы разработчиков и вывести их хорошие стороны. Для этого
я создал новую таблицу `developer_highlights`, в которую накидал фикстурой
хороших сторон разработчиков (в репозитории этому соответствует релиз
[0.1.1-failing][ytp-0.1.1]) и по-быстрому сделал вывод отдельных страниц
разработчиков, выводя для каждого три первых положительных характеристики.
Будучи уверенным, что всё прошло как маслу, я иду на собственную страницу и вижу
следующий вывод:

@todo

Вместо того, чтобы расставлять `var_dump`'ы, я могу внедриться в скрипт через
XDebug, и посмотреть, что же случилось. Для этого мне нужно

1. Включить подключение XDebug в php.ini @todo image
2. Запустить XDebug-сессию в запросе из браузера (проще всего это сделать с
помощью плагина [XDebug Helper][helper], он сделает все автоматом) @todo image
3. Включить обработку входящих XDebug-сессий на стороне IDE @todo image
4. Расставить брейкпоинты в нужных местах @todo image

После этого запрос из браузера запустит сессию XDebug, XDebug подключится к IDE,
и остановит выполнение скрипта на первом брейкпоинте. IDE же выведет
предоставленную XDebug информацию о текущих файле, переменных и всяком
остальном.

@todo image

`highlights` на самом деле является магическим свойством модели `Developer`, но
и это не помеха - XDebug позволяет выполнить произвольное выражение прямо в
скрипте (и, при желании, даже повлиять на его выполнение):

@todo image

Теперь можно убедиться в том, что из БД просто не пришло данных, а Twig был
достаточно любезен, чтобы не стрелять ошибками. Что ж, поправим это дело (релиз
[0.1.2-fix][ytp-0.1.2]):

@todo image

Помимо прочего еще важно сказать про watches / watched expressions - это такие
выражения, которые будут выполняться на каждом брейкпоинте. С помощью них легко
отслеживать изменения переменных, например, изменения в конфигурации или
построение дерева.

  [helper]: https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc
  [ytp-0.1.2]: https://github.com/etki/yii-testing-playground/releases/tag/0.1.2-fix