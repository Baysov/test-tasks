<?php

namespace test_tasks_secure_code\Libs;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Config\config;
use test_tasks_secure_code\Controllers\account;

class upload_files {

	public static function upload_files_validate_file($kf, $puth_loadf, $taskiddir, $ff)
	{		
		$kf_ok = config::kf();
		$d_puth_up_files = 'storage_files/images_of_tasks';
		if ($kf_ok==$kf 
		and !empty($puth_loadf) 
		and substr($puth_loadf,0,strlen($d_puth_up_files))==$d_puth_up_files) // catalog correct
		{
			$upload_container = 'images';
			$upload_container_in=$upload_container;	
			$re_unload_status = 'false';

			if (empty($taskiddir) or $taskiddir=='-1') $taskiddir = date("dmYHis"); // в качестве примера, id = текущее время создания записи является идентификатором задачи уникального пользователя (оно будет возвращено для использования, рекомендуется хранение параметра в бд.)

			$ff_ok = md5(date("m-Y-d-d").account::$user_info['login']."B%$TBDFBkyup#@@G#WERB".$kf_ok.$puth_loadf);
			if (!empty($ff) and $ff==$ff_ok
			and !empty($taskiddir) 
			and strlen($taskiddir)==14) // taskiddir - id task
			{ // key correct
				
				function reArrayFiles(&$file_task) {

					$file_ary = array();
					$file_count = count($file_task['name']);
					$file_keys = array_keys($file_task);

					for ($i=0; $i<$file_count; $i++) {
						foreach ($file_keys as $key) {
							$file_ary[$i][$key] = $file_task[$key][$i];
						}
					}

					return $file_ary;
				}

				if (!empty($_FILES['Filedata_'.$upload_container])) {
					$file_ary = reArrayFiles($_FILES['Filedata_'.$upload_container]);
					
					foreach ($file_ary as $file) {

						$tempFile = $file['tmp_name'];
			
						$targetPath = $_SERVER['DOCUMENT_ROOT'] . functions::rhost.'/'. $puth_loadf . '/'. $taskiddir. '/'; // ! Важно, в случае если проект лежит не в корне сайта
						if (!is_dir($targetPath)) mkdir($targetPath); // создать директорию для будущей задачи и скопировать туда фото файлы

						$filename = $file['name']; // filename (in)
						$filename = urldecode($filename);
						//-------------------------------------------
						$x = strrpos($filename,'.');
						$format = substr($filename,$x);					
						$filename = md5($file['name']).$format; // filename (in md5 hash)
						//-------------------------------------------
						$targetFile = str_replace('//','/',$targetPath);
						$targetFile = $targetFile.$filename;
						// Uncomment the following line if you want to make the directory if it doesn't exist
						// mkdir(str_replace('//','/',$targetPath), 0755, true);

						$filename_uplist.= '"'.md5(functions::translite($file['name'])).'":"'.$filename.'",';	
						move_uploaded_file($tempFile, $targetFile);
					}

					if (!empty($filename_uplist))
					$filename_uplist = substr($filename_uplist,0,strlen($filename_uplist)-1);
					$re_unload_status = 'true'; // ok
				}
			}

			$styles = '<style type="text/css">
			body {background-color:#fff; overflow-x:hidden; overflow-y:auto; }
			.cls_input_textarea { width:488px; height:50px; border:1px solid #ccc; padding:5px; border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px; resize:none;  }
			.cls_line { display:inline-table; width:50%; min-width:250px; padding:3px; color:#000; max-height:20px; border-bottom: 1px solid #f5f5f5; margin-top:5px; }
			.cls_fname { float:left; display:inline-block; height:auto; width:250px; color:#6a0096; font-weight:bold; padding:2px; font-size:10px; }
			.cls_fname_err { color:#f00; font-size:10px; }
			.cls_lnk_sr { display:inline-block; width:100%; color:#32b949; font-size:12px; font-weight:bold; margin-top:10px; }
			#TB_ajaxWTX { font-size:12px; }
			.cls_dialog_upload { display:none; position: fixed; z-index: 99999998; left:0px; top:0px; color:#fff; background-color:rgba(0,0,0,0.8); width:100%; height:100%; text-align:center; font-size:12px; padding:5px; }
			.cls_but_load_file
			{
				cursor:pointer; font-weight:300; background-color:#f3b06f; color:#000 !important; width:300px; text-align:center; font-size:12px; padding:7px; border:1px outset #eab305; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; 
			background-image:-webkit-gradient(linear,left top,left bottom,from(#ffc800),to(#ffbc42)); background-image:-webkit-linear-gradient(top,#ffc800,#ffbc42); background-image:linear-gradient(to bottom,#ffc800,#ffbc42);
				-webkit-transition:background-color 0.5s;
				-moz-transition:background-color 0.5s;
				transition:background-color 0.5s;  
			}
			.cls_but_load_file:hover { color:#333 !important; border:1px outset #fff; }
			.cls_close { position:absolute; left:100%; margin-left:-80px; width:70px; cursor:pointer; padding:5px; }

			.scroll_slider::-webkit-scrollbar {
			width: 10px;
			height: 15px;
			}
			.scroll_slider::-webkit-scrollbar-button {
			width: 20%;
			height: 0px;
			}
			.scroll_slider::-webkit-scrollbar-thumb {
			background: #FF9800;
			border: 0px none #ffffff;
			}
			.scroll_slider::-webkit-scrollbar-thumb:hover {
			background: #FF9800;
			}
			.scroll_slider::-webkit-scrollbar-thumb:active {
			background: #FF9800;
			}
			.scroll_slider::-webkit-scrollbar-track {
			background: #666666;
			border: 0px none #ffffff;
			}
			.scroll_slider::-webkit-scrollbar-track:hover {
			background: #666666;
			}
			.scroll_slider::-webkit-scrollbar-track:active {
			background: #333333;
			}
			.scroll_slider::-webkit-scrollbar-corner {
			background: transparent;
			}

			.cls_submit { float:right; height:20px; cursor:pointer; }

			</style>';

			$js_script = '<script>window.onload=function(){parent.unload_onload(\''.$upload_container.'\',\''.$re_unload_status.'\',\'{'.$filename_uplist.'}\',\''.$taskiddir.'\');};</script>';

			print '<!DOCTYPE html><head><meta http-equiv="content-type" content="text/html; charset=utf-8">'.$styles."\n".$js_script."\n".'</head>'.
			'<body class="scroll_slider"><button class="cls_close cls_but_load_file" id="id_but_close_form_'.$upload_container.'" onClick="parent.close_upload_editor(\''.$upload_container.'\');">Close</button>* The list is reloaded with every new file selection<br><form action="index.php?ajax_controller=upload_files&kf='.$kf_ok.'&upload_container='.$upload_container.'&folder='.$puth_loadf.'&taskiddir='.$taskiddir.'&ff='.$ff_ok.'" name="form_upload_'.$upload_container.'" id="id_form_uploadr_'.$upload_container.'" enctype="multipart/form-data" method="post">
				<input id="file_upload_'.$upload_container.'_input" type="file" name="Filedata_'.$upload_container.'[]" multiple="multiple" accept="'.$upload_container_in.'/*" onchange="javascript:parent.updateList(\''.$upload_container.'\')">
				<div id="file_upload_'.$upload_container.'_files" class="cls_unselected"></div>
				<input type="submit" class="cls_black" onClick="javascript:if(!parent.send_files_on_serv(\''.$upload_container.'\',\'file_upload_'.$upload_container.'_input\',\'file_upload_'.$upload_container.'_files\'))return false;" value="Attach selected files">
			</form></body></html>';		  
			exit;
		}
		else { print 'upload_files_in_not_correctly'; exit; }

	}

}

?>