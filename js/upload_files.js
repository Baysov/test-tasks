// upload photos

var tmp_puth_user = '-1', 
    tmp_taskiddir = '-1', 
    chk_file_k = '-1';

var arr_image_names = new Array(), arr_image_files = new Array(), imgs_n = 0,  prev_imgs = '';

function file_wh_size_image(id_img)
{
    var obj_img_tmp = getid(id_img);
	if (obj_img_tmp)
	{
	//----------------------
    var height_standart = 150, img_sm_icoW = 0;
	var width = prop_imgW(obj_img_tmp, height_standart);
    if (width>325) img_sm_icoW = 325; else img_sm_icoW = width;
    //----------------------
	obj_img_tmp.width = img_sm_icoW;
	obj_img_tmp.height = height_standart;
    }
	//------------------- 
}

function arr_delete_file_image(filename)
{
 var tmp_array1 = new Array();
 var tmp_array2 = new Array();
 //-------------------------------
 index_del = file_found_image(filename);
 var i=0, j=0;
 while (i<arr_image_names.length)
 {
  if (i!=index_del)
  {
   tmp_array1[j] = arr_image_files[i];
   //-------------------------------------------------
   if (tmp_array2[j]==undefined) tmp_array2[j] = new Array();
   tmp_array2[j] = arr_image_names[i];
   //-------------------------------------------------
   j++;
  }
  else
  {
   imgs_n--;
  }
  //-------
  i++;
 }
 //--------
 if (typeof arr_image_files!='undefined') { delete arr_image_files; }
 if (typeof arr_image_names!='undefined') { delete arr_image_names; }
 //--------
 arr_image_files = new Array();
 arr_image_names = new Array();
 //--------
 arr_image_files = tmp_array1;
 arr_image_names = tmp_array2;
 //--------
 if (arr_image_names.length==arr_image_files.length && arr_image_files.length==imgs_n) 
 {
  preview_load_images();
  return true; 
 }
 else return false;
 //----------------
}

// УДАЛЕНИЕ ФОТО
function delete_photo(filename, index_photo, realfilename)
{
   //-----------

   var skz = app_tasks_js_controller.skz_key;
   //----------------------------------
   $.ajax({ url: "index.php?ajax_controller=chk_file&filename="+encodeURIComponent(realfilename)+"&chk_file_k="+chk_file_k+"&sys=v2&skz="+skz+'&lmd='+md5(app_tasks_js_controller.login),   
                cache: false,   
                success: function(html)
				{
				 //-------------------------------
				 if (html=='true') 
				  {
				   var obj = getid("id_imgvX"+index_photo);
           if (obj.parentNode)
					 {
					  obj.parentNode.removeChild(obj);
            obj = getid("id_imgvX"+index_photo);
            //------------------
					  if (!arr_delete_file_image(filename))  
					  alert('Invalid indexes => photo left:' + arr_image_names.length + '; imgs_n = '+ imgs_n +'. The indices must be the same. Please contact the developer or your manager. ');
					  //------------------
					 }
				    
				  }
				  //------------------
				}   
         });  
   
}

function checked_cgp_image(id)
{
 var obj = getid(id);
 if (obj) 
  {
   index_del = file_found_image(obj.value);
   if (index_del!=-1) index_del = index_del+1;
   gpIndex = index_del;
   gpIndex_md5 = id.substring(7,id.length);
  }
}

