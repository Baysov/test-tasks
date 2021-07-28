<?php

namespace test_tasks_secure_code\Views;

use test_tasks_secure_code\Controllers\account;

class templates {

    public static function add_task_in_list($pId, 
        $taskId, 
        $task_name,
        $task_descr, 
        $task_email,
        $list_images,
        $last_change_date,
        $status_descr_update,
        $edit_task,
        $edit_task_status,
        $delete_task) 
    {
        return '<div class="col cls_col">
                    <div class="card shadow-sm mb-1">
                        <div class="row">
                            <div class="col-4 themed-grid-col"><span class="cls_task_name_tId'.$taskId.' p-1">'.$task_name.'</span></div>
                            <div class="col-4 themed-grid-col"><span class="cls_task_email_tId'.$taskId.' p-1">'.$task_email.'</span></div>
                            <div class="col-4 themed-grid-col"><span class="cls_descr_tId'.$taskId.' p-1">'.$task_descr.'</span></div>
                        </div>
                        <div class="row">
                            '.$list_images.'
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group m-1">
                                '.$edit_task_status.'
                                '.$edit_task.'
                                '.$delete_task.'
                                </div>
                            </div>
                            
                            <div class="row">
                                '.$status_descr_update.'
                                <small class="text-muted"><span class="p-1"><b>Task #'.$taskId.'</b>, date create '.$last_change_date.'</span></small>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    public static function generate_pagination_tasks_sort_list($pagination)
    {
        return '<div class="row cls_unsel">
            <div class="btn-group">
                '.$pagination.'
            </div>
        </div>';
    }
    
    public static function generate_album_tasks_sort_list(
        $add_class_user_active, $sort_ord1,
        $add_class_email_active, $sort_ord2,
        $add_class_status_active, $sort_ord3) 
    {        

        $onClick_button_sort = "onClick=\"app_tasks_js_controller.click_column_sort(this.id);\"";
        
        $svg_ico_sort_type_asc = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-sort-numeric-up m-1 cls_svg_ico_sort_type" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M4 14a.5.5 0 0 0 .5-.5v-11a.5.5 0 0 0-1 0v11a.5.5 0 0 0 .5.5z"/>
        <path fill-rule="evenodd" d="M6.354 4.854a.5.5 0 0 0 0-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L4 3.207l1.646 1.647a.5.5 0 0 0 .708 0z"/>
        <path d="M12.438 7V1.668H11.39l-1.262.906v.969l1.21-.86h.052V7h1.046zm-2.84 5.82c.054.621.625 1.278 1.761 1.278 1.422 0 2.145-.98 2.145-2.848 0-2.05-.973-2.688-2.063-2.688-1.125 0-1.972.688-1.972 1.836 0 1.145.808 1.758 1.719 1.758.69 0 1.113-.351 1.261-.742h.059c.031 1.027-.309 1.856-1.133 1.856-.43 0-.715-.227-.773-.45H9.598zm2.757-2.43c0 .637-.43.973-.933.973-.516 0-.934-.34-.934-.98 0-.625.407-1 .926-1 .543 0 .941.375.941 1.008z"/>
      </svg>';
        
        $svg_ico_sort_type_desc = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-sort-numeric-down-alt m-1 cls_svg_ico_sort_type" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M4 2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-1 0v-11A.5.5 0 0 1 4 2z"/>
        <path fill-rule="evenodd" d="M6.354 11.146a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L4 12.793l1.646-1.647a.5.5 0 0 1 .708 0z"/>
        <path d="M9.598 5.82c.054.621.625 1.278 1.761 1.278 1.422 0 2.145-.98 2.145-2.848 0-2.05-.973-2.688-2.063-2.688-1.125 0-1.972.688-1.972 1.836 0 1.145.808 1.758 1.719 1.758.69 0 1.113-.351 1.261-.742h.059c.031 1.027-.309 1.856-1.133 1.856-.43 0-.715-.227-.773-.45H9.598zm2.757-2.43c0 .637-.43.973-.933.973-.516 0-.934-.34-.934-.98 0-.625.407-1 .926-1 .543 0 .941.375.941 1.008zM12.438 14V8.668H11.39l-1.262.906v.969l1.21-.86h.052V14h1.046z"/>
      </svg>';

      
        $svg_ico_sort_ord1 = '';
        $svg_ico_sort_ord2 = '';
        $svg_ico_sort_ord3 = '';

        if ($add_class_user_active=='active'){
            if ($sort_ord1=='desc')
            $svg_ico_sort_ord1 = $svg_ico_sort_type_asc;
            else
            $svg_ico_sort_ord1 = $svg_ico_sort_type_desc;
        }

        if ($add_class_email_active=='active'){
            if ($sort_ord2=='desc')
            $svg_ico_sort_ord2 = $svg_ico_sort_type_asc;
            else
            $svg_ico_sort_ord2 = $svg_ico_sort_type_desc;
        }
        
        if ($add_class_status_active=='active'){
            if ($sort_ord3=='desc')
            $svg_ico_sort_ord3 = $svg_ico_sort_type_asc;
            else
            $svg_ico_sort_ord3 = $svg_ico_sort_type_desc;
        }

        return '<div class="album py-1 bg-light">
        <div class="container">
    
            <div class="row">
              <h4 class="text-black">Sort tasks:</h4>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="btn-group">
                
                    <button type="button" id="id_sort_user_name" '.$onClick_button_sort.' class="btn btn-sm btn-outline-secondary cls_task_sort '.$add_class_user_active.'" sort="'.$sort_ord1.'">User Name</button>
                    <div class="m-1">'.$svg_ico_sort_ord1.'</div>
                    <button type="button" id="id_sort_email" '.$onClick_button_sort.' class="btn btn-sm btn-outline-secondary cls_task_sort '.$add_class_email_active.'" sort="'.$sort_ord2.'">Email</button>
                    <div class="m-1">'.$svg_ico_sort_ord2.'</div>
                    <button type="button" id="id_sort_status" '.$onClick_button_sort.' class="btn btn-sm btn-outline-secondary cls_task_sort '.$add_class_status_active.'" sort="'.$sort_ord3.'">Status</button>
                    <div class="m-1">'.$svg_ico_sort_ord3.'</div>

                </div>              
              </div>
            </div>

        </div>
      </div>  ';

    }

}


?>