

var tasks_forms = 
{
    
    // open login form
    get_form_login:function(){ 
        return `<div id="id_dinamic_box_login" class="cls_dinamic_box">
        <div class="cls_login_comments"></div>
        <div class="cls_dbr_name">Name</div>
        <input class="cls_dbr_input" id="id_dbr_name" maxlength="50" placeholder="Please enter your name or email">
        <br>
        <div class="cls_dbr_name">Password</div>
        <input class="cls_dbr_input" id="id_dbr_pasw" maxlength="255" placeholder="Please enter your password" type="password">
        <br>
        <div class="cls_dbr_name"><a onClick="app_tasks_js_controller.get_ui('registration');" class="cls_ahref_login">Sign Up</a></div>
        <div onClick="app_tasks_js_controller.get_ui('logged_in','send_query');" class="cls_but_login">Sign In</div>
        </div>`;
    },
 
    // open registration form
    get_form_registration:function(type){ 
     let current_pasw = '', new_pasw = '', 
     login_link = `<br>
     <div class="cls_dbr_name"><a onClick="app_tasks_js_controller.get_ui('logged_in');" class="cls_ahref_login">Sign In</a></div>`, 
     reg_or_change_reg = `<div onClick="app_tasks_js_controller.get_ui('registration','send_query');" class="cls_but_sign_up">Sign Up</div>`,
     current_email = `<br>
     <div class="cls_dbr_name">Email</div>
     <input class="cls_dbr_input" id="id_dbr_email" placeholder="Please enter your email" value="${app_tasks_js_controller.email}">`;
     //-------------------------------------------------------------------
     if (type=='edit_name' || type=='edit_pasw')
     {
        login_link = '';
        current_pasw = `<br>
     <div class="cls_dbr_name">Current Password</div>
     <input class="cls_dbr_input" id="id_dbr_current_pasw" placeholder="Please enter your current password" value="" type="password">`;
        if (type=='edit_pasw') // edit pasw data (menu) 
        {
            current_email = '';
            new_pasw = `<br>
            <div class="cls_dbr_name">New Password</div>
            <input class="cls_dbr_input" id="id_dbr_pasw" placeholder="Please enter your new password" value="" type="password">`;
        }
        
        reg_or_change_reg = `
        <div class="cls_buts_sign_up">
         <div class="cls_but_sign_up_change_close" onclick="app_tasks_js_controller.get_ui('close_or_open_form');">Close</div>
         <div class="cls_but_sign_up_change_save" onclick="app_tasks_js_controller.change_ses_data();">Save</div>
        </div>`;
     }
     else // new pasw
        new_pasw = `<br>
     <div class="cls_dbr_name">Password</div>
     <input class="cls_dbr_input" id="id_dbr_pasw" placeholder="Please enter your password" value="" type="password">`;

     let dbr_name_value = '';
     if (app_tasks_js_controller.login.length>0) 
         dbr_name_value = app_tasks_js_controller.login[0].toUpperCase()+app_tasks_js_controller.login.substring(1);
     let dbr_name = `<input class="cls_dbr_input" id="id_dbr_name" placeholder="Please enter your name" value="${dbr_name_value}"></input>`;

     return `<div id="id_dinamic_box_registration" class="cls_dinamic_box">
        <div class="cls_dbr_name">Name</div>
        ${dbr_name}   
        ${current_email}
        ${current_pasw}
        ${new_pasw}
        ${login_link}
        ${reg_or_change_reg}
        </div>`;
    },

    // open the menu (if logged in and there is session data)
    get_form_menu:function(){ 
        return `<div id="id_dinamic_box_menu" class="cls_dinamic_box_menu">
        <div class="cls_tasks_ico_hidden" onclick="app_tasks_js_controller.tasks_ico_hidden_click();"></div>
        
        <br>

        <div onClick="app_tasks_js_controller.get_ui('registration','edit_name');" class="cls_but_alnk" title="edit current name or email">
        <div class="cls_tasks_ico cls_tasks_ico_edit_name"></div><div class="cls_dbr_menu_name">Edit name/email</div></div>
        
        <div onClick="app_tasks_js_controller.get_ui('registration','edit_pasw');" class="cls_but_alnk" title="edit current password">
        <div class="cls_tasks_ico cls_tasks_ico_edit_psw"></div><div class="cls_dbr_menu_name">Edit password</div></div>
        </div>`;
    },        

    // general ui
    get_form_general_ui:function(){ 
        let tasks_login = '';
        if (app_tasks_js_controller.login.length>0)
            tasks_login = app_tasks_js_controller.login[0].toUpperCase()+app_tasks_js_controller.login.substring(1);

        let logged_box = `<div class="cls_logged_box"><span style="font-size:10px; color:#afafaf;">Logged in:</span><br>${tasks_login}</div>
        <div class="cls_settings_ico_box" onclick="app_tasks_js_controller.tasks_ico_hidden_click('mini_menu');"><div class="cls_settings_ico"></div></div>
        <div class="cls_mini_menu" id="id_mini_menu">
         <a onClick="app_tasks_js_controller.get_ui('exit');" class="cls_ahref" title="logout">Logout</a>
        </div>`;
        if (tasks_login.length==0 || app_tasks_js_controller.login=='Anonymous') 
            logged_box = `<a onclick="app_tasks_js_controller.get_ui('close_or_open_form');" class="cls_ahref_login1">Sign In</a>`;

        return `<div id="id_dinamic_box_general_ui" class="cls_dinamic_box">${logged_box}</div>`;
    },
    
    // load start page
    get_form_start_page:function(){ 
        
        let date = new Date();
        let tchs = date.getHours(); // Kyiv time
        let welc_variant = '';
        if (tchs<0) tchs = 24;

        if (tchs>6 && tchs<12) 
        welc_variant = 'Good Morning';
        else
        if (tchs>=12 && tchs<18) 
        welc_variant = 'Good Day';
        else
        if (tchs>=18 && tchs<24) 
        welc_variant = 'Good Evening';
        else 
        welc_variant = 'Good Night';
        
        let welcome_text = welc_variant;
        if (app_tasks_js_controller.login.length>0)
        {
          let tmp_tasks_login = app_tasks_js_controller.login[0].toUpperCase()+app_tasks_js_controller.login.substring(1);
          welcome_text = welc_variant+`, ${tmp_tasks_login}!`;
        }
        return `<div id="id_dinamic_box_start_page" class="cls_dinamic_box cls_box_start_page">
        <div class="cls_header"> 
         <div class="cls_welc_text">${welcome_text}</div>
        </div>
        ${tasks_forms.get_app_taskss_forms_load()}
        </div>`;
    },

    // refresh app content
    get_app_taskss_forms_load:function(){ 

        let plogin = app_tasks_js_controller.login;
        return `
        <div class="cls_buttons_line">
         <div class="cls_but_menu" id="id_but_public_tasks">Tasks <span id="id_count_tasks"></span></div>
         <div class="cls_but_menu" id="id_but_public_api_json_stat">Get Api json (custom statistics)</div>
        </div>

        <div class="cls_tasks_tab">
            <div id="id_dinamic_box_add_task" class="cls_dinamic_box cdimb">
                <span>To publish a task, upload a photo and write a descriptions</span>
                <br>
                <div class="cls_box_content" id="id_task_image_container">
                
                 <div id="id_form_upload_container_images" class="cls_but_load_file">Photo file upload editor...</div>

                 <div id="loadTips_images"></div>
                 <div id="id_prew_images" style="width:90%;">Currently uploaded 0 photos</div>
                            
                </div>
                    
                <div id="id_task_descr">                
                    <span><span style="color:#f00;">*</span> Name:</span>
                    <br>
                    <input class="cls_name_input" id="id_task_name_input" maxlength="50" placeholder="Please enter Name">    
                    <br>
                    <span><span style="color:#f00;">*</span> Email:</span>
                    <br>
                    <input class="cls_email_input" id="id_task_email_input" maxlength="255" placeholder="Please enter Email">    
                    <br>
                    <span><span style="color:#f00;">*</span> Task description (or name):</span>
                    <br>
                    <textarea class="cls_descr_textarea" id="id_task_descr_text_task" maxlength="255" placeholder="Please enter task description"></textarea>
                    <br>
                    <div class="cls_max_w">
                    <div class="cls_but_send_query" onClick="app_tasks_js_controller.get_ui('send_task','send_query');" id="id_but_send_public_task">Add new task</div>
                    </div>       
                </div>
            </div>
            <div id="id_dinamic_box_tasks" class="cls_dinamic_box cdimb">               
                <div class="cls_box_content" id="id_task_list"></div>             
                <div class="row" id="id_task_pagination"></div>
            </div>
        </div>

        <div class="cls_link_json_stat_tab">
         <div class="cls_comment1">Enter the login of the registered user to get detailed statistics:</div> <input class="cls_edit_link_json_stat_getl" id="id_link_json_stat_edit_get_login" value=\"${plogin}\" onkeyup="app_tasks_js_controller.change_edit_get_login();">
         <div style="width:100%;">
         <a id="id_link_json_stat" target=\"_blank\"><input class="cls_edit_link_json_stat" id="id_link_json_stat_edit" readonly="readonly" value="load link json statistics user.."></a>
         <div id="id_link_json_stat_result" style="max-width:80vw; overflow:auto;"></div>
         </div>
         <br>
         <div class="cls_but_send_query" onClick="getid('id_but_public_api_json_stat').click();getid('id_link_json_stat').click();" id="id_but_open_link_json_stat">Open link in new tab</div>
        </div>
        `;
    }
    
   

}