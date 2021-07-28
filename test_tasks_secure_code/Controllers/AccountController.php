<?php

namespace test_tasks_secure_code\Controllers;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Config\config;
use test_tasks_secure_code\Core\router;
use test_tasks_secure_code\Controllers\AjaxController;

class account {

    public static $user_info = array();

    public static function dir_user_tasks_file() { return substr(md5(account::$user_info['login']),0,10); } // ! directory of user's tasks files (DO NOT CHANGE!)
    
    public static function logged_in_correctly()
    {   
      if (!empty(account::$user_info['login'])
             and account::$user_info['login']!='Anonymous') return true;
    }

    public static function preg_match($value, $column_name)
    {
      if ($column_name=='name_user')
          $pattern = '/^[a-zA-Zа-яА-ЯіїєгёІЇЄГЁ0-9-\s_\.\@]{1,50}$/';
      else
      if ($column_name=='login')
          $pattern = '/^[a-zA-Zа-яА-ЯіїєгёІЇЄГЁ0-9-_\.\@]{1,50}$/';
      else
      if ($column_name=='text_ru_en')
          $pattern = '/^[a-zA-Zа-яА-ЯіїєгёІЇЄГЁ0-9\s\!\@\#\$\€\%\&\*\(\)\_\+\{\}\|\:\?\,\.\/\;\[\]\\\=\-\—\–\"\™\®\№\«\»]{1,255}$/';
      else
      if ($column_name=='int')
          $pattern = '/^[0-9]+$/';
      else return false;

      if (preg_match($pattern, $value)) return true;
    }

    public static function get_new_correct_validate_value($value, $column_name)
    {
      if ($column_name=='login'
       or $column_name=='email'
       or $column_name=='name_user'
       or $column_name=='description')
      {
        $value = urldecode($value);
        $value = strip_tags($value);
        $value = functions::validate_html_script_php($value);
        $value = htmlspecialchars($value, ENT_QUOTES);

        if ($column_name=='login'
         or $column_name=='email')
        $value = preg_replace('/\s+/', '', $value);
      }
      return $value;
    }

