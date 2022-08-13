<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Application;
/**
 * Архитектурно следует добавить следующие вещи:
 * 1) отсутствуют настройки проекта (параметры и секреты)
 * в $_ENV храним чувствительные данные: dsn к DB, Url'ы к используемым Api-шкам и токены авторизации, is_debug_mode
 * дополнителные настройки: log_level, is_display_errors и прочее
 * 2) отсутствует контейнер с зависимостями: Logger, EntitiesRepositories, ExternalApiClients, Services
 * 3) добавить в корень папку migrations с файлами вида *.sql
 * 4) добавить автоматизацию в makefile или composer.json. Запуск линтера, Unit-тестов, sql-миграций и прочее
 * 5) написать обертку на Response, в дальнейшем будет удобнее логировать, считать метрики.
 * 6) добавить мидлвари: ErrorMiddleware, AuthMiddleware
 * 7) добавить ShutdownHandler для метода register_shutdown_function();
 * 8) перейти на более свежую версию PHP
 * 9) отсутствует в корне папочка tests для Unit-тестов и прчих тестов
 * 10) добавить документацию API, к примеру с помощью Swagger
 */

$app = new Application();
$app->run();
