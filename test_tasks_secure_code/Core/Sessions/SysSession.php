<?php

namespace test_tasks_secure_code\Core\Sessions;

use SessionHandlerInterface;
use test_tasks_secure_code\Config\config;

class SysSession implements SessionHandlerInterface 
{ 
	private $_sess_db; 
	public static $connectionInfo = '';

	public function open ( $savePath , $sessionName ) 
	{ 
	 global $_sess_db;
	 $_sess_db = sqlsrv_connect(config::$glocal, SysSession::$connectionInfo);
	 if ($_sess_db) return $_sess_db;
	 return false; 
	} 
	
	public function close () 
	{ 
	 global $_sess_db;
	 $_sess_db=null; 
	 return true;
	} 
	
	public function read ( $id ) 
	{ 
	 global $_sess_db;
	 if (!$_sess_db) { return false; exit; }
	 if ($result = sqlsrv_query($_sess_db, "SELECT data FROM [sessions] WHERE id = '$id'",array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))) 
	 {  
	   if (sqlsrv_num_rows($result)) 
		{ 
		 $record = sqlsrv_fetch_array($result);
		 return $record[0]; 
		}  
	 } 
	 return false; 
	} 
	
	public function write ( $id , $data ) 
	{
	 global $_sess_db;
	 $access = time();   
	
	 if ( (!empty($data)) and (!empty($access)) and (!empty($id)) and (!empty($_sess_db)) and ($_sess_db!=false) )
	 {
	  $result = sqlsrv_query($_sess_db," SELECT top 1 id FROM [sessions] WHERE id = '$id' ",array());
	  if ($result !== NULL)  $rows = sqlsrv_has_rows($result);
	  if ($rows)
			$result = sqlsrv_query($_sess_db,"Update [sessions] set [access]='$access',[data]='$data' where id='$id'; ");
	  else  $result = sqlsrv_query($_sess_db,"INSERT INTO [sessions] ([id],[access],[data]) values ('$id',$access,N'$data'); ");
	  return $result;
	 }
	 return false;	  
	} 
	
	public function destroy ( $id ) 
	{ 
	 global $_sess_db;
	 return $result = sqlsrv_query($_sess_db, "DELETE [sessions] WHERE id = '$id' "); 
	} 
	
	public function gc ( $max ) 
	{ 
	 global $_sess_db;
	 $old = time() - $max; 
	 if (!intval($old)) return;
	 //---------------------------
	 return $result = sqlsrv_query($_sess_db, "DELETE [sessions] WHERE access < $old "); 
	} 
}

?>