    public static function registration()
    {   
      $get_name_user = functions::get('n');
      $get_name_user = strtolower($get_name_user);

      $get_email_user = functions::get('e');
      $get_email_user = strtolower($get_email_user);

      $pasw_user = functions::get('p');
      $current_pasw_user = functions::get('cp');
      $uo = functions::get('uo');
      
          $name_user = account::get_new_correct_validate_value($get_name_user,'login');
      if ($name_user!=$get_name_user or !account::preg_match($name_user,'login')) { print 'err_user_name'; exit; }
      
          $email_user = account::get_new_correct_validate_value($get_email_user,'email');
      if ($email_user!=$get_email_user or !filter_var($email_user, FILTER_VALIDATE_EMAIL)) { print 'err_email'; exit; }
      
      $current_pasw_user_change = false;
      if (!empty($current_pasw_user) and !empty(account::$user_info['login'])) 
      $current_pasw_user_change = true; // change pasw for current session user
  
      if ((!$current_pasw_user_change 
      or ($current_pasw_user_change and (!empty($name_user) and !empty($pasw_user))) )
      and !empty($pasw_user)
      and $name_user!='anonymous'
      and $name_user!='admin')
      {

        if ($current_pasw_user_change)
            $where_user = "[login]='".account::$user_info['login']."' ";
        else
        { // new registration
            if (empty($email_user) and !$current_pasw_user_change) { print 'err_req_mail'; exit; }
            $where_user = "[login]='$name_user' ";
        }
        
        $qsend = "SELECT top 1 login, pasw, email FROM [users] where ".$where_user." order by date_create desc";
        $result = sqlsrv_query(config::$lnk_db,$qsend,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        
        if (sqlsrv_num_rows($result)<>0 or $current_pasw_user_change)
        {
          $res = sqlsrv_fetch_array($result);
  
          if ($current_pasw_user_change)
          { 
            $current_pasw_now_tabl = $res[1];
            $current_email_user_now = $res[2];
            $current_pasw_user = functions::get_pasw_secret_hash($current_pasw_user);
            if ($current_pasw_now_tabl==$current_pasw_user and strlen($current_pasw_now_tabl)==32)
            {// ok access
  
              $qsend = "SELECT top 1 login FROM [users] where [login]='$name_user' order by date_create desc";
              $result_tmp = sqlsrv_query(config::$lnk_db,$qsend,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
              if (sqlsrv_num_rows($result_tmp)==0 or $name_user==account::$user_info['login'])
              {
                $pasw_user = functions::get_pasw_secret_hash($pasw_user); // new hash pasw
                if (empty($email_user)) $email_user = $current_email_user_now;
  
                if (substr_count($uo,'edit_name')>0)
                  sqlsrv_query(config::$lnk_db,"Update [users] set [login] = '$name_user', [email] = '$email_user', [date_last_change] = ".functions::date_sql().", [login_last_change] = '".account::$user_info['login']."' where ".$where_user."; ");
                else
                if (substr_count($uo,'edit_pasw')>0)
                {
                  sqlsrv_query(config::$lnk_db,"Update [users] set [pasw] = '$pasw_user', [login] = '$name_user', [date_last_change] = ".functions::date_sql().", [login_last_change] = '". account::$user_info['login']."' where ".$where_user."; ");
                  functions::exit_system(true);
                }

                print 'ch=1'; // successfully
                exit;
              }
              print 'Such user already exists';
              exit;
            }
            print 'err_ch1.';
            exit;
          }
  
          if (!$current_pasw_user_change)
          print 'Such user already exists';
          else
          print 'err.@311';
          exit;
        }
        else
        { // ok, user not found -> registration
          
          $user_found = false;
          $pasw_user = functions::get_pasw_secret_hash($pasw_user);
  
          $date_create = functions::date_sql();
          sqlsrv_query(config::$lnk_db,"INSERT INTO [users] 
                ([login]
                ,[date_create]
                ,[date_last_change]
                ,[login_last_change]
                ,[email]
                ,[pasw]) 
                VALUES 
                (N'$name_user', ".$date_create.", ".$date_create.", N'".$email_user."', N'".$email_user."', N'".$pasw_user."');");
          
          $qsend = "SELECT top 1 login FROM [users] where [login]='$name_user' order by date_create desc";
          $result_tmp = sqlsrv_query(config::$lnk_db,$qsend,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
          if (sqlsrv_num_rows($result_tmp)==0 or $name_user==account::$user_info['login'])
          $user_found = true;

          $error_send_mail = account::ajax_send_mail();          
          if (!$error_send_mail)
          {
            print 'rsuccessfuly';
            exit;
          }
          else
          {
            print "Email message is not sended. Please enter your real email.";
            exit;
          } 
        
          print "err_req2";
          exit;
        }
  
        print "err_req1";
        exit;
      }
  
      print "err_req";
      exit;
    }
    
    public static function logged_in()
    {
        $get_name_user = functions::get('n');
        $get_name_user = strtolower($get_name_user);

        $pasw_user = functions::get('p');
        
            $name_user = account::get_new_correct_validate_value($get_name_user,'login');
        if ($name_user!=$get_name_user or !account::preg_match($name_user,'login')) { print 'err_user_name'; exit; }

        if (!empty($name_user) and !empty($pasw_user))
        {
  
          $pasw_user = functions::get_pasw_secret_hash($pasw_user);
  
          $l_auth_pc_t = functions::session_connect('l_auth_pc_t');
          $login_auth_process_count = functions::session_connect('login_auth_pc');
          if (empty($login_auth_process_count)) $login_auth_process_count = 0;
          if ($login_auth_process_count>9)
          {
            if (!empty($l_auth_pc_t))
                $sec = strtotime(now)-$l_auth_pc_t;
            
            if (empty($l_auth_pc_t) or $sec>60)
            {
            functions::set_session('login_auth_pc', 0);
            functions::set_session('l_auth_pc_t', '');
            }
            
            print 'error_throttling:'.$sec;
            exit;
          }
  
          $login_auth_process_count++;
          functions::set_session('login_auth_pc', $login_auth_process_count);

          if (empty($l_auth_pc_t))
          functions::set_session('l_auth_pc_t', time());

          $qsend = "SELECT top 1 login FROM [users] where [login] = '$name_user' and [pasw] = '".$pasw_user."' order by date_create desc";
          $result = sqlsrv_query(config::$lnk_db,$qsend,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
          if (sqlsrv_num_rows($result)<>0 
          or ($name_user=='admin' and $pasw_user==functions::get_pasw_secret_hash(md5('agdf34fee23123')))) 
          {
            $res = sqlsrv_fetch_array($result);
            
            $_SESSION['sl'] = $name_user;
            functions::save_cookie_hashID($name_user, config::$lnk_db);
  
            print 'Such user already exists:start';
            exit;
          }
        }
  
      print 'error_li:'.$login_auth_process_count;
      exit;
    }

    public static function ajax_send_mail()
    {    
      $get_to_name = functions::get('n');
      $get_to_email = functions::get('e');
      
          $to_name = account::get_new_correct_validate_value($get_to_name,'name_user');
      if ($to_name!=$get_to_name or !account::preg_match($to_name,'name_user')) { print 'err_name'; exit; }

          $to_email = account::get_new_correct_validate_value($get_to_email,'email');
      if ($to_email!=$get_to_email or !filter_var($to_email, FILTER_VALIDATE_EMAIL)) { print 'err_email'; exit; }
      
      $url_send_query = functions::get('url_send_query');
      $type_query = functions::get('type_query');
  
      $smtp_gmail_config['From_email'] = config::account_gmail_name;
      $smtp_gmail_config['From_login_email'] = config::account_gmail_name;
      $smtp_gmail_config['Password'] = config::account_gmail_pasw;
      $smtp_gmail_config['FromName'] = config::account_from_name;
      $smtp_gmail_config['To_email'] = $to_email;
      $smtp_gmail_config['To_name'] = $to_name;
  
      $smtp_gmail_config['url_send_query'] = $url_send_query;
      $smtp_gmail_config['type_query'] = $type_query;
  
      if (strlen($smtp_gmail_config['url_send_query'])>255) $smtp_gmail_config['url_send_query'] = substr($smtp_gmail_config['url_send_query'],0,255);
  
      $smtp_gmail_config['Subject'] = 'Congratulations, you have successfully registered!';
  
      $smtp_gmail_config['Body'] = 'Hello, <b>'.$smtp_gmail_config['To_name'].'</b><br>Thank you for registering<br><br><br>This email is automatically generated, please do not reply to it.';
  
      router::spl_autoload_register('smtp_gmail_module');

      return $error_send_mail;
    }

    public static function js_onload_config() 
    {        
        if (strpos(functions::https_host(),'landings')>0) $js_min = '.min';

        $js_onload_config = '
        <script type="text/javascript" charset="utf-8" src="'.functions::https_host().'/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="'.functions::https_host().'/js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="'.functions::https_host().'/js/functions'.$js_min.'.js"></script>
        <script type="text/javascript" charset="utf-8" src="'.functions::https_host().'/js/tasks_forms'.$js_min.'.js"></script>
        <script type="text/javascript" charset="utf-8" src="'.functions::https_host().'/js/app_tasks_js_controller'.$js_min.'.js"></script>
        <script type="text/javascript" charset="utf-8" src="'.functions::https_host().'/js/upload_files'.$js_min.'.js"></script>
        ';


        $js_onload_config.= "
        <script>
  
            window.onload = function()
            {   
                app_tasks_js_controller.gl = '".
                functions::get('gl')."';
  
                app_tasks_js_controller.kf = '".
                config::kf()."';
  
                app_tasks_js_controller.dir_user_tasks_file = '".
                account::dir_user_tasks_file()."';
  
                tmp_puth_user = '".
                config::tmp_puth_user()."';
  
                tmp_taskiddir = '".
                config::tmp_taskiddir()."';
  
                chk_file_k = '".
                config::chk_file_k()."';
  
                app_tasks_js_controller.spak_key = '".
                config::spak_key()."';
  
                app_tasks_js_controller.spak_key2 = '".
                config::spak_key2()."';
                
                app_tasks_js_controller.skz_key = '".
                config::skz_key()."';
            ";
        
            if (!empty(account::$user_info['login']) 
                   and account::$user_info['login']!='Anonymous') { 
            $js_onload_config.= "
                app_tasks_js_controller.login = '".
                account::$user_info['login']."';  // login session tasks
                
                app_tasks_js_controller.email = '".
                account::$user_info['email']."'; // email session tasks
                
                ";
            } else { 
            $js_onload_config.= "
                app_tasks_js_controller.login = '';
                app_tasks_js_controller.email = '';
                ";
            }
            
            if (account::logged_in_correctly()){
            $js_onload_config.= "
                app_tasks_js_controller.session_connect = true;
                ";
            } 
            
        $js_onload_config.= "app_tasks_js_controller.ready();
            }
            
        </script>";
  
        print $js_onload_config;
    }

}

?>
