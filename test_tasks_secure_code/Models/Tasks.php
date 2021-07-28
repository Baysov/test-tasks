<?php 

namespace test_tasks_secure_code\Models;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Config\config;
use test_tasks_secure_code\Controllers\account;
use test_tasks_secure_code\Views\templates;

class tasks
{

    public static $sort_tasks_list = 'load task list..';
    public static $sort_page_pos = 3; // max results on page

    public static function load_tasks(
        $method_ajax_load_sort_tasks_list, 
        $now_p, 
        $column_sort, 
        $column_sort_ordby, 
        $sort_page_num)
    {
                
        if ($column_sort=='user_name') // sort by user name (first column)
        {
            $sort_ord1 = $column_sort_ordby;
            $add_class_user_active = 'active';
            $sort_sequence = "name ".$column_sort_ordby.", email asc, status_task asc";
        }else
        if ($column_sort=='email') // sort by email (first column)
        {
            $sort_ord2 = $column_sort_ordby;
            $add_class_email_active = 'active';
            $sort_sequence = "email ".$column_sort_ordby.", name asc, status_task asc";
        }else
        if ($column_sort=='status') // sort by status (first column)
        {
            $sort_ord3 = $column_sort_ordby;
            $add_class_status_active = 'active';
            $sort_sequence = "status_task ".$column_sort_ordby.", name asc, email asc";
        } else { print 'error_load_tasks_sort'; exit; }
        
        $where_sort = " order by ".$sort_sequence.", date_create desc";
                
        $num_start = ($sort_page_num-1) * tasks::$sort_page_pos;
        $num_end = $num_start + tasks::$sort_page_pos;
        
        $query_load_tasks = "
        select tmp.* from ( 
            SELECT *, ROW_NUMBER() OVER(".$where_sort.") as num FROM [tasks]
            ) as tmp 
        where tmp.num>".$num_start." and tmp.num<=".$num_end." ";

        tasks::$sort_tasks_list = '';
        $result = sqlsrv_query(config::$lnk_db,$query_load_tasks,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
        while ($sql_data = sqlsrv_fetch_array($result))
        {
            // clear prms
            $status_descr_update = '';
            $onClick_comleted_task_directory = '';
            $edit_task = '';
            $delete_task = '';
            $edit_task_status = '';
            $list_images = '';
            $last_change_date = date("d.m.Y H:i");
            //-----------------------------------------------
            
            $zone = 3600; // Kyiv time
            
            $taskId = $sql_data[0];
            $get_login = $sql_data[1];
            $date_get = functions::date_get_sql($sql_data[2],'full_date');
            $date = gmdate("M D Y H:i", strtotime($date_get) + $zone);
            
            $task_name = $sql_data[3];
            $task_name = htmlspecialchars_decode($task_name);

            $task_descr = $sql_data[4];
            $task_descr = htmlspecialchars_decode($task_descr);

            $task_email = $sql_data[5];
            $task_directory = $sql_data[6];
            $task_status = $sql_data[7];
            $task_status_change_descr = $sql_data[8];

            // load all images for this task
            $dir_user_tasks_file = substr(md5($get_login),0,10); // ! directory of user's tasks files (DO NOT CHANGE!)      
            $dir_task_s = 'storage_files/images_of_tasks/'.$dir_user_tasks_file.'/'.$task_directory;
            //---------------------------------------------------------------------------------------
            if (!empty(functions::rhost)) $dir_task = substr(functions::rhost,1).'/'.$dir_task_s;
                            else $dir_task = $dir_task_s;
            //---------------------------------------------------------------------------------------    
            if (is_dir($_SERVER['DOCUMENT_ROOT'].$dir_task))
            {
                $images_task_user = functions::scandirs_custom($_SERVER['DOCUMENT_ROOT'].$dir_task);
                for ($i=0; $i<count($images_task_user); $i++)
                {
                    $src_img_task = $dir_task_s.'/'.$images_task_user[$i];
                    $list_images.= " <a href=\"".$src_img_task."\" target=\"_blank\"><img class=\"cls_img_task cls_img_pId".substr(md5($dir_task_s),0,10)."\" src=\"".$src_img_task."\" alt=\"".$taskId."-".($i+1)."\" title=\"".$taskId."-".($i+1)."\"></a>";
                }

                if (!empty($list_images)) $list_images = '<p class="card-text">'.$list_images.'</p>';
            }
            
            if ($task_status_change_descr=='1')
            $status_descr_update = '<small class="pt-1 px-3 text-warning">Отредактировано администратором</small>';

            if (account::logged_in_correctly()) //  edit task
            {
                $onClick_comleted_task_directory = " onClick=\"app_tasks_js_controller.comleted_task_directory('$dir_user_tasks_file','$task_directory','$taskId');\" ";

                $edit_task = "<button type=\"button\" class=\"btn btn-sm btn-outline-secondary cls_task_dir\" onClick=\"app_tasks_js_controller.change_task_directory('$dir_user_tasks_file','$task_directory','$taskId');\">Редактировать</button>";
                
                $delete_task = "<button type=\"button\" class=\"btn btn-sm btn-outline-secondary cls_task_dir\" onClick=\"app_tasks_js_controller.delete_task_directory('$dir_user_tasks_file','$task_directory','$taskId');\">Удалить</button>";
            }
            
            if (account::logged_in_correctly() or $task_status=='1')
            {
                $cls_task_status_active = '';
                if ($task_status=='1') $cls_task_status_active = ' cls_task_status_active';
                $edit_task_status = "<button type=\"button\" class=\"btn btn-sm btn-outline-secondary cls_task_dir".$cls_task_status_active."\" ".$onClick_comleted_task_directory." id=\"id_task_".$taskId."_status\">Выполнено</button>";
            }

            tasks::$sort_tasks_list.= templates::add_task_in_list(               
                substr(md5($dir_task_s),0,10),
                $taskId, 
                $task_name,
                $task_descr, 
                $task_email,
                $list_images,
                $last_change_date,
                $status_descr_update,
                $edit_task,
                $edit_task_status,
                $delete_task
            );

        }

        if (!empty(tasks::$sort_tasks_list))
        tasks::$sort_tasks_list = templates::generate_album_tasks_sort_list(
            $add_class_user_active, $sort_ord1,
            $add_class_email_active, $sort_ord2,
            $add_class_status_active, $sort_ord3).tasks::$sort_tasks_list;

        
        $result = sqlsrv_query(config::$lnk_db,"SELECT count(*) FROM [tasks] ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
        if (sqlsrv_num_rows($result)<>0) 
        {
            $res = sqlsrv_fetch_array($result);
            $count_tasks = $res[0];
        }
        
        if ($method_ajax_load_sort_tasks_list)
        {
            if (strlen($now_p)==32 && md5(tasks::$sort_tasks_list)==$now_p)
            print '|:|-1';  // нет обновлений
            else
            {
                $cp_tmp = $count_tasks/tasks::$sort_page_pos;
                $cp_tmpX = strpos($cp_tmp,'.');
                if ($cp_tmpX>0) $count_pages = substr($cp_tmp,0,$cp_tmpX)*1+1;
                else
                $count_pages = $cp_tmp*1;
                
                $pagination = '';
                for ($i=1;$i<=$count_pages;$i++)
                {
                    if ($i==$sort_page_num) $cls_active = 'active'; else $cls_active = '';
                    $pagination.= '<a class="cls_page_num btn-outline-secondary '.$cls_active.'">'.$i.'</a>';
                }

                print tasks::$sort_tasks_list.'|:|'.$now_p.'|:|'.$count_tasks.'|:|'.templates::generate_pagination_tasks_sort_list($pagination).'|:|'; 
            }
            exit;

            //--------------------------------------------
            print 'err_load_tasks';
            exit;
        }
    }

    public static function edit_task_data($taskId, $update_task_params_arr)
    {     

        if (!($taskId*1>0)) return false;

        $update_columns = '';
        foreach ($update_task_params_arr as $column => $value)
        {            
            if ($column=='task_name') 
            $update_columns.= "[name]=N'$value', ";

            if ($column=='task_email')  
            $update_columns.= "[email]=N'$value', ";

            if ($column=='task_descr') { $task_descr = $value;
            $update_columns.= "[description]=N'$value', "; }

            if ($column=='status_task')  
            $update_columns.= "[status_task]='$value', ";
        }

        if (!empty($update_columns)) $update_columns = substr($update_columns,0,-2);
        $update_task_params = $update_columns;

        $result = sqlsrv_query(config::$lnk_db,"SELECT description FROM [tasks] where [taskId] = '".$taskId."' ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        if (sqlsrv_num_rows($result)<>0) 
        {
            $res = sqlsrv_fetch_array($result);

            if (!empty($task_descr) and $res[0]!=$task_descr) $status_change_descr = ', [status_change_descr]=1'; // отредактировано администратором
            
            $update_task_params.= $status_change_descr;
            $query_update_task = "UPDATE [tasks] SET ".$update_task_params." where [taskId] = '".$taskId."' ";

            sqlsrv_query(config::$lnk_db,$query_update_task);

            return true;
        }
    }

    public static function edit_task_status($taskId, $status_task)
    {
        if ($status_task!='1') $status_task = '0';

        $result_update = tasks::edit_task_data($taskId, array('status_task'=>$status_task));
        if ($result_update)
        print 'edit_task_data:task-update';
        else
        print 'edit_task_data:task-err';
        exit;
    }

    public static function send_task($taskId, $task_name, $task_descr, $task_email, $task_id_dir, $date_create)
    {
        if (!empty($taskId) and ($taskId*1)>0)
        {// UPDATE task
            
            $result_update = tasks::edit_task_data($taskId,
            array(
            'task_email'=>$task_email,
            'task_name'=>$task_name,
            'task_descr'=>$task_descr));

            if ($result_update)
            print 'edit_task_data:task-update'; 
            else
            print 'edit_task_data:task-err'; 
            exit;

        }
        else // NEW INSERT task
        {

            // поверить, когда был отправлен последний запрос от пользователя, чтобы определить возможность отправки следующего запроса (ограничить количество запросов в 1 мин)
            $result = sqlsrv_query(config::$lnk_db,"SELECT top 1 description FROM [tasks] where [login] = '".account::$user_info['login']."' and [date_create] > convert(datetime,'".date("Y-m-d H:i:00.000", time()-60)."',120) order by date_create desc",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
            if (sqlsrv_num_rows($result)<>0) 
            {
                $res = sqlsrv_fetch_array($result);
                print 'err_timeout:?'; // already sended
                exit;
            }
            
            sqlsrv_query(config::$lnk_db,'INSERT INTO [tasks] 
            ([login]
            ,[date_create]
            ,[name]
            ,[email]
            ,[description]
            ,[task_id_dir]
            ,[status_task]
            ,[status_change_descr]) 
            VALUES 
            (N\''.account::$user_info['login']."', ".$date_create.", N'".$task_name."', N'".$task_email."', N'".$task_descr."', N'".$task_id_dir."', '0','0'); ");
        }
        //----------------------------
        print 'send_task:task';
        exit;
    }

    public static function load_json_stat($sp_auth_key, $get_login)
    {
    //--------------------------------------------
    $special_auth_key = config::spak_key();
    if (!empty(account::$user_info['login'])
    and !empty($get_login)
    and $special_auth_key==$sp_auth_key) 
    {// Запрос выполняется только под авторизированным пользователем и при использовании дополнительной аутентификации
        //--------------------------------------------   
        $result = sqlsrv_query(config::$lnk_db,"SELECT email FROM [users] where [login]='$get_login' ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
        if (sqlsrv_num_rows($result)<>0 or $get_login=='Anonymous' or $get_login=='admin') 
        { // find user in dbs
        $res = sqlsrv_fetch_array($result);
        $email = $res[0];
        $result = sqlsrv_query(config::$lnk_db,"SELECT count(*) FROM [tasks] where [login]='$get_login' ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
        if (sqlsrv_num_rows($result)<>0) 
        { // find tasks
            $res = sqlsrv_fetch_array($result);
            $count_tasks = $res[0];

            // получить последнюю задачу пользователя и ёё название Title/description (last_task_name)
            $result = sqlsrv_query(config::$lnk_db,"SELECT TOP 1 description, task_id_dir FROM [tasks] where [login]='$get_login' order by date_create desc ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
            if (sqlsrv_num_rows($result)<>0) 
            { // find tasks
                $res = sqlsrv_fetch_array($result);
                $last_task_name = $res[0]; // lst task name
                $task_id_dir = $res[1];    // last task id dir

                
                $tmp_dir_user_tasks_file = substr(md5($get_login),0,10); // ! directory of user's tasks files (DO NOT CHANGE!)        
                $dir_task_s = 'storage_files/images_of_tasks/'.$tmp_dir_user_tasks_file.'/'.$task_id_dir;
                //---------------------------------------------------------------------------------------   

                //---------------------------------------------------------------------------------------
                if (!empty(functions::rhost)) $dir_task = substr(functions::rhost,1).'/'.$dir_task_s;
                            else $dir_task = $dir_task_s;
                //--------------------------------------------------------------------------------------- 
                    
                if (is_dir($_SERVER['DOCUMENT_ROOT'].$dir_task))
                {   
                    $last_task_imgs = functions::scandirs_custom($_SERVER['DOCUMENT_ROOT'].$dir_task, functions::https_host().'/'.$dir_task_s.'/');
                }
            }

            $array = array(
            'ID user' => $get_login, 
            'email' => $email, 
            'count_tasks' => $count_tasks, 
            'last_task_name' => $last_task_name, 
            'last_task_imgs' => $last_task_imgs
            );

            header('Content-Type: application/json');
            $json = json_encode($array, JSON_UNESCAPED_SLASHES);
            print $json;
            exit;
        }
        }
    }
    print 'err_load_json_stat';
    exit;
    }

    public static function del_task($sp_auth_key2, $taskId, $dpId)
    {
        if (!empty(account::$user_info['login']) and $taskId*1>0)
        {
            $special_auth_key = config::spak_key2();
            if (!empty($dpId)
            // <- .. and dpId catalog find..
            and $special_auth_key==$sp_auth_key2) 
            {// Запрос выполняется только под авторизированным пользователем и при использовании дополнительной аутентификации
                
                    sqlsrv_query(config::$lnk_db,"DELETE [tasks] where [taskId]='$taskId' ");

                    $result = sqlsrv_query(config::$lnk_db,"SELECT taskId FROM [tasks] where [taskId]='$taskId' ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
                    if (sqlsrv_num_rows($result)==0) 
                    print 'successfuly';
                    else
                    print 'error delete';
                    exit;
            }
        }
        print 'err_del_task';
        exit;
    } 


}


?>