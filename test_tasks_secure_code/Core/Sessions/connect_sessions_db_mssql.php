<?php

namespace test_tasks_secure_code\Core\Sessions;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Logs\logs;
use test_tasks_secure_code\Config\config;
use test_tasks_secure_code\Core\router;
use test_tasks_secure_code\Controllers\account;
use test_tasks_secure_code\Core\Sessions\SysSession;

class connect_sessions_db_mssql {
		
	public static function new_connect()
	{
		// create new db connect
		if (!function_exists('sqlsrv_connect')){ 
			
		} else { // for mssql
			
			$lcon = sqlsrv_connect(config::$glocal, SysSession::$connectionInfo);
			if ($lcon) return $lcon; else
			{	
				die( print_r( sqlsrv_errors(), true));
					print "Problem MSSQL";
				return false;
			}

		}
	}

	public static function start_sessions_db_mssql($get_exit)
	{
		/*  create source db
			connect config sql
		*/
		if (router::spl_autoload_register('config'))
		{ 
			if (!empty(config::db_name)
			and !empty(config::db_user)
			and !empty(config::db_pasw)
			and !empty(config::hash_pasw_project())
			and !empty(config::account_from_name)
			and !empty(config::account_gmail_name)
			and !empty(config::account_gmail_pasw))
			SysSession::$connectionInfo = array("UID" => config::db_user,"PWD" => config::db_pasw, "Database" => config::db_name, "CharacterSet" => "UTF-8");
		else logs::client_err_log('config-not-found',true);
		}
		else logs::client_err_log('config-not-found',true);
		
		$handler = new SysSession (); 
		session_set_save_handler ( $handler , true );

		$lnk_db = connect_sessions_db_mssql::new_connect(); // connect to base
		
		config::$lnk_db = $lnk_db;
		
		session_start();

		account::$user_info['login'] = functions::session_connect('sl'); //  login session
		if ($get_exit) functions::exit_system(true);
		account::$user_info['login'] = functions::session_connect('sl'); //  login session

		if (!empty(account::$user_info['login']))
		{ // загрузить информацию о пользователе
			$result = sqlsrv_query(config::$lnk_db,"SELECT [login],[email] FROM [users] where [login]='".account::$user_info['login']."' ",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if (sqlsrv_num_rows($result)<>0) // Пользователь существует
			{

				$res = sqlsrv_fetch_array($result); // result query
				account::$user_info['login'] = $res[0];
				account::$user_info['email'] = $res[1];

			} 
			else
			if (account::$user_info['login']=='admin') // Пользователь существует или 'admin'
			{	
				account::$user_info['login'] = 'admin';
				account::$user_info['email'] = 'admin@test.com'; 
			}
		}
		else
		{  
			account::$user_info['login'] = 'Anonymous';
		}
	
		if ($lnk_db) return true; 
	}

}

?>