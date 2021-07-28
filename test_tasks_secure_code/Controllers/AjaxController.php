<?php

namespace test_tasks_secure_code\Core\Ajax;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Libs\upload_files;
use test_tasks_secure_code\Libs\chk_file;
use test_tasks_secure_code\Config\config;
use test_tasks_secure_code\Core\router;
use test_tasks_secure_code\Controllers\account;
use test_tasks_secure_code\Models\tasks;

class AjaxController {

  public static function ajax_controller_redirect()
  {
      $ajax_controller = functions::get('ajax_controller');
      if (empty($ajax_controller) or $ajax_controller=='ajaxController') return false;

      router::spl_autoload_register($ajax_controller);

      if ($ajax_controller=='upload_files') 
      {        
        $kf = functions::get("kf");
        $puth_loadf = functions::get("folder");
        $taskiddir = functions::get('taskiddir');
        $ff = functions::get('ff');
        
        upload_files::upload_files_validate_file($kf, $puth_loadf, $taskiddir, $ff);
      }
      else
      if ($ajax_controller=='chk_file') 
      {
        $filename_tmp = functions::get("filename");
        $filename_tmp = urldecode($filename_tmp);
        if (!empty(functions::rhost))
        $filename_tmp = $_SERVER['DOCUMENT_ROOT'] . substr(functions::rhost,1).'/'. $filename_tmp; 
        else
        $filename_tmp = $_SERVER['DOCUMENT_ROOT'] . $filename_tmp; 
          
        $login_query = functions::get("lmd");
        $client_chk_file_k = functions::get("chk_file_k");
        $sys = functions::get("sys");
        $ssget = functions::get('skz');
        
        chk_file::upload_chk_validate_file($filename_tmp, $login_query, $client_chk_file_k, $sys, $ssget);
      }
      
      return true;
  }
      
  public static function request_response_data()
  {

    if (!empty(config::$lnk_db))
    {
      if (AjaxController::ajax_controller_redirect()) return true;

      $load = functions::get('q');

      if ($load == 'registration')
        account::registration();
      else
      if ($load == 'logged_in')
        account::logged_in();
      else
      if ($load == 'load_tasks')
      {
        $now_p = functions::get('now_p');
        $column_sort = functions::get('column_sort');
        $column_sort_ordby = functions::get('column_sort_ordby');        
        $sort_page_num = functions::get('spage_num');
        
        if (!account::preg_match($sort_page_num,'int')) $sort_page_num = 1;

        tasks::load_tasks(true, 
          $now_p, 
          $column_sort, 
          $column_sort_ordby, 
          $sort_page_num);
      }
      else
      if ($load == 'load_json_stat')
      {    
        $get_jstat_login = functions::get('get_login');
        
            $get_login = account::get_new_correct_validate_value($get_jstat_login,'login');      
        if ($get_login!=$get_jstat_login or !account::preg_match($get_login,'login')) { print 'err_user_name'; exit; }
        
        $sp_auth_key = functions::get('spak');
        tasks::load_json_stat($sp_auth_key, $get_login);
      }
      else
      if ($load == 'send_task')
      {
        $date_create = functions::date_sql();	

        $taskId = functions::get('taskId');
        $get_task_name = functions::get('task_name');
        $get_task_email = functions::get('task_email');
        $get_task_descr = functions::get('task_descr');
        
        $task_name = account::get_new_correct_validate_value($get_task_name,'name_user');
        if ($task_name!=$get_task_name or !account::preg_match($task_name,'name_user')) { print 'err_user_name'; exit; }

        $task_email = account::get_new_correct_validate_value($get_task_email,'email');
        if ($task_email!=$get_task_email or !filter_var($task_email, FILTER_VALIDATE_EMAIL)) { print 'err_email'; exit; }

        $task_descr = account::get_new_correct_validate_value($get_task_descr,'description');
        if (!account::preg_match($task_descr,'text_ru_en')) { print 'err_descr'; exit; }
        
        if (strlen($task_name)<1) { print "err_task_descr"; exit; }
        if (strlen($task_descr)<1) { print "err_task_descr"; exit; }
        if (strlen($task_email)<1) { print "err_task_email"; exit; }

        if (strlen($task_name)>50
         or strlen($task_descr)>255
         or strlen($task_email)>255) { print "error_max_length"; exit; }

        $task_id_dir = functions::get('taskiddir');
        if (strlen($task_id_dir)!=14) { $task_id_dir = config::tmp_taskiddir(); }

        if (account::logged_in_correctly() 
        || (account::$user_info['login']=='Anonymous' and !($taskId*1>0))) // not editing
            tasks::send_task($taskId, $task_name, $task_descr, $task_email, $task_id_dir, $date_create);
            else { print 'logged_in_not_correctly'; exit; }
      }
      else
      if ($load == 'edit_task_status')
      {
        if (account::logged_in_correctly())
        {
          $taskId = functions::get('taskId');      
          $status_task = functions::get('status_task');
          tasks::edit_task_status($taskId, $status_task);
        }
        else { print 'logged_in_not_correctly'; exit; }
      }
      else
      if ($load == 'del_task')
      {
        if (account::logged_in_correctly())
        {
          $sp_auth_key2 = functions::get('spak2');
          $taskId = functions::get('taskId');
          $dpId = functions::get('dpId'); // del task [task_id_dir]
          tasks::del_task($sp_auth_key2, $taskId, $dpId);        
        }
        else { print 'logged_in_not_correctly'; exit; }
      }
      else
        print 'error request #0';

    }
    else { print 'error request #1'; exit; }

  }

}

AjaxController::request_response_data();

?>