var gpIndex = 1, gpIndex_md5 = '';
function preview_load_images()
{
 //-------------------
 var obj_pli = getid('id_prew_images');
 var obj_pli_info = getid('loadTips_images');
 //-----------
 var items_count = 0;
 //-----------
 if (obj_pli && obj_pli_info)
  {
   var i=0; prev_imgs = '';
   while (i<arr_image_names.length)
   {
    var rand = Math.random(), id_imgw = "id_view_img"+i, sel = '';
    //--------------------
    if (i==0 && gpIndex==1) sel = "checked=\"checked\""; else sel = "";
    var b = 0, options_var = "", sel_var = '', max_options = 1;
    //-------------------
    while (b<=max_options)
    {
    //------------------------------
    if (b==arr_image_names[i]['own']) sel_var = " selected=\"selected\" "; else sel_var = "";
    //------------------------------
    if (b==0) options_var = options_var + "<option value=\"0\" "+sel_var+" style=\"color:green\">task Photo</option>";
    //------------------------------
    b++;
    }
    
    //-------------------
    var disabled_obl = '';
    if (arr_image_names[i]['url'].indexOf('public_tasks')!=-1) disabled_obl = " disabled=\"disabled\" ";
    //-------------------
    items_count++;
    
    var img_box_cover = "<div id=\"id_imgv_posXY-"+id_imgw+"\" style=\"width: 325px; height: 215px; background: url("+arr_image_names[i]['real_url']+"?rand="+rand+") 0% 0% / cover no-repeat;\"></div>";

    // <label><input name=\"chk_gp\" onClick=\"javascript:checked_cgp_image(this.id)\" disabled=\"disabled\"  id=\"chk_cgp"+md5(arr_image_names[i]['url'])+"\" value=\""+arr_image_names[i]['url']+"\" "+sel+disabled_obl+" type=\"radio\">cover</label> | 
    prev_imgs = prev_imgs + "<div id=\"id_imgvX"+items_count+"\" style=\"float:left; width:325px; margin:5px; height:auto; border: 1px dashed #CCCCCC;\" align=\"left\"><div style=\"width:325px; height:215px; overflow:hidden;\" align=\"center\">Photo "+items_count+"<br>"+img_box_cover+"</div><div style=\"width:325px; padding:10px;\"><div><a href=\"javascript:delete_photo('"+arr_image_names[i]['url']+"',"+items_count+",'"+arr_image_names[i]['real_url']+"');\" class=\"cls_del_ph\">delete</a></div><div style=\"display:none; padding-top:5px;\"><select style=\"width:300px;\" id=\"id_own_photo-"+id_imgw+"\">"+options_var+"</select></div><div style=\"display:none; width:100%; margin-top:5px;\">Photo signature (max. 255 characters)<br><textarea id=\""+id_imgw+"_title\" readonly=\"readonly\" type=\"text\" cols=\"10\" rows=\"3\" style=\"width:300px; height:50px; background-Color:#ccc; resize:none;\" maxlength=\"255\">"+arr_image_names[i]['descr']+"</textarea></div></div></div>";
    //-------------------
    i++;
    //-------------------
   }
   obj_pli.innerHTML = prev_imgs;
   obj_pli_info.innerHTML = "Attached images: "+items_count;
   //-- select general photo
   var obj_chk_genereal = getid('chk_cgp'+gpIndex_md5);
   if (obj_chk_genereal) obj_chk_genereal.checked = true;
   //----------
  }
 //================================= 
}

function cfu_create(type_files, script)
{  
    if (!getid('id_uplc_'+type_files)) { 
     var dom_new_id = document.createElement("div"); 
     document.body.appendChild(dom_new_id);
     dom_new_id.id='id_uplc_'+type_files;
    }
    if (sr_load_files_images.tmp_puth_user=='')
    { alert('error settings tmp_puth_user'); return; }

    let frame_upload_dir = https_host+script+"&upload_container="+type_files+"&folder="+sr_load_files_images.tmp_puth_user+"&taskiddir="+sr_load_files_images.tmp_taskiddir;
    
    $('#id_uplc_'+type_files).html("<iframe src=\""+frame_upload_dir+"\" name=\"iframe_upload_files_"+type_files+"\" id=\"id_iframe_upload_files_"+type_files+"\" width=\"70%\" height=\"80%\" style=\"margin-top:3%;\" class=\"cls_unselected\" scrolling=\"auto\" frameborder=\"0\" id=\"id_iufi_"+type_files+"\"></iframe>");
    $('#id_uplc_'+type_files).addClass('cls_dialog_upload');
}

