

var app_tasks_js_controller = {

  login:'Anonymous', // login session tasks
  session_connect:false, // session is not connect
  tab_open:'tasks', 
  menu_open:false, // hidden menu
  tasks_ico_mini_menu_hidden_move:false, // mini menu tasks ico move
  form_now:-1, // form opened now
  edit_process:false, // edit process
  now_p:'', // now hash tasks
  dir_user_tasks_file: '', // directory of user's tasks files
  email:'',
  gl:'', // get login link

  taskId:-1,
  column_sort:'',
  column_sort_ordby:'',
  spage_num:1,

  log_status:false,	// показывать логи в консоль

  log:function(msg)
  {
	if (app_tasks_js_controller.log_status) console.log(msg);
  },

  ready:function()
  { // start app (Main)

	$('body').click(function(){
		app_tasks_js_controller.tasks_ico_hidden_click('mini_menu',false);
	});
	
	app_tasks_js_controller.start_session();	
  },

  start_session:function()
  {
	if (!app_tasks_js_controller.session_connect){
		app_tasks_js_controller.get_ui('start_page'); 			// view default start page
	}
	else
	{
		app_tasks_js_controller.get_ui('general_ui'); 			// view logout
		app_tasks_js_controller.get_ui('start_page'); 			// view default start page
	}
	
  },

  get_ui:function(type_ui, control)
  { // get load page\ui

	/* 
	 control = '' 			-> open standart form
	 control = 'send_query' -> send query ajax event
	 control = 'edit_name' 	-> open from edit name data (menu) 
	 control = 'edit_pasw' 	-> open from edit pasw data (menu) 
	 control = 'exit' 		-> disconnect account 

	 type_ui = 'exit'		-> disconnect account (exit)
	 
	*/

	if (type_ui=='exit')
	{ // disconnect account (exit)
		location.href = '?exit=1';
		return;
	}

	
	if (type_ui=='close_or_open_form')
	{
		if ($('#id_login_form').css('display')!='none')
		{
		 $('#id_login_form').fadeOut('fast');
		 $('.cls_ahref_login1').html('Sign In');
		}
		else
		{
		 app_tasks_js_controller.get_ui('logged_in'); // default
		 //$('#id_login_form').fadeIn('fast');
		 $('.cls_ahref_login1').html('Close');
		}
	    return;
	}

	if (control=='send_query')  // send query ajax event
	{
	 
	let get_url_open = location.href,
		query_prms = '',
		now_cp_other = ''; // key sess

		if (type_ui=='logged_in' || type_ui=='registration')
		{
			let
			name = $('#id_dbr_name').val(),
			email = $('#id_dbr_email').val(),
			pasw = $('#id_dbr_pasw').val(),
			cur_pasw = $('#id_dbr_current_pasw').val();

			if (name.length==0) { alert('Enter your name'); return false; }
			if (email && email.length==0) { alert('Enter your email'); return false; }
			if (getid('id_dbr_pasw') && pasw.length==0) {
				if (app_tasks_js_controller.edit_process)
				{
					if ($('#id_dbr_current_pasw').val()=='')
					alert('Enter your current password');
					else
					alert('Enter your new password');
					
					$('#id_dbr_pasw').focus();
				}
				else
				if (app_tasks_js_controller.form_now=='registration')
					alert('Enter a new password or log in'); 
				else
				{
					alert('Enter your current password'); 
					$('#id_dbr_pasw').focus();
				}
				
				return false; }
				
				if (!getid('id_dbr_email')) email = '';

			query_prms = '&n='+encodeURIComponent(name)+'&e='+encodeURIComponent(email)+'&uo='+get_url_open+'&p='+md5('agdf34fee23'+pasw);

			 // change data
			 if (app_tasks_js_controller.form_now === 'registration' && app_tasks_js_controller.edit_process) // opened form reg. 
			 {
			  if (cur_pasw.length==0) { $('#id_dbr_current_pasw').focus(); alert('Please enter current password for confirm'); return false; }
			  query_prms+= '&cp='+md5('agdf34fee23'+cur_pasw);
			  type_ui = 'registration';
			 }

		}
		else
		if (type_ui=='edit_task_status')
		{				
			query_prms = '&taskId='+app_tasks_js_controller.taskId+'&status_task=1';
		}
		else
		if (type_ui=='send_task')
		{	
			let
			task_descr = $('#id_task_descr_text_task').val();
			let
			task_email = $('#id_task_email_input').val();
			let
			task_name = $('#id_task_name_input').val();
			
			query_prms = '&taskId='+app_tasks_js_controller.taskId;
			query_prms+= '&task_name='+encodeURIComponent(task_name);
			query_prms+= '&task_email='+encodeURIComponent(task_email);
			query_prms+= '&task_descr='+encodeURIComponent(task_descr);
			query_prms+= '&taskiddir='+encodeURIComponent(sr_load_files_images.tmp_taskiddir);
			
			if (task_name.length==0) { $('#id_task_name_input').focus(); alert('Please enter Name'); return false; }
			if (task_email.length==0) { $('#id_task_email_input').focus(); alert('Please enter Email'); return false; }
			if (!validateEmail(task_email)) { $('#id_task_email_input').focus(); alert('Please enter correct Email'); return false; }
			if (task_descr.length==0) { $('#id_task_descr_text_task').focus(); alert('Please enter task description'); return false; }
			
		}
		else
		if (type_ui=='load_tasks')
		{
			if (app_tasks_js_controller.column_sort=='') app_tasks_js_controller.column_sort = 'user_name';
			if (app_tasks_js_controller.column_sort_ordby=='') app_tasks_js_controller.column_sort_ordby = 'asc';
			now_cp_other = '&now_p='+app_tasks_js_controller.now_p+'&column_sort='+app_tasks_js_controller.column_sort+'&column_sort_ordby='+app_tasks_js_controller.column_sort_ordby+'&spage_num='+app_tasks_js_controller.spage_num;	
		}
		else
		if (type_ui=='load_json_stat')
		{
			let get_login = getid('id_link_json_stat_edit_get_login');
			if (get_login && get_login.value.length>0) get_login = get_login.value; else get_login = app_tasks_js_controller.login;
			now_cp_other = '&spak='+app_tasks_js_controller.spak_key+'&get_login='+get_login;	
		}
		else
		if (type_ui=='del_task')
		{
			now_cp_other = '&taskId='+app_tasks_js_controller.taskId+'&dpId='+sr_load_files_images.tmp_taskiddir+'&spak2='+app_tasks_js_controller.spak_key2;	
		}

		$.ajax({ url: https_host+"index.php?ajax_controller=ajaxController&q="+type_ui+query_prms+now_cp_other,   
			cache: false,   
			success: function(html)
			{

			   if (html=='err_msg' && (type_ui=='send_task')) {
			    alert('There is an error in the message.'); 
				return;
			   }
			   
			   if (type_ui=='del_task')
			   {
				app_tasks_js_controller.close_form_task_edit();

				if (html!='successfuly') 
				{
					if (html=='logged_in_not_correctly')
					location.href = '?exit=1&start&gl='+encodeURIComponent(app_tasks_js_controller.login);
					else 
					alert('Error #1. Server response:'+html);
				}
				else app_tasks_js_controller.get_ui('load_tasks','send_query');  // загрузить задачи 
				return;
			   }
			   else
			   if (type_ui=='load_tasks') {
				   //---------------------------------------------
					let split_arr = html.split('|:|'); // 5 prm)
					if (split_arr.length!=5) { return; }
					//--------------------------------------------
					if (split_arr[1]!='-1') 
					{
					 if (type_ui=='load_tasks')
					 {
					  app_tasks_js_controller.now_p=md5(split_arr[0]);
					  $('#id_task_list').html(split_arr[0]);
					  $('#id_task_pagination').html(split_arr[3]);
					  $('#id_count_tasks').html(' ('+split_arr[2]+')');	
					  $('.cls_svg_ico_sort_type').fadeIn('normal');

					  	$('.cls_page_num').unbind('click');
						$('.cls_page_num').click(function(){

							app_tasks_js_controller.spage_num = this.innerHTML;
							now_id_sort_column = 'id_sort_user_name';
							if (app_tasks_js_controller.column_sort=='email') now_id_sort_column = 'id_sort_email';
							if (app_tasks_js_controller.column_sort=='status') now_id_sort_column = 'id_sort_status';
							
							app_tasks_js_controller.click_column_sort(now_id_sort_column,'page_click');
						});
					
					 }
					 else
					 {
					  $('#id_tasks_log').html(split_arr[0]);		
					 }
					 app_tasks_js_controller.resize(type_ui);
					}
					return;
				   //---------------------------------------------
			   }
			   else			   
			   if (type_ui=='load_json_stat')
			   {
				   let json_string = '';
				   if (html!='err_load_json_stat') json_string = JSON.stringify(html);
				   app_tasks_js_controller.change_edit_get_login(json_string);
				   return;
			   }

			   if (app_tasks_js_controller.edit_process)
			   {
				if (html=='ch=1')
				location.reload();
				else
				if (html=='err_ch1.') { alert('Error change password. Please enter correct data.'); }
				else
				if (html=='err_req') { $('#id_dbr_current_pasw').focus(); alert('Error change password. Please enter correct data.'); }
				else
				if (html=='send_task_complete') location.href = https_host+'?start&task=successfully';
				else
				if (html=='Such user already exists') { $('#id_dbr_name').val(app_tasks_js_controller.login); $('#id_dbr_name').focus(); alert('This user or email address already exists'); }
				return;
			   }
			  
			   if (html && html.length>0 && html.indexOf('error_throttling:')!=-1) 
			   {  // THROTTLING
				  let error_throttling = html;
				  if (error_throttling.length>17) // осталось попыток
				  {
					  let ethsec = 60-(error_throttling.substring(17)*1);
					  if (ethsec<1) ethsec = 1; 
					  error_throttling = "\nPlease try again in "+(ethsec)+" seconds";
				  }
				  else error_throttling = '';

				  $('#id_dbr_pasw').val(''); 
				  $('#id_dbr_pasw').focus(); 
				  alert('Too many login attempts, please try again later.'+error_throttling); 
				  return; 
			   }
			   else
			   if (html=='Such user already exists') { $('#id_dbr_name').focus(); alert('This user or email address already exists'); }
			   else
			   if (html && html.length>0 && html.indexOf('error_li:')!=-1) 
			   { 
				  let error_throttling = html;
				  if (error_throttling.length>9) // осталось попыток
				      error_throttling = "\n"+(10-(error_throttling.substring(9)*1))+" attempts left";
					  else error_throttling = '';

				  $('#id_dbr_pasw').val(''); 
				  $('#id_dbr_pasw').focus(); 
				  alert('Error password or login name'+error_throttling); 
				  return; 
			   }
			   else
			   if (html=='Such user already exists:start' 
			   	|| html=='rsuccessfuly'
				|| html=='send_msg_complete')
			   { 
				if (type_ui=='logged_in' || type_ui=='registration') location.href = https_host+'?start&gl='+encodeURIComponent($('#id_dbr_name').val());
			   }
			   else
			   if (html=='err_timeout:?') { alert('You have already created a new task. Please wait a minute.'); }
			   else
			   if (html=='err_req_mail')
			   alert('Please enter your email.');
			   else
			   if (html.indexOf('edit_task_data:')!=-1) {
				   
				if (html=='edit_task_data:task-update')
				{
					if (type_ui=='edit_task_status' 
					&& !$('#id_task_'+app_tasks_js_controller.taskId+'_status').hasClass('cls_task_status_active')) 
						$('#id_task_'+app_tasks_js_controller.taskId+'_status').addClass('cls_task_status_active');
					else
					if (type_ui=='send_task')
					{
						app_tasks_js_controller.close_form_task_edit();
				   		app_tasks_js_controller.get_ui('load_tasks','send_query'); // simple implementation of updating the tasks				   
				  	 	alert('Task changed successfully');
					}
				}
				else 
				if (html=='edit_task_data:task-err')
				alert('Error edit task');
			   }
			   else
			   if (html=='logged_in_not_correctly')
			   {
					location.href = '?exit=1&start&gl='+encodeURIComponent(app_tasks_js_controller.login);
			   }  
			   else
			   if (html=='send_task:task') {
					app_tasks_js_controller.close_form_task_edit();
				    app_tasks_js_controller.get_ui('load_tasks','send_query'); // simple implementation of updating the tasks				   
				    alert('Task added successfully');
				}
			   else
			   if (html=='err taskiddir') { 
				   alert('You also need to upload a photo');
				   $('#id_form_upload_container_images').click();
			   }
			   else
			   if (type_ui=='registration' && html=='err_req') 
			   {
				   alert('Try another login or email');
			   }
			   else
			   if (html=='err_user_name') alert("Please enter correct Name.\nOnly English and Russian alphabets can be used without spaces");
			   else
			   if (html=='err_email') alert('Please enter correct Email');
			   else
			   if (html=='err_descr') alert('Please enter correct Description');
			   else
				alert('error sinh. data? '+html);

			}});
			
			return;
	}
	else
	if (control=='edit') // open from edit data 
	{ }
	else
	{
	 app_tasks_js_controller.edit_process = false;
	}
	
	var gl = app_tasks_js_controller.gl; // login session
	if (typeof gl=='undefined' && getid('id_dbr_name')) gl = encodeURIComponent($('#id_dbr_name').val());
	//----------------------------------------------------
	
	let tasks_form = '';
	if (type_ui=='logged_in')
	{
		app_tasks_js_controller.form_now = 'logged_in';
		//---------------------------------------
		cv_url(https_host+'?login&gl='+gl);
		tasks_form = tasks_forms.get_form_login();
	}
	else
	if (type_ui=='registration') 
	{
		app_tasks_js_controller.form_now = 'registration';
		//---------------------------------------
			
		if (!app_tasks_js_controller.session_connect)
		{
			cv_url(https_host+'?signup');
		}
	
		if (control=='edit_name' || control=='edit_pasw') // open from edit name or edit pasw data 
		{
			app_tasks_js_controller.form_now='registration';
			if (control=='edit_pasw')
			cv_url(https_host+'?edit_pasw=1&gl='+app_tasks_js_controller.login);
			else
			cv_url(https_host+'?'+control);
			app_tasks_js_controller.edit_process = true;
			//---------------------------------------------
		    tasks_form = tasks_forms.get_form_registration(control);
		}
		else // new registration
		tasks_form = tasks_forms.get_form_registration();
	}
	else
	if (type_ui=='start_page')
	{
		app_tasks_js_controller.form_now = 'start_page';
		//---------------------------------------
		if (gl.length>0)
		{
		 cv_url(https_host+'?start');
		
		 app_tasks_js_controller.get_ui('logged_in'); // default
		 
		 if (app_tasks_js_controller.login.length==0 || app_tasks_js_controller.login=='Anonymous')
		 {
			setTimeout(function(){ 

				app_tasks_js_controller.get_ui('logged_in'); // default load form login
				$('.cls_ahref_login1').html('Close');

				$('#id_dbr_name').val(gl);
				$('#id_dbr_pasw').focus();
			
			 },100);

		}
		 
		 $('.cls_ahref_login1').html('Close');
		 $('#id_dbr_name').val(gl);
		
		 if (app_tasks_js_controller.login.length==0)
		 {
		  $('.cls_login_comments').html('Congratulations, you have successfully registered!');
		  $('.cls_login_comments').fadeIn('normal');
		 }
		 else cv_url(https_host);
		}
		
		$('#id_general_ui').html(tasks_forms.get_form_start_page()); // default + (start page, welcome)
		
		if (app_tasks_js_controller.session_connect)
		{
		 $('#id_but_public_api_json_stat').css({'display':'block'}); // view menu
		}
	
		$('#id_tasks_general_ui').html(tasks_forms.get_form_general_ui());
	
		// свернуть меню если не свернуто
		app_tasks_js_controller.tasks_ico_hidden_click('big_menu',false);

		app_tasks_js_controller.buttons_events(); // настройка событий
		//-------------------------------------------------------------------------
		// default open tab
		app_tasks_js_controller.open_tab('id_but_public_tasks', 'id_dinamic_box_tasks');

	}
	
	// start settings
	$('.cls_settings_ico_box').unbind('mouseenter');
	$('.cls_settings_ico_box').mouseenter(function(){
		app_tasks_js_controller.tasks_ico_mini_menu_hidden_move = true;
		}).mouseleave(function(){
		app_tasks_js_controller.tasks_ico_mini_menu_hidden_move = false;
	});

	//--------------------------------------------------------
	if (tasks_form!='') { 
		 $('#id_login_form').html(tasks_form); 
		 $('#id_login_form').fadeIn('fast',function(){ app_tasks_js_controller.start_login_form(type_ui); }); 
	}
	else $('#id_login_form').fadeOut('fast');
	//--------------------------------------------------------
	
  },

  close_form_task_edit:function()
  {
	$('#id_but_send_public_task').html('Add new task');
	app_tasks_js_controller.taskId = -1;
	app_tasks_js_controller.delete_list_images('');
	preview_load_images();
	
	$('#id_task_descr_text_task').val('');
	$('#id_task_email_input').val('');
	$('#id_task_name_input').val('');
  },

  start_login_form:function(type_ui)
  {
	app_tasks_js_controller.dbr_input_events_refresh(type_ui);
  },

  click_column_sort:function(id, page_click) {
	
	$('#'+id).addClass('active');

	if (typeof page_click == 'undefined')
	{
		if ($('#'+id).attr('sort')=='asc')
		{
			app_tasks_js_controller.column_sort_order = 'desc';
			$('#'+id).attr('sort','desc');
		} else
		{
			app_tasks_js_controller.column_sort_order = 'asc';
			$('#'+id).attr('sort','asc');
		}
	}

	app_tasks_js_controller.column_sort = id.replace('id_sort_','');
	app_tasks_js_controller.column_sort_ordby = $('#'+id).attr('sort');
	
	app_tasks_js_controller.get_ui('load_tasks','send_query');
  },

  dbr_input_events_refresh:function(type_ui)
  {
	$('.cls_dbr_input').unbind('keyup');
	$('.cls_dbr_input').keyup(function(event)
	{
		if (event.which==13)
		{
		 if (type_ui=='registration')
		 $('.cls_but_sign_up').click(); // Sign Up
		 else
		 if (type_ui=='logged_in')
		 $('.cls_but_login').click(); // Sign In
		}
	});
  },

  delete_list_images:function(get_taskiddir)
  {
	imgs_n = 0;
	arr_image_files = new Array();
	arr_image_names = new Array();
  
	arr_image_files.splice(0,arr_image_files.length);
	arr_image_names.splice(0,arr_image_names.length);
  },

  change_task_directory:function(new_tmp_puth_user, task_directoryId, taskId)
  {
	  app_tasks_js_controller.taskId = taskId;
	  sr_load_files_images.tmp_puth_user='storage_files/images_of_tasks/'+new_tmp_puth_user+'/';
	  sr_load_files_images.tmp_taskiddir = task_directoryId;
	  load_task_user_for_editing(task_directoryId);
	  
  	  $('html').animate({scrollTop:75}, 100);
  },
  
  comleted_task_directory:function(new_tmp_puth_user, task_directoryId, taskId)
  {
	  app_tasks_js_controller.taskId = taskId;
	  sr_load_files_images.tmp_puth_user='storage_files/images_of_tasks/'+new_tmp_puth_user+'/';
	  sr_load_files_images.tmp_taskiddir = task_directoryId;
	  app_tasks_js_controller.get_ui('edit_task_status','send_query');
  },

  delete_task_directory:function(new_tmp_puth_user, task_directoryId, taskId)
  {
	  if (!confirm('Confirm deletion?')) return;
	  
	  app_tasks_js_controller.taskId = taskId;
	  sr_load_files_images.tmp_puth_user='storage_files/images_of_tasks/'+new_tmp_puth_user+'/';
	  sr_load_files_images.tmp_taskiddir = task_directoryId;
	  app_tasks_js_controller.get_ui('del_task','send_query');
  },

  change_edit_get_login:function(json_string)
  {// GET JSON STATISTICS
	let get_login = getid('id_link_json_stat_edit_get_login');
	if (get_login && get_login.value.length>0) get_login = get_login.value; else get_login = app_tasks_js_controller.login;
	let now_cp_other = '&spak='+app_tasks_js_controller.spak_key+'&get_login='+get_login;	
	let link_json_stat_href = https_host+"index.php?ajax_controller=ajaxController&q=load_json_stat"+now_cp_other;
	$('#id_link_json_stat').attr('href',link_json_stat_href);	
	$('#id_link_json_stat').attr('target','_blank');	
	$('#id_link_json_stat_edit').val(link_json_stat_href);
	
	$('#id_link_json_stat_result').html(json_string);	
  },
  
  buttons_events:function() // настройка событий по клику для меню функционала
  {
	// click general buttons
	$('.cls_but_menu').click(function(){
		if (this.id=='id_but_public_tasks') // публичные задачи
		app_tasks_js_controller.open_tab(this.id, 'id_dinamic_box_tasks');
		else
		if (this.id=='id_but_public_api_json_stat') // публичная настраиваемая статистика 
		app_tasks_js_controller.open_tab(this.id, 'id_dinamic_box_add_task');		
	});
  },
  
  open_tab:function(id, id_tab_box)
  {
	$('.cls_but_menu').removeClass('active'); // удалить фокус
	$('.cdimb').removeClass('active');	 	  // удалить фокус
	$('.cls_link_json_stat_tab').removeClass('active'); // удалить фокус
	//---------
	$('#'+id).addClass('active'); 			  // зафиксировать закладку

	if (id=='id_but_public_api_json_stat') 
	{// загрузить статистику по пользователю из списка
		//------------------------------------------------ 
		if (app_tasks_js_controller.tab_open!='statistics')
	 	app_tasks_js_controller.get_ui('load_json_stat','send_query');
		//------------------------------------------------ 
		$('.cls_link_json_stat_tab').addClass('active'); // показать окно настроек запроса json статистики

		//------------------------------------------------ 
	 	app_tasks_js_controller.tab_open = 'statistics';
		//------------------------------------------------ 
	 return;
	}

	$('#'+id_tab_box).addClass('active');	

	if (id=='id_but_public_tasks')
	{
		//------------------------------------------------ 
		if (app_tasks_js_controller.tab_open=='tasks') 
		{
			app_tasks_js_controller.get_ui('load_tasks','send_query');
		
			try
			{ 
				fileUpload('images'); // найстройка загрузки фото файлов для задач	
				$('#id_dinamic_box_tasks').addClass('active');	 	
				
			}
			catch(e) { 	
			alert('Error tasks fileUpload().'); 	
			}
		}

		$('#id_dinamic_box_add_task').addClass('active');	 
	
	 	app_tasks_js_controller.tab_open = 'tasks';
	}

	app_tasks_js_controller.resize('open_tab',id);
  },

  click:function()
  {
	alert('click href open functional');

  },

  change_ses_data:function()
  {
	app_tasks_js_controller.get_ui('registration','send_query');
  },

  tasks_ico_hidden_click:function(type_obj, concr_view)
  {
	
	if (type_obj=='mini_menu')
	{
		setTimeout(function(){
			
			if (typeof concr_view != 'undefined' && app_tasks_js_controller.tasks_ico_mini_menu_hidden_move) return;
			
			if ($('.cls_mini_menu').css('display')!='none' 
			|| (typeof concr_view != 'undefined' && !concr_view))
			{ // hidden
			 $('.cls_mini_menu').css({'display':'none'});
			}
			else
			{ // view
			 $('.cls_mini_menu').css({'display':'inline-block'});
			}

		},100);

		return;
	}

	if (app_tasks_js_controller.menu_open || (type_obj=='big_menu' && !concr_view))
	{
		app_tasks_js_controller.menu_open = false; // hidden menu
		$('.cls_dinamic_box_menu').css({'display':'none'});
		$('.cls_dbr_menu_name').fadeOut('fast');
		$('.cls_tasks_ico_hidden').css({'display':'none','float':'left','margin-left':'10px'});
		$('.cls_tasks_ico_hidden').removeClass('posright');
		$('.cls_tasks_ico_hidden').addClass('posleft');
	}
	else
	{
		app_tasks_js_controller.menu_open = true; // view menu

		$('.cls_dinamic_box_menu').css({'display':'none'});
		$('.cls_tasks_ico_hidden').css({'display':'inline-block','float':'right','margin-left':'auto'});
		$('.cls_tasks_ico_hidden').removeClass('posleft');
		$('.cls_tasks_ico_hidden').addClass('posright');

	}
	
  },

	resize:function(open_tab,id){

		callback_onresize(open_tab);
		if ((open_tab=='open_tab' && id=='id_but_public_tasks') 
		 || (open_tab=='global_resize' && app_tasks_js_controller.tab_open=='tasks'))
		{
		 if (app_tasks_js_controller.session_connect)
		 $('#id_dinamic_box_add_task').addClass('active');	 		 
		}
		
	}
  

}

window.addEventListener("resize", correct_resize_route, false);
var restimebreak;

function correct_resize_route() {
	// ignore resize events as long as an correct_resize execution is in the queue
	if ( !restimebreak ) {
	  restimebreak = setTimeout(function() {
		restimebreak = null;
        //----------------------
		callback_onresize();
	   // The correct_resize will execute at a rate of 15fps
	   }, 66);
	}
}

// change page functional
var onresize_flag = false, real_screenH = -1, real_screenW = -1;
function callback_onresize(open_tab)
{
   real_screenW = screenSize().w;
   real_screenH = screenSize().h;

   if (open_tab!='global_resize')
   app_tasks_js_controller.resize('global_resize');
}