<?php

namespace test_tasks_secure_code\Libs;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Config\config;

class functions
{

  const rhost = '/test-tasks'; // temp project name
  public static function domain() { return $_SERVER['HTTP_HOST']; }
  public static function req_uri() { return $_SERVER['REQUEST_URI']; }
  public static function https_host() { return 'https://'.functions::domain().functions::rhost; }

  // провести авто-очистку неиспользуемых каталогов хранения изображений не подтвержденных задач
  public static function auto_delete_dirs_for_empty_tasks()
  {
  
    if  (substr_count('/?start',$_SERVER['REQUEST_URI'])==-1) return true; // default status ничего не выполнять (оставить выполнение очистки и анализа, только с главной и для зарегистрированных пользователей)
    $dir_task_s = 'storage_files/images_of_tasks/';
    //---------------------------------------------------------------------------------------
    if (!empty(functions::rhost)) $dir_task = substr(functions::rhost,1).'/'.$dir_task_s;
                  else $dir_task = $dir_task_s;
    //---------------------------------------------------------------------------------------
    // ПОЛУЧИТЬ СПИСОК СУЩЕСТВУЮЩИХ ЗАДАЧ, ЧТОБЫ ОСТАВИТЬ ИХ
    $list_tasks = array();
    $next = 0;
    $result = sqlsrv_query(config::$lnk_db,"SELECT task_id_dir FROM [tasks] ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  
    while ($sql_data = sqlsrv_fetch_array($result))
    {
      $list_tasks[$next]=$sql_data[0];
      $next++;
    }

    // ПОЛУЧИТЬ СПИСОК КАТАЛОГОВ ДЛЯ АНАЛИЗА
    if (is_dir($_SERVER['DOCUMENT_ROOT'].$dir_task) // exist. dir task
    and strpos($dir_task,'storage_files/images_of_tasks') // only directories in -> "storage_files/images_of_tasks"
    and count($list_tasks)>0) // WARNING! >0 -> correct find tasks 
    {
    $images_task_user = array();
    functions::dirToArray($_SERVER['DOCUMENT_ROOT'].$dir_task,$images_task_user);
    //------------------------------------------------------------------
    for ($i=0; $i<count($images_task_user); $i++)
      {
        $images_task_user[$i] = preg_replace("/\/\/+/","/",$images_task_user[$i]);  
        $task_find = false; // каталог не найден среди зарегистрированных задач
        for ($j=0; $j<count($list_tasks); $j++)
        { 
          if (strpos($images_task_user[$i],$list_tasks[$j])>0)
          {// найден каталог существующей задачи
          $task_find = true;
          break;
          }
        }
        
        if (!$task_find) // разрешаем удалить каталог, так как он не обнаружен в списке сущестующих задач пользователей
        {
          $del_dir = substr($images_task_user[$i],strpos($images_task_user[$i],$dir_task));
          if (is_dir($_SERVER['DOCUMENT_ROOT'].$del_dir)) // directory exist
          {
            system("RMDIR ".preg_replace("/\/+/","\\",$_SERVER['DOCUMENT_ROOT'].$del_dir)." /s /q");
          }
        }
        
      }

    //------------------------------------------------------------------
    } else return false; // error?
    return true; // default status
  }

  public static function scandirs_custom($start,$link)
  {
      if (empty($start)) return false;
      $files = array();
      $handle = opendir($start);
      while (false !== ($file = readdir($handle)))
      {
          if ($file != '.' && $file != '..')
          {
              if (is_dir($start.'/'.$file))
              {
                $dir = functions::scandirs_custom($start.'/'.$file,$link);
                $files[$file] = $dir;
              }
              else 
              {
                  array_push($files, $link.$file);
              }
          }
      }
      closedir($handle);
      return $files; 
  }

  public static function dirToArray($start, &$images_task_user)
  {
      if (empty($start)) return false;
      $handle = opendir($start);
      while (false !== ($file = readdir($handle)))
      {
          if ($file != '.' && $file != '..')
          {
              if (is_dir($start.'/'.$file))
              {
                $dir = functions::dirToArray($start.'/'.$file, $images_task_user);
                if (strpos($file,'.')==0 and strlen($file)!=10)
                $images_task_user[] = $start.'/'.$file;
              }
              else 
              {
                if (strpos($file,'.')==0 and strlen($file)!=10)
                array_push($images_task_user, $start.'/'.$file);
              }
          }
      }
      closedir($handle);
  }
        
  public static function get($var)
  {
      if (!empty($var) and (isset($_GET[$var]))) { if (strlen($_GET[$var])>255) return substr($_GET[$var],0,255); else return $_GET[$var]; }
      return '';
  }
  
  public static function post($var)
  {
    if (!empty($var) and (isset($_POST[$var]))) {  if (strlen($_POST[$var])>255) return substr($_POST[$var],0,255); return $_POST[$var]; }
    return '';
  }
    
  public static function set_session($var, $value)
  {
    $_SESSION[$var] = $value;
    return true;
  }

  public static function unset_session($var)
  {
    unset($_SESSION[$var]);
    return true;
  }

  // проверяет подключение к сессии если $var = '' и возвращает true если сессия открыта
  // Если $var<>'' возвращает значение запрашиваемой переменной из сессии
  // Если значения нет и сессия открыта или не открыта возвращает ''
  public static function session_connect($var)
  { // в firefox сессия не устанавливается из за проверки этого условия
    if (empty($var)) { if (isset($_REQUEST[session_name()])) return true; } // подключена ли сессия
    if (!empty($var) and (isset($_REQUEST[session_name()])))
        {
        if (isset($_SESSION[$var])) return  $_SESSION[$var];
                            else return '';
      } return '';
  }
    
  // установить переменную cookie
  public static function set_cookie($var, $value)
  {
    $catalog_cookie = "/test-tasks/";
    if ($_SERVER['HTTP_HOST']=='landings')
    setcookie($var, $value,  time() + 432000, $catalog_cookie."; SameSite=Lax"); // 432000sec - 5 day cookie
    else // only https
    setcookie($var, $value,  time() + 432000, $catalog_cookie."; SameSite=Lax, Secure=true"); // 432000sec - 5 day cookie
    return true;
  }

  // разрегистрировать переменную cookie
  public static function unset_cookie($var)
  {
    unset($_COOKIE[$var]);
    return true;
  }
  
  public static function cookie_connect($var)
  {
    return $_COOKIE[$var];
  }

  // возвращает правильный формат Даты для записи в SQL
  public static function date_sql($date='',$type='days')
  {
    if (empty($date))
    return "convert(datetime,'".date("Y-m-d H:i:s")."',120)"; 
    else // дни...
    if ($date=='not_time') // без времени
    return "convert(datetime,'".date("Y-m-d")."',120)"; 
    else // дни...
    if ($date=='time_minus_10min') // время -10 минут
    return "convert(datetime,'".date("Y-m-d H:i",time()-600)."',120)"; 
    else // дни...
    if ($date[0]=='+' and strlen($date)>1 and $type=='days') // добавляет + N дней от текущей даты
    return "convert(datetime,'".date("Y-m-d",time()+substr($date,1)*3600*24)."',120)"; 
    else
    if ($date[0]=='-' and strlen($date)>1 and $type=='days') // отнимает - N дней от текущей даты
    return "convert(datetime,'".date("Y-m-d",time()-substr($date,1)*3600*24)."',120)";
    else // секунды...
    if ($date[0]=='+' and strlen($date)>1 and $type=='sec') // добавляет + N секунд от текущей даты
    return "convert(datetime,'".date("Y-m-d H:i:s",time()+substr($date,1))."',120)"; 
    else
    if ($date[0]=='-' and strlen($date)>1 and $type=='sec') // отнимает - N секунд от текущей даты
    return "convert(datetime,'".date("Y-m-d H:i:s",time()-substr($date,1))."',120)";
    else // else => $date = 01.01.2013
    return "convert(datetime,'".substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2).date(" H:i")."',120)"; 
  }

