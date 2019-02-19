<?php

//function getUserDetails($userId=NULL,$field='')
//{
//   $CI =& get_instance();
//   $mod = $CI->load->model('user_model');
//   $conditions = array('users.id'=>$userId);
//   $result = $CI->user_model->getUsers($conditions);
//   if($result->num_rows()>0) {
//        $data = $result->row();
//        $res = $data->$field;
//   } else {
//        $res = '';
//   }
//return $res;
//}

function showErrorMessages($errorMessage) {
    
    if(isset($errorMessage['type'])){
    if ($errorMessage['type'] == "error") {
        return '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $errorMessage['msg'] . '</div>';
    }
    if ($errorMessage['type'] == "warning") {
        return '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $errorMessage['msg'] . '</div>';
    }
    if ($errorMessage['type'] == "info") {
        return '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $errorMessage['msg'] . '</div>';
    }
    if ($errorMessage['type'] == "success") {
        return '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $errorMessage['msg'] . '</div>';
    }
    }
}
