<?php
 
namespace test_tasks_secure_code\Config;

use test_tasks_secure_code\Controllers\account;

// connect to db (mysql\mssql\other)
class config
 {
   const db_name = 'test_tasks';
   const db_user = 'test-tasks-db';
   const db_pasw = 'DFSOGMHOERJH#)GJDSgGWE:IRNGsdfgno4og';

   // connect to account Test.Serv.Mls@gmail.com (test account for sending email messages)
   const account_from_name = 'test-tasks';
   const account_gmail_name = 'Test.Serv.Mls@gmail.com';
   const account_gmail_pasw = 'p9gh54giubdsnl_#@$KFGJGjg';

   public static $lnk_db = -1;

   public static $glocal = '(local)';

   // settings secure hash of generating registration password (specify any character set for your project)
   public static function hash_pasw_project() 
   { return md5('GE$Y%UEDRSDHFDFYTITF#@$YJTFG'); 
   } // ! do not change 'GE$Y%UEDRSDHFDFYTITF#@$YJTFG', keep secret (in the database, other storages)

   public static function dir_user_tasks_file() { return substr(md5(account::$user_info['login']),0,10); } // ! directory of user's tasks files (DO NOT CHANGE!)
 
   public static function host_name()
   {
       return $_SERVER['HTTP_HOST'];
   }

   public static function full_host_name()
   {
       return $_SERVER['HTTP_PROTOCOL'].'::/'.$_SERVER['HTTP_HOST'];
   }

   public static function tmp_puth_user() { 
       $tmp_puth_user = "storage_files/images_of_tasks/";
       if (!is_dir($tmp_puth_user)) mkdir($tmp_puth_user);

           
       $tmp_puth_user = "storage_files/images_of_tasks/".account::dir_user_tasks_file()."/";
       if (!is_dir($tmp_puth_user)) mkdir($tmp_puth_user);

       return $tmp_puth_user;
   }
   
   public static function tmp_taskiddir() { 
       $tmp_taskiddir = date("dmYHis");
       return $tmp_taskiddir;
   }
   
   public static function chk_file_k() { 
       return md5(date("dmY").'lksfgj934g'); // rezerved key
   }

   public static function kf() { 
       return md5(date("mYdd").account::$user_info['login']."G%@Bkyup#@$"); // rezerved api key
   }
   
   public static function spak_key() { 
       return md5(account::$user_info['login'].'GJERD(GJ#$GFGG'); // rezerved api key sess ajax load_json_stat
   }
   
   public static function spak_key2() { 
       return md5(account::$user_info['login'].'lkdj321sfbviuRHTDGF35FHb'); // rezerved api key 2 sess ajax registration new user  
   }

   public static function skz_key() { 
       return md5(account::$user_info['login'].date("ddmY--")); // rezerved api key
   }

 }

 
    
?>