function create_form_upload(type_files)
{

  var form_upload = {recstartI:0,images:'1'};
    
  if (type_files=='images')  // создать объекты загрузки
    cfu_create(type_files, sr_load_files_images.script);
  
  $('.cls_but_load_file').unbind('click');
  $('.cls_but_load_file').click(function(){

    function recstart_load(type_files)
    {
    
      var obj = frames['iframe_upload_files_'+type_files].document.getElementById('file_upload_'+type_files+'_input');
      if (obj) 
      {
        if (frames['iframe_upload_files_'+type_files].document.getElementById('file_upload_'+type_files+'_files').innerHTML=='') // если еще не были выбраны файлы, тогда сделать выбор
        obj.click();
      } else 
      {
        if (form_upload.recstartI>10) return;
        form_upload.recstartI++;
        setTimeout(function(){recstart_load(type_files);});
      }

    }
    
    if (this.id=='id_form_upload_container_'+type_files)
    {
     $('#id_uplc_'+type_files).fadeIn('normal');
     setTimeout(function(){
      form_upload.recstartI=0;
      recstart_load(type_files);
     },1000);
    }

   });

}

// проверка существования файлов изображений
function checkfile(index, filename, fname, file_user_name) {
  //----------------------------------		
  try
  {
   $.ajax({ url: "index.php?ajax_controller=chk_file&filename="+encodeURIComponent(filename)+"&chk_file_k="+chk_file_k+"&sys=v1&lmd="+md5(app_tasks_js_controller.login),   
                cache: false,   
                success: function(html)
				{
           try {
              if (html=='true')
              {
                //-----------------------------
                if (arr_image_names[imgs_n]==undefined) arr_image_names[imgs_n] = new Array();
                arr_image_names[imgs_n]['url'] = file_user_name.replace('%','{prc}'); // filename upload
                arr_image_names[imgs_n]['real_url'] = filename.replace('%','{prc}');  // filename real
                //---------------------------------------------
                arr_image_names[imgs_n]['descr'] = fname;
                //---------------------------------------------
                arr_image_names[imgs_n]['own'] = '';
                //---------------------------------------------
                  imgs_n++;
                  arr_image_files[index] = true;
                //-----------------------------
              }
              else 
              {
                alert('photo is not uploaded to the server: '+filename+'\nServer response: ['+html+']');
                arr_image_files[index] = false;
              }
              //-----------------------------------
              preview_load_images();
          }   
          catch(e)
          {
           alert('try except fileUpload (checkfile 1) e.msg='+e.message);
          }		
				  //-----------------------------------
			    }});  
    
    }   
    catch(e)
    {
     alert('try except fileUpload (checkfile 2) e.msg='+e.message);
    }		
  //-----------------------------------------
}

//find filename in mas IMAGE ФОТО
function file_found_image(filename)
{
 var i=0;
 while (i<arr_image_names.length)
 {
  if (arr_image_names[i]['url'] == filename) { return i/*+1*/; break; }
  i++;
 }		
 return -1;
}

// массивы параметров файловых данных для загрузки
var sr_load_files_images = {}, 
    filelist = {},
    qId_list_del = {};

const fileTypes = {
        images:[ // jpg, jpef, png, gif
          "image/apng",
          "image/gif",
          "image/jpeg",
          "image/pjpeg",
          "image/png",
        ]};

function validFileType(file,object_type) {
  if (object_type=='images')
  return fileTypes.images.includes(file.type);
}

