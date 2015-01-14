# Continuous Integration

Теперь, когда мы обложились тестами со всех сторон, появляется новая проблема:
нехватка времени и общее неудобство работы. Прогонять все утилиты каждый раз -
довольно неприятное занятие, потому что этот процесс слишком долгий и
ресурсоемкий, чтобы выполнять его на каждое сохранение файла. Кроме того, чтение
многостраничный вывод в терминале и нахождение какого-то конкретного места в нем
является очень трудным делом (не говоря уж об общем анализе - определить
количество отрапортавших об ошибках утилит и выделение критических ошибок в их
отчетах практически невозможно), поэтому просто написание скрипта запуска утилит
и его периодический запуск не решит проблему полностью. При этом нерегулярное
использование анализаторов и инструментов тестирования только увеличивает
технический долг, откладывая рефакторинг и исправление ошибок в долгий ящик -
если мы решили тестировать приложение, то необходимо выполнять тестирование как
минимум перед завершением/по итогу **каждой** итерации разработки. Кроме этого
хотелось бы разбить инструментарий по группам - если форматирование нам надо
анализировать только один раз перед финишем итерации (т.к. форматирование не
вносит изменения в ход выполнения кода, его исправление можно спокойно
откладывать на любой промежуток времени), то тесты необходимо прогонять сразу
после написания кода.

Здесь на помощь приходят сервисы и приложения **непрерывной интеграции**.
Непрерывная интеграция - это процесс, в котором все рутинные задачи разработки
автоматизируются, и разработчик уже имеет дело не непосредственно с утилитами,
а с некоторым сервисом или приложением, которое на вход так или иначе получает
исходный код, а на выходе выдает отчеты об анализе этого кода и выполненных
операциях. Непрерывную интеграцию сложно описать в широком смысле - она может
включать в себя что угодно (тестирование кода, анализ кода, сборка приложения,
распространение приложения, анализ скриншотов приложения, да хоть обновление
твиттера о новом релизе приложения), в данной статье нас будет интересовать
только та часть, которая затрагивает анализ и тестирование кода.

В общем представлении этот процесс будет выглядеть так: абстрактный сервис
получает исходный код, создает локальную копию, после чего запускает на этой
копии все описанные в конфигурации конкретного проекта шаги: запуск тех или иных
утилит, сборка проекта и т.п. После успешного завершения всех шагов или после
провала одного из шагов сервис подводит итог сборки проекта: сборка прошла
успешно, проблем нет, или при сборке проекта произошли такие-то ошибки: (выводит
отчет).