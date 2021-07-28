<?php

namespace test_tasks_secure_code\Libs;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Config\config;
use test_tasks_secure_code\Controllers\account;

class chk_file {

  public static function upload_chk_validate_file($filename_tmp, $login_query, $client_chk_file_k, $sys, $ssget)
  {
    //-------------------------------------------
    $chk_file_k = config::chk_file_k();
    if ($chk_file_k==$client_chk_file_k 
    and !empty($filename_tmp) 
    and (md5(account::$user_info['login'])==$login_query 
      or md5(account::$user_info['login'])=='7079c72c21415131774625ba1d64f4b0') // md5('Anonymous') -> 7079c72c21415131774625ba1d64f4b0
    and !empty($sys))
    {

      if ($sys=='v1') // file exists puth
      {
        if (is_file($filename_tmp)) print "true";
                              else print "false";
        exit;
      } 

      $skz = config::skz_key();
      if ($sys=='v2' and $ssget==$skz and !empty($skz) and is_file($filename_tmp)) // delete puth file
      {
      //---------------
      $delete_files = false;
      //--------------
      $upf_tmp = 'storage_files/images_of_tasks/'.substr(md5(account::$user_info['login']),0,10).'/'; // ! Validate -> Identify the user's store
      if (substr_count($filename_tmp,$upf_tmp)>0) // -> ok?
      {
        if (is_file($filename_tmp))
        {
            $filename_tmp = str_replace('/','\\',$filename_tmp);
            system("del ".$filename_tmp);
            if (!is_file($filename_tmp)) 
            {
              print 'true'; // successfully delete file image
              exit;
            }
        }
      }
      print 'false';
      exit;
      } 
    }
    print "false";
    exit;
  }

}

?>