//var index_found_file = 0;
function updateList(object_type, remove_qId) {
    
    if (!frames['iframe_upload_files_'+object_type]) return false;
     
    let input, output, inputId, outputId = '';
    filelist[object_type] = new Array();
    if (object_type=='images')
    {
     inputId = 'file_upload_images_input';
     outputId = 'file_upload_images_files';
     tmp_puth_user = sr_load_files_images.tmp_puth_user;
     tmp_taskiddir = sr_load_files_images.tmp_taskiddir;
    }

    input=frames['iframe_upload_files_'+object_type].document.getElementById(inputId);
    output=frames['iframe_upload_files_'+object_type].document.getElementById(outputId);
    
    if (remove_qId && remove_qId.length>0)
    {// удалить файл из списка загружаемых

      const dt = new DataTransfer();
      
      for(const file of input.files) {
       let qId_tmp = md5(object_type+tmp_puth_user+tmp_taskiddir+'/'+file.name);
       if (remove_qId=='all_incorrect' || (remove_qId!='all_incorrect' && qId_tmp!=remove_qId)) 
           dt.items.add(file); // добавить в список как разрешенный, так как проблемы с файлом не обнаружены            
      }
  
      input.files = dt.files; // новый список  
    }

    var fl_html = '';
    for(const file of input.files) {
        if(validFileType(file,object_type)) {
         file.name = encodeURIComponent(file.name);
         filelist[object_type].push(file);
         qId = md5(object_type+tmp_puth_user+tmp_taskiddir+'/'+file.name); 
         //---------------------------------------------------
         let fstatus = '';
         if (typeof qId_list_del[qId] != 'undefined')  fstatus=qId_list_del[qId]; // отобразить статус который отображен сейчас
         //---------------------------------------------------
         fl_html += "<div class=\"cls_line\" id=\"id_qId_"+qId+"\"><div class=\"cls_fname\">"+file.name+" "+Math.round(file.size/1024)+" Kb.</div><div class=\"cls_fname cls_fname_err\" id=\"id_qId_"+qId+"_status\">"+fstatus+"</div><button onClick=\"javascript:parent.fileUploadCancel('"+object_type+"','"+qId+"'); return false;\" class=\"cls_submit cls_black\">-</button></div>";
        }
        else alert('The file format ' + file.name + ' is not supported, please contact the developer and inform the format' + file.type);
    }
    output.innerHTML = fl_html;
    return true;
}


function fileUploadCancel(object_type, qId)
{
  return updateList(object_type, qId); // remove_qId = qId -> удалить из списка
}

function send_files_on_serv(object_type)
{ 
  if (!filelist[object_type]) return false;
  //-----------------------------------------------------------------
  qId_list_del = {};
  
  var correct_select_files_for_upload=0;     // проверка файлов на стороне клиента не выполнена
  for (var i=0; i<filelist[object_type].length; i++)
  {
    if (object_type=='images')
    {
     get_qId=md5(object_type+tmp_puth_user+tmp_taskiddir+'/'+filelist[object_type][i].name); 
     get_fileObj=filelist[object_type][i];
    }

    if (fileUpload(object_type,'select_files_for_upload',get_qId,get_fileObj))
    correct_select_files_for_upload++;     // проверить файлы на стороне клиента
  }

  // выполнить обновление списка и отмены проблемных файлов 
  fileUploadCancel(object_type,'all_incorrect');   

  var obj_but_send_files = frames['iframe_upload_files_'+object_type].document.getElementById('id_form_uploadr_'+object_type);
  
  //==filelist[object_type].length
  if (obj_but_send_files && correct_select_files_for_upload==filelist[object_type].length)   
  { // предварительная проверка файлов на стороне клиента выполнена для хотя бы 1 загружаемого файла из списка успешно
    frames['iframe_upload_files_'+object_type].document.getElementById('id_but_close_form_'+object_type).click(); // свернуть редактор загрузки файлов
    obj_but_send_files.submit(); // отправить запрос пред.загрузки файлов
  }
  else
  return false;  
}
 
