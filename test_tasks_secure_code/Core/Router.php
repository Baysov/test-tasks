<?php

namespace test_tasks_secure_code\Core;

use test_tasks_secure_code\Core\Sessions\connect_sessions_db_mssql;

class router {

   // SECURITY APP DIRECTORY STORAGE
   public static function test_tasks_secure_code_dir()
   {
    if (is_dir(__DIR__)) 
    return substr(__DIR__,0,strrpos(__DIR__, 'test_tasks_secure_code'))."test_tasks_secure_code\\";
    else return '';
   }

    public static function spl_autoload_register($class) { 
        
        if (router::test_tasks_secure_code_dir())
        {
            $dir_classes = [
                'Core',
                'Core\Sessions',
                'Config',
                'Models',
                'Views',
                'Views\templates',
                'Controllers',
                'Libs',
                'Libs\PHPMailer_5_2_4_sendmail',
                'Logs'
            ];
            
            foreach ($dir_classes as $dir)
            {
                $ext = 'php';
                if (strpos($class,'.txt')>0) $ext = 'txt';
                $puth_dir_classes = router::test_tasks_secure_code_dir().$dir."\\".$class.'.'.$ext;
                
                if (file_exists($puth_dir_classes))
                { require_once $puth_dir_classes; return true; }
            }
        }
        
        print 'Error app class "'.$class.'" not found. Please refresh configuration "test_tasks_secure_code\" folder for app.'; 
        exit;

    }

    public static function app_run(){
        
        router::spl_autoload_register('functions');
        router::spl_autoload_register('config');
        router::spl_autoload_register('logs');
        router::spl_autoload_register('AccountController');
        
        // start (sessions+cookie) user db - sessions.php (mssql)
        router::spl_autoload_register('SysSession');
        router::spl_autoload_register('connect_sessions_db_mssql');

        router::spl_autoload_register('Application');

    }

}

router::app_run();

?>