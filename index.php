<?php

/* Точка входа приложения, настраивается в ручную для дополнительной безопасности, эта настройка по умолчанию 
 -> на уровень выше от корня проекта домена, например если приложение /test-taks/ находится по адресу: 
    http(s)://domain.com/test-tasks/index.php тогда доступ ко всем контроллерам и моделям приложения будет закрыт
    так как они будут расположены по адресу "test_tasks_secure_code/"
*/

require_once '../../test_tasks_secure_code/Core/Router.php';

?>