function unload_onload(object_type, re_unload_status, json_serv_filenames, taskiddir)
{ // выполняется после предварительной загрузки файлов на сервер 

  if (re_unload_status!='true') // пред. загрузка завершена
  return false;
  
  tmp_taskiddir = taskiddir;
  sr_load_files_images.tmp_taskiddir=tmp_taskiddir;
  
  var file_real_name, jsf=JSON.parse(json_serv_filenames);
  for (var i=0; i<filelist[object_type].length; i++)
  {
    file_real_name = '';
    if (object_type=='images')
    {
     file_real_name = jsf[md5(translite(filelist[object_type][i].name))]; // else renamed tmp file name -> get new tmp file name on server!
     sr_load_files_images.fileObj=filelist[object_type][i];
    }

    if (typeof file_real_name == 'undefined' 
            || file_real_name == '') alert('error load file file_real_name=['+file_real_name+']');
    
    fileUpload(object_type,'onComplete_files_for_upload','','',tmp_puth_user+tmp_taskiddir+'/'+filelist[object_type][i].name,file_real_name);
  } 
}

// БЛОК С ФОТОГРАФИЯМИ
function refresh_block_photo()
{
 try
 {
 arr_image_files.length = 0;
 arr_image_names.length = 0;
 //------------------------
 imgs_n=0;
 var i=0;
 while (i<arr_image_names.length) 
 {
  var obj = getid("id_imgvX"+(i+1));
  if (obj) obj.parentNode.removeChild(obj);
  i++;
 }
 //------------------------------------
 var obj_pli = getid('id_prew_images');
 var obj_pli_info = getid('loadTips_images');
 obj_pli.innerHTML = 'Currently uploaded 0 photos';
 obj_pli_info.innerHTML = '';
 //----------------
 sr_load_files_images.tmp_puth_user=tmp_puth_user;
 sr_load_files_images.tmp_taskiddir=tmp_taskiddir;
 } catch(e) { try_error('refresh_block_photo'); }
}

