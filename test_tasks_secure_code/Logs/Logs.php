<?php

namespace test_tasks_secure_code\Logs;

class logs {
    
    public static function client_err_log($log_msg_id, $app_exit) {
        if ($log_msg_id=='config-not-found') 
        $msg = 'Error connected to site or db.<br>File settings class "Config/" is not found<br>Please contact for author this application.<br><a href="index.php?ajax_controller=readme.txt" target="_blank">View application settings readme.txt</a>'; 
        else
        if ($log_msg_id=='config-not-found') 
        $msg = 'Error connected to site or db.<br>File settings class "Config/" file settings class found, but db connection not established<br>Please contact for author this application.<br><a href="index.php?ajax_controller=readme.txt" target="_blank">View application settings readme.txt</a>'; 
        
        print $msg;
        if ($app_exit) exit;
    }

}

?>