  public static function date_get_sql($res, $type = '')
  {
    if (empty($type)) return date_format($res, 'd.m.Y H:i:s');
    if ($type=='full_date') return date_format($res, 'd.m.Y H:i');
    else
    if ($type==1) return date_format($res, 'H:i');
    else
    if ($type==2) return date_format($res, 'd.m.Y');
    return 'тип не зарезервирован';
  }

  public static function save_cookie_hashID($email_user)
  {
    if (strlen($email_user)<2) return;
    $email_user = strtolower($email_user);
    functions::set_session('login', $email_user);
    
    $catalog_cookie = "/test-tasks/";
    if ($_SERVER['HTTP_HOST']=='landings')
    setcookie('login', $email_user,  time() + 432000, $catalog_cookie."; SameSite=Lax"); // 432000sec - 5 day cookie
    else // only https
    setcookie('login', $email_user,  time() + 432000, $catalog_cookie."; SameSite=Lax, Secure=true"); // 432000sec - 5 day cookie  
  }

  public static function get_pasw_secret_hash($pasw_user)
  {
    if (strlen(config::hash_pasw_project())==32) 
    return md5(config::hash_pasw_project().$pasw_user);
    else return md5('not secure password hash if not configured'); 
  }

  public static function exit_system($exit_funct)
  {
    if ($exit_funct and isset($_REQUEST[session_name()])) 
    {
      setcookie('login');
      functions::unset_cookie('login');

      if (isset($_SESSION['sl'])) unset($_SESSION['sl']);
      session_destroy();
    } 
  }

