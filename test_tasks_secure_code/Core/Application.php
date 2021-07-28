<?php

namespace test_tasks_secure_code\Core;

class Application {

    public static function app_run(){
        router::spl_autoload_register('MainController');
    }

}

Application::app_run();

?>