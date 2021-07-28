<?php

namespace test_tasks_secure_code\Core;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Logs\logs;
use test_tasks_secure_code\Controllers\account;
use test_tasks_secure_code\Core\Sessions\connect_sessions_db_mssql;

class MainController
{

  public static function start() { 
    
    error_reporting(0);

    header_remove('X-Powered-By');

    header("Expires:Sat, 1 Jan 2005 00:00:00 GMT");
    header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control:no-store, no-cache,  must-revalidate");
    header("Content-type:text/html; charset=utf-8");
    header("Pragma:no-cache");
    
    // include modules for Main page (app Tasks)
    router::spl_autoload_register('templates');
    router::spl_autoload_register('Tasks');

    if (!empty(functions::get('ajax_controller'))) // ajax redirects on modules
         router::spl_autoload_register('ajaxController');
    else router::spl_autoload_register('Main');

  }

}

if (!empty(functions::get('exit')) and functions::get('exit')==='1') $get_exit = true;

if (connect_sessions_db_mssql::start_sessions_db_mssql($get_exit)) MainController::start();
else logs::client_err_log('error connect to app');

?>
