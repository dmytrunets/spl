# Symfony EvercodeLab Edition

Рады представить вам **Symfony EvercodeLab Edition**, нашу небольшую доработку
для [Symfony Standard Edition][1], призванную ускорить процесс начала разработки
нового приложения.

Кроме стандартных компонентов Symfony2 в данный вариант включены некоторые 
дополнительные пакеты, которые мы обычно используем в своей работе:

* [GedmoDoctrineExtensions][2] — расширение возможностей Doctrine. Добавляет
такие полезные штуки как Timestampable (добавляет время создания, обновления),
Tree (добавляет возможность работы с деревьями).
* [SonataAdminBundle][3] — административный интерфейс для Symfony
* [KnpMenuBundle][4] — генерация меню
* [KnpPaginatorBundle][5] — создание пагинации
* [FosUserBundle][6] — управление пользователями
* [DoctrineFixturesBundle][7] — управление фикстурами
* [DoctrineMigrationsBudnle][8] — управление миграциями
* [AvalancheImagineBundle][9] — бандл для работы с изображениями
* [EvercodePageBundle][10] — наш небольшой бандл для создания и отображения 
простых страниц

## Установка

Для установки можно воспользоваться несколькими возможностями. Первая из них
воспользоваться composer:


    composer.phar create-project evercode/symfony-skeleton path/

Вторая возможность это воспользоваться клонированием с github:

    git clone git@github.com:EvercodeLab/symfony-skeleton.git path/

В обоих случаях `path` это путь, куда будет устанавливаться новый проект.

Следующие действия идентичны для обоих обоих вариантов, нам необходимо запустить
установочный скрипт, который сделает за нас большую часть работы:

    bin/set_project

Скрипт выполняет следующие действия:

* Создает необходимые директории (`app/cache` и `app/logs`) и выставляет 
необходимые права на них (в этом месте установочный скрипт может запросить у 
вас пароль для `sudo`, так как установка прав на папки идет от имени 
администратора)
* Копирует и открывает на редактирование файл с настройками. При этом файл 
открывается в программе, которая установлена как редактор в `$EDITOR` переменной
операционной системы.
* Скачивается, если необходимо, composer, после чего происходит установка
необходимых пакетов.
* Создается база данных и прогоняются необходимые действия для придание ей
рабочего вида (миграции и загрузка фикстур), в базовом варианте — создаются 
таблицы от FosUserBundle и загружается тестовый пользователь.

Всё, теперь наш проект готов к работе!

## Запуск проекта с использованием встроенного сервера php

Для этого просто запускаем команду:
    
    bin/run_project

после этого откроется браузер на главной странице проекта, ну и так же 
запустится сервер, на котором всё это дело крутится.

## Тестирование

Для тестирования мы используем [Behat + Mink][11]. Тут опять же все просто. 
Тесты располагаются в папочке `features`, и запускаются командой `bin/behat`. 
Вот в общем то и всё. Для лучшего понимания работы с Behat лучше всего обратится
к документации на официальном сайте.

[1]: https://github.com/symfony/symfony-standard
[2]: https://github.com/l3pp4rd/DoctrineExtensions
[3]: https://github.com/sonata-project/SonataAdminBundle
[4]: https://github.com/KnpLabs/KnpMenuBundle
[5]: https://github.com/KnpLabs/KnpPaginatorBundle
[6]: https://github.com/FriendsOfSymfony/FOSUserBundle
[7]: https://github.com/doctrine/DoctrineFixturesBundle
[8]: https://github.com/doctrine/DoctrineMigrationsBundle
[9]: https://github.com/avalanche123/AvalancheImagineBundle
[10]: https://github.com/EvercodeLab/EvercodePageBundle
[11]: http://behat.org/