  public static function translite($str='', $type='')
  {
    //---------------------
      static $tbl= array(
      '.'=>'-', '@'=>'@', '_'=>'-', ' '=>'-',  '-'=>'-', '/'=> '-', '\\'=>'-', 
      //-------- Numeric
      '0'=>'0', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9',
      //-------- Special Chars
      'quot1;'=>'-', 'quot;'=>'-', '34;'=>'-', '39;'=>'-', 'apos;'=>'-', 'resh;'=>'-', 'gt;'=>'-', 
      'lt;'=>'-', 'and;'=>'-', 'amp;'=>'-', "'"=>'-', '"'=>'-',
      //-------- English lower case
      'q'=>'q', 'w'=>'w', 'e'=>'e', 'r'=>'r', 't'=>'t', 'y'=>'y', 'u'=>'u', 'i'=>'i',
      'o'=>'o', 'p'=>'p', 'a'=>'a', 's'=>'s', 'd'=>'d', 'f'=>'f', 'g'=>'g', 'h'=>'h',
      'j'=>'j', 'k'=>'k', 'l'=>'l', 'z'=>'z', 'x'=>'x', 'c'=>'c', 'v'=>'v', 'b'=>'b',
      'n'=>'n', 'm'=>'m',
      //-------- English upper case
      'Q'=>'Q', 'W'=>'W', 'E'=>'E', 'R'=>'R', 'T'=>'T', 'Y'=>'Y', 'U'=>'U', 'I'=>'I',
      'O'=>'O', 'P'=>'P', 'A'=>'A', 'S'=>'S', 'D'=>'D', 'F'=>'F', 'G'=>'G', 'H'=>'H',
      'J'=>'J', 'K'=>'K', 'L'=>'L', 'Z'=>'Z', 'X'=>'X', 'C'=>'C', 'V'=>'V', 'B'=>'B',
      'N'=>'N', 'M'=>'M',
      //-------- Russ to English upper & lower case
      'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'zh', 'з'=>'z',
      'и'=>'i', 'й'=>'j', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
      'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'y', 'э'=>'je', 'А'=>'A',
      'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'ZH', 'З'=>'Z', 'И'=>'I',
      'Й'=>'J', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
      'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'Y', 'Э'=>'JE', 'ё'=>'yo', 'х'=>'h',
      'ц'=>'c', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shh', 'ю'=>'yu', 'я'=>'ya',
      'Ё'=>'YO', 'Х'=>'H', 'Ц'=>'C', 'Ч'=>'CH', 'Ш'=>'SH', 'Щ'=>'SHH',
      'Ю'=>'YU', 'Я'=>'YA', 
      //-------- other
      'ь'=>'', 'ъ'=>'', 'Ь'=>'', 'Ъ'=>'',
      //-------- URK to English upper & lower case
      'і'=>'i', 'І'=>'I', 'ї'=>'i', 'Ї'=>'I'
      //--------
      );
      $res = strtr($str, $tbl);
      $res = strtolower($res);
      $i=0; $new_res = '';
      while ($i<strlen($res))
      {
        //-------------------
        if ( ($res[$i]>='a' and $res[$i]<='z')
          or ($res[$i]>='а' and $res[$i]<='я')
          or ($res[$i]>='0' and $res[$i]<='9')
          or $res[$i]=='-' or $res[$i]=='@' or $res[$i]=='?' or $res[$i]=='/'
          
            ) $new_res.= $res[$i];
        //-------------------
        $i++;
      }
      //--------------
      $res = $new_res;
      //----- заменяем все "----" на одно "-"
      $res = preg_replace("/--+/","-",$res);  
    
      if ($res[0]=='-') $res = substr($res,1);
      if ($res[strlen($res)-1]=='-') $res = substr($res,0,strlen($res)-1);
      //------------	

  return $res;
  }	

  // подготовить текст, удалить пробелы, теги html         
  public static function validate_html_script_php($text_html)
  {
      /* <script */
      $search = array ("'<script[^>]*?>.*?</script>'si");
      $replace = array ('err_script'); 
      $text_html = preg_replace($search, $replace, $text_html);
      /* <?php */
      $search = array ("'\<\?'si");
      $replace = array ('err_php'); 
      $text_html = preg_replace($search, $replace, $text_html);
      /* ?> */
      $search = array ("'\?\>'si");
      $replace = array ('err_php'); 
      $text_html = preg_replace($search, $replace, $text_html);

      return $text_html;
  }

}

?>