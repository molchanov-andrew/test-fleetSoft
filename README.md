<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

Yii 2 Basic Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


### Clone Repo 
клонуйте репозиторій у пусту папку
https://github.com/molchanov-andrew/test-fleetSoft/tree/master

### Install with Docker

    
Скачайте образи та запустіть контейнери

    docker-compose up -d

1. Підключаєтесь в консоль контейнеру yii2 командою
   docker exec -it  test-task-fleetsoft-php-1 bash та виконуєту команду
   composer install. НЕ З ВАШОЇ МАШИНИ А САМЕ З СЕРЕДИНИ КОНТЕЙНЕРУ!
2. Застосовуєте міграції в контейнері
   php yii migrate
3. Отримуємо перелік активних контейнерів (docker ps) та знаходимо ім"я MySQL контейнера (test-task-fleetsoft-mysql-1)
   Виконуємо команду docker exec -i <ім"я контейнера mysql> mysql -uuser -ppassword <ім"я БД> < <ім"я файлу з дампом>
   всі необхідні параметри беремо з docker-compose.yml файлу що в корні проекту. Також у корні лежить дамп.
4. Колекція postman для тестування REST знаходиться у корні проекту у файлі test_fleetSoft.postman_collection.json
Веб версія додатку доступна у браузері

    http://127.0.0.1:8000


Угода:

передбачається, що всі команди виконуватимуться з кореня проекту;
назви таблиць повинні бути в однині (якщо це можливо)