// load task user for editing
function load_task_user_for_editing(get_taskiddir)
{
  // создать\обновить форму загрузки файлов
  getid('id_iframe_upload_files_images').src=https_host+sr_load_files_images.script+"&upload_container=images&folder="+sr_load_files_images.tmp_puth_user+"&taskiddir="+get_taskiddir;

  app_tasks_js_controller.delete_list_images(get_taskiddir);
   
  let mkey_dirId = (md5(sr_load_files_images.tmp_puth_user+get_taskiddir)).substring(0,10);
  let cipId = '.cls_img_pId'+mkey_dirId;
  var list_images = $('div').find(cipId);
  var i=0;
  for (var i=0; i<list_images.length; i++)
  {
    let photo_name = list_images[i].getAttribute('src');
    
    arr_image_names[imgs_n] = new Array();
    arr_image_names[imgs_n]['url'] = (photo_name.substring(photo_name.indexOf('storage_files'))).replace('%','{prc}'); 
    arr_image_names[imgs_n]['real_url'] = (photo_name.substring(photo_name.indexOf('storage_files'))).replace('%','{prc}');
    arr_image_names[imgs_n]['descr'] = ''; 
    arr_image_names[imgs_n]['own'] = '';    
    //--------------------------------------
    arr_image_files[imgs_n] = false;
    //--------------------------------------
    imgs_n++;
  }

  // load name task
  $('#id_task_name_input').val($('.cls_task_name_tId'+app_tasks_js_controller.taskId).html());
  // load email task
  $('#id_task_email_input').val($('.cls_task_email_tId'+app_tasks_js_controller.taskId).html());
  // load description task
  $('#id_task_descr_text_task').val($('.cls_descr_tId'+app_tasks_js_controller.taskId).html());
  
  $('.cls_general_ui').animate({scrollTop:0}, 500);

  $('#id_but_send_public_task').html('Edit task');

  preview_load_images();
}

 function fileUpload(type_load, type_process='start_settings', get_qId = '', get_fileObj = '', file_user_name='', file_real_name='')
 {
   /*
    type_load='images' -> onload images files
   */
 
  try
  {

   if (type_process=='start_settings')
   {
     
   if (type_load=='images')
   {
    sr_load_files_images = {
    'qId': '-1',
    'fileObj': '-1',
		'script': 'index.php?ajax_controller=upload_files&kf='+app_tasks_js_controller.kf,
		'tmp_puth_user': tmp_puth_user,
		'tmp_taskiddir': tmp_taskiddir,
		'fileDesc': 'photos *.jpg;*.jpeg;*.gif;*.png',
		'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
		'multi': true,
		'buttonText': 'Select photos',
		'SizeLimit': 256000000,
		'onSelect'   : function(qId, fileObj) 
		  {         
            try
            {
            var filesize = Math.round(fileObj.size / 1024);
            //------------
            var qi=fileObj.name.length;
            while (qi>0)
            {
                if (fileObj.name[qi]=='.') { break; }
                qi--;
            }
            //-------------
            var zet = fileObj.name.substring(qi,fileObj.name.length);
            var fname = fileObj.name.substring(0,qi);
            fileObj.name = translite(fname)+zet; // translit in english
            //-------------
            var max_size_f = 5120; // kb = 5 MB
            //-------------
            var index_found_file = file_found_image(tmp_puth_user+tmp_taskiddir+'/'+fileObj.name); // -1 = not found
            if ((filesize>max_size_f && (fileObj.name.indexOf('-3d-')==-1 && fileObj.name.indexOf('-anim-')==-1)) || index_found_file!=-1 || arr_image_names.length>=150)
                { 
                //--------------
                if (filesize>max_size_f)  qId_list_del[qId] = 'The photo has a size over '+ max_size_f +' kb. Unable to upload photo.';
                if (arr_image_names.length>=150)  qId_list_del[qId] = 'The number of photos has been exceeded. Unable to upload photo.';
                if (index_found_file!=-1) qId_list_del[qId] = 'The photo is already on the server. Unable to upload photo.';
                return false;
                //--------------
                }
            //--------------------------
            return true;
            //-------------------------            
            }
            catch(e)
            {
              alert('try except fileUpload images -> onSelect e.msg='+e.message);
            }
		   },
		   'onComplete': function(fileObj, file_user_name, file_real_name) 
		   { 
		    //=================
        try
        {
          var qi=fileObj.name.length;
          while (qi>0)
          {
            if (fileObj.name[qi]=='.') { break; }
            qi--;
          }

            var fname = fileObj.name.substring(0,qi);
                checkfile(imgs_n, tmp_puth_user+tmp_taskiddir+'/'+file_real_name, fname, file_user_name); // проверка существования фото
        }
        catch(e)
        {
          alert('try except fileUpload (onComplete) e.msg='+e.message);
        }
			 //========================		   
		   },
		'auto' :true
  	}

   } 
 
   // создать форму загрузки файлов
   create_form_upload(type_load);

   }
   else
   if (type_process=='select_files_for_upload') 
   { 
    if (type_load=='images') return sr_load_files_images.onSelect(get_qId, get_fileObj); 
    return false;
   }
   else
   if (type_process=='onComplete_files_for_upload') 
   { 
    try
    {
     if (type_load=='images') return sr_load_files_images.onComplete(sr_load_files_images.fileObj, file_user_name, file_real_name); 
    }
    catch(e)
    {
     alert('try except fileUpload (onComplete_files_for_upload) e.msg='+e.message);
    }
    return false;
   }
   else app_tasks_js_controller.log('type_process='+type_process+'; undeclared');

  }
  catch(e)
  {
   app_tasks_js_controller.log('try except fileUpload e.msg='+e.message);
  }

 }

 function close_upload_editor(upload_container)
 {// закрыть окно редактора загрузки файлов
   $('#id_uplc_'+upload_container).fadeOut('fast');
 }