<?php

/*
 * Function to print data in readable manner
 */

function pre($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/*
 * Establish database connection
 */

function db_connect() {
    return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

function validateLogin() {
    $ci = &get_instance();
    
    $logged_in = $ci->session->userdata('adminSession');
   
    if(!isset($logged_in) && count($logged_in)==0) {
        $ci->session->set_flashdata(array('type' => 'error', 'msg' => 'You are not authorized to this section. Please login.'));
        redirect(base_url('login'));        
    }
}

function set_default_timezone() {
    $ci = &get_instance();
    $tz = $ci->input->cookie('tz', TRUE);
//pre($tz); die;
    if (isset($tz) && $tz != '') { //echo "hello";
// Convert minutes to seconds
        $timezone_name = timezone_name_from_abbr("", $tz * 60, false); //echo "hello1";
//pre($timezone_name); die;
//date_default_timezone_set($timezone_name);
        $tz = $ci->input->cookie('timezone', TRUE);
        if (isset($tz) && $tz != '') {
            date_default_timezone_set($tz);
        } else {
            date_default_timezone_set('Australia/Sydney');
        }
    }
}

function dateFormat($format, $dt) {
    return date($format, strtotime($dt));
}

function string_to_date($format, $dt) {
    $d = explode('/', $dt);
    return date_format(date_create($d[2] . '/' . $d[1] . '/' . $d[0]), $format);
}

/*
 * Function to prevent admin user to access pages like forgot password, reset password, etc.. if user is logged in
 * it redirects user to admin dashboard
 */

function checkIfNotLoggedIn() {
    $ci = &get_instance();
    if ($ci->session->userdata('adminSession') && count($ci->session->userdata('adminSession')) > 0) {
        if ($ci->session->flashdata()) {
            $flashdata = $ci->session->flashdata();
            $ci->session->set_flashdata($flashdata);
        }
        redirect(base_url('admin/dashboard'));
    }
}

/*
 * function to get current Language
 */

function get_current_language() {
    $ci = & get_instance();
    if ($ci->session->userdata('lang')) {
        return $ci->session->userdata('lang');
    } else {
        return DEF_LANGUAGE;
    }
}

/*
 * function to get user details
 */

function get_user_details($user_id, $cond = array()) {

    $ci = & get_instance();
    $lang_id = get_current_language();
    $user = array();
    $lang_case = "`id`.`lang_id`= CASE WHEN (EXISTS (SELECT id FROM " . INTERESTS_DATA . " WHERE lang_id = '$lang_id') ) THEN $lang_id ELSE 1 END";

    $ci->db->select("u.id as uid,u.plan_expire as u_plan_expire,u.plan_type as u_plan_type,u.plan_id as u_plan_id, u.*, pr.*, f.*, f.id as file_id, fam.id as fam_id,fam.plan_expire,fam.plan_type,fam.plan_id, fam.*");
    $ci->db->from(USER . " u");
    $ci->db->join(PROFILE . " pr", "pr.user_id = u.id", 'left');
    $ci->db->join(FILES . " f", "pr.profile_Image = f.id", 'left');
    $ci->db->join(FAMILY_USERS . " fu", "fu.user_id = u.id", 'left');
    $ci->db->join(FAMILY . " fam", "fu.family_id = fam.id", 'left');
    $ci->db->where("u.id", $user_id);
    $query = $ci->db->get();


    if ($query->num_rows() > 0) {
        $result = $query->result_array();
        $user = $result[0];

// Get data from for user interests
        $ci->db->select("i.*, id.content");
        $ci->db->from(INTERESTS . " i");
        $ci->db->join(PROFILE_INTEREST . " pi", "pi.interest_id = i.id");
        $ci->db->join(INTERESTS_DATA . " id", "id.interest_id = i.id");
        $ci->db->where("pi.user_id", $user_id);
        $ci->db->where($lang_case);
//$ci->db->distinct();
        $query_int = $ci->db->get();
        if ($query_int->num_rows() > 0) {
            $details = $query_int->result_array();
            $user['interest'] = $details;
        } else {
            $user['interest'] = array();
        }

        // Get data from for user family children
        $ci->db->select("fc.*");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(FAMILY_CHILDREN . " fc", "f.id = fc.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = fc.family_id");
        $ci->db->where("fu.user_id", $user_id);
//$ci->db->distinct();
        $query_child = $ci->db->get();
        if ($query_child->num_rows() > 0) {
            $details = $query_child->result_array();
            $user['family_children'] = $details;
        } else {
            $user['family_children'] = array();
        }

// Get data from for user family pets
        $ci->db->select("fp.*");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(FAMILY_PETS . " fp", "f.id = fp.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = fp.family_id");
        $ci->db->where("fu.user_id", $user_id);
//$ci->db->distinct();
        $query_pets = $ci->db->get();
        if ($query_pets->num_rows() > 0) {
            $details = $query_pets->result_array();
            $user['family_pets'] = $details;
        } else {
            $user['family_pets'] = array();
        }

// Get data from for user family elders
        $ci->db->select("fe.*");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(FAMILY_ELDERS . " fe", "f.id = fe.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = fe.family_id");
        $ci->db->where("fu.user_id", $user_id);
//$ci->db->distinct();
        $query_elders = $ci->db->get();
        if ($query_elders->num_rows() > 0) {
            $details = $query_elders->result_array();
            $user['family_elders'] = $details;
        } else {
            $user['family_elders'] = array();
        }
        /**
          $family_users = array();
          $users = family_user();
          //        pre($users);
          $ids = array_column($users, 'user_id');
          foreach ($ids as $user) {
          $userinfo = get_user_basic_info($user);
          $userinfo['is_user'] = 1;
          $userinfo['user_id'] = $user;
          $family_users[] = $userinfo;
          }

          $ci->db->where("family_id", $_SESSION['userSession']['family_id']);
          $qqur = $ci->db->get(PARENT_ADMIN);
          $res_parent = $qqur->result_array();
          foreach ($res_parent as $parent) {
          if(in_array($parent['user_id'], array_column($family_users, 'user_id'))) {
          $family_users[$parent['user_id']]['role'] = $parent['role'];
          }
          if ($family_users[$parent['user_id']]) {
          $family_users[$parent['user_id']]['role'] = $parent['role'];
          }else{

          }
          }
         * */
        $ci->db->select("pa.*");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(PARENT_ADMIN . " pa", "f.id = pa.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = pa.family_id");
        $ci->db->where("fu.user_id", $user_id);
        //$ci->db->distinct();
        $query_elders = $ci->db->get();
        if ($query_elders->num_rows() > 0) {
            $details = $query_elders->result_array();
            $user['family_admin'] = $details;
        } else {
            $user['family_admin'] = array();
        }

// Get data from for user family home address
        $ci->db->select("ha.*, cd.content");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(HOME_ADDRESS . " ha", "f.id = ha.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = ha.family_id");
        $ci->db->join(COUNTRY . " c", "ha.country = c.id", 'left');
        $ci->db->join(COUNTRY_DATA . " cd", "cd.country_id = ha.country", 'left');
        $ci->db->where("fu.user_id", $user_id);
        $query_add = $ci->db->get();
        if ($query_add->num_rows() > 0) {
            $details = $query_add->row_array();
            $user['home_address'] = $details;
        } else {
            $user['home_address'] = array();
        }

// Get data from for user family emergency Contact
        $ci->db->select("ec.*, cd.content");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(EMERGENCY_CONTACT . " ec", "f.id = ec.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = ec.family_id");
        $ci->db->join(COUNTRY . " c", "ec.country = c.id", 'left');
        $ci->db->join(COUNTRY_DATA . " cd", "cd.country_id = ec.country", 'left');
        $ci->db->where("fu.user_id", $user_id);
        $query_eme = $ci->db->get();
        if ($query_eme->num_rows() > 0) {
            $details = $query_eme->row_array();
            $user['emergency_contact'] = $details;
        } else {
            $user['emergency_contact'] = array();
        }

// Get data from for user family doctor
        $ci->db->select("d.*, cd.content");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(DOCTOR . " d", "f.id = d.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = d.family_id");
        $ci->db->join(COUNTRY . " c", "d.country = c.id", 'left');
        $ci->db->join(COUNTRY_DATA . " cd", "cd.country_id = d.country", 'left');
        $ci->db->where("fu.user_id", $user_id);
        $query_doc = $ci->db->get();
        if ($query_doc->num_rows() > 0) {
            $details = $query_doc->row_array();
            $user['doctor'] = $details;
        } else {
            $user['doctor'] = array();
        }

// Get data from for user family Gallery
        $ci->db->select("fg.*");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(FAMILY_GALLERY . " fg", "f.id = fg.family_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = fg.family_id");
        $ci->db->where("fu.user_id", $user_id);
        $query_gal = $ci->db->get();
        $user['family_gallery'] = ($query_gal->num_rows() > 0) ? $query_gal->result_array() : array();

// Get data from for user family friends
        $ci->db->select("fr.*");
        $ci->db->from(FAMILY . " f");
        $ci->db->join(FRIENDS . " fr", "f.id = fr.from_id OR f.id = fr.to_id");
        $ci->db->join(FAMILY_USERS . " fu", "fu.family_id = fr.from_id OR fu.family_id = fr.to_id");
        $ci->db->where("fu.user_id", $user_id);
        $ci->db->distinct();
        $query_fr = $ci->db->get();
        $user['friends'] = ($query_fr->num_rows() > 0) ? $query_fr->result_array() : array();
    }
    return $user;
}

/*
 * Function to send email
 */

function send_email($to, $subject, $message, $attchment = FALSE, $cc = FALSE, $bcc = FALSE) {
    $ci = & get_instance();
    $ci->load->library('email');
    $config     =   array(
                    'protocol' => 'smtp',
                    'smtp_crypto' => 'tls',
                    'smtp_host' => 'smtp.sendgrid.net',
                    'smtp_port' => 2525,
                    'smtp_user' => 'apikey',
                    'smtp_pass' => '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',
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'smtp_timeout' => '4'
                );
    $ci->email->initialize($config);
    $ci->email->set_newline("\r\n");

    //$ci->email->from('help@mindforme.com', "Mind For Me™");
    $ci->email->from('support@cryptoxygen.io', 'Cryptoxygen');
    $ci->email->to($to); //$ci->email->to($to);
    //$ci->email->cc($cc);
    $ci->email->bcc(array('abc@gmail.com','support@cryptoxygen.io'));
    if($bcc!=''){
        $ci->email->bcc($bcc);
    }
    $ci->email->subject($subject);
    $ci->email->message($message);
    $res = $ci->email->send();
    
    if ($res){
        return 1;
    }else{
        //return $ci->email->print_debugger();
        return 0;
    }
}

function make_dir($folder_name) {

    if (!is_dir($folder_name)) {
        mkdir($folder_name, 0755, TRUE);
    }
}

/*
 * Function to upload images
 * @params field_name, $_FILES[], folder_name
 * @return array of uploaded data, true/false
 */

function do_upload($field_name, $folder_name) {
//echo $folder_name;
    $ci = & get_instance();
//create upload folder if not exists
    if (!is_dir(UPLOADS . $folder_name)) {
        mkdir(UPLOADS . $folder_name, 0777, TRUE);
    }

    $data = array();
    $config = array();
    $config['upload_path'] = UPLOADS . $folder_name;

//$config['max_size']         =   0;
    $config['allowed_types'] = '*';
//    var_dump($_FILES);
//$attach = time() . "_image";
//$config['file_name'] = $attach;
    $ci->load->library('upload');
    $ci->upload->initialize($config);
    if ($ci->upload->do_upload($field_name)) {
        $data = $ci->upload->data();
//rotate image if exif orientation exists
//$ci->load->library('image_autorotate', array('filepath' => $data['full_path']));
        $data['val'] = 1;
    } else {
        $data['val'] = 0;
        $data['error'] = $ci->upload->display_errors();
    }
    return $data;
}

function upload_videos($field_name, $files, $folder_name) {
//echo $folder_name;
    $ci = & get_instance();
//create upload folder if not exists
    if (!is_dir($folder_name)) {
        mkdir($folder_name, 0777, TRUE);
    }

    $data = array();
    $config = array();
    $config['upload_path'] = $folder_name;
//$config['max_size']         =   0;
    $config['allowed_types'] = '*'; //'mp4|avi|mov|mpg|mpeg|mpe';

    $attach = time() . "video";
//    $attach =   time()."_".$files['name'];
//    $attach =   str_replace(':', '_', $attach);
//    $attach =   str_replace('(', '_', $attach);
//    $attach =   str_replace(')', '_', $attach);
//    $attach =   str_replace('-', '_', $attach);
//    $attach =   str_replace(' ', '_', $attach);

    $config['file_name'] = $attach;

    $ci->load->library('upload');
    $ci->upload->initialize($config);
    if ($ci->upload->do_upload($field_name)) {
        $data = $ci->upload->data();
//rotate image if exif orientation exists
        $ci->load->library('image_autorotate', array('filepath' => $data['full_path']));
        $data['val'] = 1;
    } else {
        $data['val'] = 0;
        $data['error'] = $ci->upload->display_errors();
    }
//pr($data);die;
    return $data;
}

/*
 * Function to apply paginatiuon 
 * return pagination links html
 */

function pagination($data, $base_url, $uri_segment, $per_page) {
    $ci = & get_instance();
    $ci->load->library('pagination');
    $config['base_url'] = $base_url;
    $config['uri_segment'] = $uri_segment;
    $config['total_rows'] = count($data);
    $config['per_page'] = $per_page;
    $config['display_pages'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 4;
    $config['full_tag_open'] = '<ul class="pagination pagination-sm pull-right push-down-20">';
    $config['full_tag_close'] = '</ul>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a>';
    $config['cur_tag_close'] = '</a></li>';
    $config['first_url'] = $base_url;

    $ci->pagination->initialize($config);

    return $ci->pagination->create_links();
}

function pageConfig() {
    $config['display_pages'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 4;
    $config['full_tag_open'] = '<ul class="pagination pagination-sm pull-right push-down-20">';
    $config['full_tag_close'] = '</ul>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a>';
    $config['cur_tag_close'] = '</a></li>';
    return $config;
}

function frontend_pageConfig() {
    $config['display_pages'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 4;
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li><a class="active">';
    $config['cur_tag_close'] = '</a></li>';
    $config['reuse_query_string'] = true;
    return $config;
}

/*
 * function to check if content of mentioned page type exist or not
 * @param type
 * return true/false
 */

function checkIfContentExists($type) {
    $ci = & get_instance();
    $content = $ci->crud->get(CMS_CONTENT, array('page_type' => $type));
    if (count($content) > 0) {
        return 1;
    } else {
        return 0;
    }
}

/*
 * function to check and get alias
 * @param table - in which field should be unique
 *        field - which field should be unique
 *        value - which value needs to be compared
 */

function checkAlias($table, $field, $value, $where = array()) {
    $ci = & get_instance();
    $alias = strtolower(preg_replace("![^a-z0-9]+!i", "-", $value));
    $j = 1;
    for ($i = 0; $i < $j; $i++) {
        $cond = array($field => $alias);
        if (count($where) > 0) {
            array_merge($cond, $where);
        }
        $row = $ci->crud->getAll($table, $cond);
        if (count($row) == 0) {
            $newAlias = $alias;
        } else {
            $alias = $alias . '-' . $i;
            $j++;
        }
    }
    return $newAlias;
}

/*
 * function to check if the file exists on url or not
 */

function checkRemoteFile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
// don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (curl_exec($ch) !== FALSE) {
        return true;
    } else {
        return false;
    }
}

function imageCreateFromAny($filepath) {
    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
    $allowedTypes = array(
        1, // [] gif
        2, // [] jpg
        3, // [] png
        6   // [] bmp
    );
    if (!in_array($type, $allowedTypes)) {
        return false;
    }
    switch ($type) {
        case 1 :
            $im = imageCreateFromGif($filepath);
            break;
        case 2 :
            $im = imageCreateFromJpeg($filepath);
            break;
        case 3 :
            $im = imageCreateFromPng($filepath);
            break;
        case 6 :
            $im = imageCreateFromBmp($filepath);
            break;
    }
    if ($im && imagefilter($im, IMG_FILTER_PIXELATE, 8)) {
        imagepng($im, $filepath);
    }
    return $im;
}

/*
 * Function to get media path by media Id
 * @param (media_id)
 * @return media_path
 */

function getMediaPath($media_id = 0, $width = '', $height = '', $blur = 0) {
    $ci = &get_instance();
    $media = $ci->crud->get(FILES, array('id' => $media_id));
    if (count($media) > 0) {
        if ($width == '' && $height == '') {
            compress_image(UPLOADS . $media['path'], UPLOADS . $media['path'], 80);
            return UPLOADS . $media['path'];
        } else {
            if (!file_exists(UPLOADS . $media['path'])) {  //echo $media['path'];die("kkkkkkk");
                return "";
            }
            $arr = explode('/', $media['path']);
            $path = '';
            foreach ($arr as $key => $ar) {
                if ($key < count($arr) - 1) {
                    $path.= $ar . '/';
                }
            }
            $image_name = end($arr);
            $imageArr = explode('.', $image_name);
            //create upload folder if not exists
            if (!is_dir(FCPATH . UPLOADS . $path . 'resize')) {
                mkdir(FCPATH . UPLOADS . $path . 'resize', 0777, TRUE);
            }
            $target = UPLOADS . $path . 'resize/' . $imageArr[0] . '_' . $width . '_' . $height . '.' . $imageArr[1];

//            var_dump($target);
            if ($blur) {
                if (!is_dir(FCPATH . UPLOADS . $path . 'blur')) {
                    mkdir(FCPATH . UPLOADS . $path . 'blur', 0777, TRUE);
                }
                $target1 = UPLOADS . $path . 'blur/' . $imageArr[0] . '_' . $width . '_' . $height . '.' . $imageArr[1];
                if (!file_exists(FCPATH . $target1)) {
                    if (do_resize(FCPATH . UPLOADS . $media['path'], FCPATH . $target1, $width, $height)) {
                        if (file_exists(FCPATH . $target1)) {
                            $im = imageCreateFromAny($target1);
                            if ($im) {
                                //To compress images
                                compress_image(FCPATH . $target1, FCPATH . $target1, 80);
                                return $target1;
                            }
                        }
                    }
                } else {
                    return $target1;
                }
            } else {
                if (!file_exists(FCPATH . $target)) {
                    if (do_resize(FCPATH . UPLOADS . $media['path'], FCPATH . $target, $width, $height)) {
                        if (file_exists(FCPATH . $target)) {
                            //To compress images
                            compress_image(FCPATH . $target, FCPATH . $target, 80);
                            return $target;
                        }
                    }
                } else {
                    return $target;
                }
            }
        }
    } else {
        return "";
    }
}

/* function getMediaPath($media_id = 0, $width = '', $height = '') {
  $ci = &get_instance();
  $media = $ci->crud->get(FILES, array('id' => $media_id));
  if (count($media) > 0) {
  if ($width == '' && $height == '') {
  //$info = getimagesize(UPLOADS . $media['path']);
  compress_image(UPLOADS . $media['path'], UPLOADS . $media['path'], 80);
  return UPLOADS . $media['path'];
  } else {
  if (!file_exists(UPLOADS . $media['path'])) {
  return "";
  }
  $arr = explode('/', $media['path']);
  $path = '';
  foreach ($arr as $key => $ar) {
  if ($key < count($arr) - 1) {
  $path.= $ar . '/';
  }
  }
  $image_name = end($arr);
  $imageArr = explode('.', $image_name);
  //create upload folder if not exists
  if (!is_dir(FCPATH . UPLOADS . $path . 'resize')) {
  mkdir(FCPATH . UPLOADS . $path . 'resize', 0777, TRUE);
  }
  $target = UPLOADS . $path . 'resize/' . $imageArr[0] . '_' . $width . '_' . $height . '.' . $imageArr[1];
  //            var_dump($target);
  if (!file_exists(FCPATH . $target)) {
  if (do_resize(FCPATH . UPLOADS . $media['path'], FCPATH . $target, $width, $height)) {
  if (file_exists(FCPATH . $target)) {
  //To compress images
  compress_image(FCPATH . $target, FCPATH . $target, 80);
  return $target;
  }
  }
  } else {
  return $target;
  }
  }
  } else {
  return "";
  }
  } */


/*
 * function to resize the image
 */

function do_resize($source, $target, $width, $height) {
    $ci = & get_instance();
    $config_manip = array(
        'image_library' => 'GD2',
        'source_image' => $source,
        'new_image' => $target,
        'create_thumb' => TRUE,
        'maintain_ratio' => FALSE,
        'thumb_marker' => '',
        'width' => $width,
        'height' => $height
    );
    $ci->load->library('image_lib');
    $ci->image_lib->clear();
    $ci->image_lib->initialize($config_manip);
    if (!$ci->image_lib->resize()) {
        echo $ci->image_lib->display_errors();
    } else {
        return true;
    }
// clear //
    $ci->image_lib->clear();
}

/*
 * Function to check enabled slider count by slider type
 * @param slider_type
 * @return 1 if new slider can be enabled else return 0 
 */

function checkSliderLimit($slider_type) {
    $ci = & get_instance();
    $activeRows = $ci->crud->getAll(ES_SLIDER, array('status' => '1', 'slider_type' => $slider_type));
    $count = count($activeRows);
    $limit = 0;
    if ($slider_type == "advertisements") {
        $limit = ADVERTISEMENT_LIMIT;
    } elseif ($slider_type == "premium_member") {
        $limit = PREMIUM_MEMBER_LIMIT;
    } elseif ($slider_type == "city_slider") {
        $limit = CITY_SLIDER_LIMIT;
    } elseif ($slider_type == "slabs") {
        $limit = SLABS_LIMIT;
    }
    if ($limit > $count) {
        return '1';
    } else {
        return '0';
    }
}

/*
 * Function to get country, city, states from id of any table
 */

function getRelatedLocations($data = array(), $cond = array(), $json = false) {
    $ci = & get_instance();
    $result = array();
    if (isset($data['country_id'])) {
//get country details
        $whereCountry = array('id' => $data['country_id']);
        if (isset($cond['country'])) {
            $whereCountry = array_merge($whereCountry, $cond['country']);
        }
        $result['country'] = $ci->crud->get(ES_COUNTRIES, $whereCountry);
//get all states corresponding country id
        $whereState = array('country_id' => $data['country_id']);
        if (isset($cond['state'])) {
            $whereState = array_merge($whereState, $cond['state']);
        }
        $states = $ci->crud->getAll(ES_STATES, $whereState);
        if (count($states) > 0) {
            foreach ($states as $state) {
                $result['states'][$state['id']]['name'] = $state['name'];
//get all cities corresponding each state id
                $whereCity = array('state_id' => $state['id']);
                if (isset($cond['city'])) {
                    $whereCity = array_merge($whereCity, $cond['city']);
                }
                $cities = $ci->crud->getAll(ES_CITIES, $whereCity);
                foreach ($cities as $city) {
                    $result['states'][$state['id']]['cities'][$city['id']] = $city['name'];
                }
            }
        }
    } elseif (isset($data['state_id'])) {
//get state and country details
        $ci->db->select('s.id as state_id, c.id as country_id, s.name as state, c.name as country, c.sortname as sortname, c.phonecode as phonecode');
        $ci->db->from(ES_STATES . " s");
        $ci->db->join(ES_COUNTRIES . " c", "c.id = s.country_id");
        $ci->db->where(array('s.id' => $data['state_id']));
        if (isset($cond['state'])) {
            $ci->db->where($cond['state']);
        }
        $query = $ci->db->get();
        $return = $query->result_array();
        $result = $return[0];
//get all cities corresponding state_id
        $whereCity = array('state_id' => $data['state_id']);
        if (isset($cond['city'])) {
            $whereCity = array_merge($whereCity, $cond['city']);
        }
        $cities = $ci->crud->getAll(ES_CITIES, $whereCity);
        foreach ($cities as $city) {
            $result['cities'][$city['id']] = $city['name'];
        }
    } elseif (isset($data['city_id'])) {
//get city, state and country details using joins
        $ci->db->select('s.id as state_id, c.id as country_id, s.name as state, c.name as country, c.sortname as sortname, c.phonecode as phonecode, city.id as city_id, city.name as city');
        $ci->db->from(ES_CITIES . " city");
        $ci->db->join(ES_STATES . " s", "s.id = city.state_id");
        $ci->db->join(ES_COUNTRIES . " c", "c.id = s.country_id");
        $ci->db->where(array('city.id' => $data['city_id']));
        if (isset($cond['city'])) {
            $ci->db->where($cond['city']);
        }
        $query = $ci->db->get();
        $return = $query->result_array();
        $result = $return[0];
    } elseif (count($data) == 0) {
        $whereCountry = array();
        if (isset($cond['country'])) {
            $whereCountry = array_merge($whereCountry, $cond['country']);
        }
        $countries = $ci->crud->getAll(ES_COUNTRIES, $whereCountry);
        if (count($countries) > 0) {
            foreach ($countries as $key => $country) {
                $result[$country['id']] = getRelatedLocations(array('country_id' => $country['id']));
            }
        }
    }
    if ($json == true) {
        return json_encode($result);
    } else {
        return $result;
    }
}

/*
 * Function to check in which cities, resource type exists
 * @return resource type => array of cities
 */

function checkResourceInCity($json) {
    $ci = & get_instance();
    $records = $ci->crud->getAll(ES_RESOURCES, array());
    $result = array();
    if (count($records) > 0) {
        foreach ($records as $record) {
            $result[$record['type']][] = $record['city_id'];
        }
    }
    if ($json == true) {
        return json_encode($result);
    } elseif ($json == false) {
        return $result;
    }
}

/*
 * Function to get category name
 */

function getCategoryNameById($cat_id) {
    $ci = & get_instance();
    $category = $ci->crud->get(ES_PRODUCT_CATEGORIES, array('id' => $cat_id));
    if (count($category) > 0) {
        return $category['name'];
    } else {
        return "Not available";
    }
}

/*
 * Function to get category status
 */

function getCategoryStatusById($cat_id) {
    $ci = & get_instance();
    $category = $ci->crud->get(ES_PRODUCT_CATEGORIES, array('id' => $cat_id));
    if (count($category) > 0) {
        return $category['status'];
    } else {
        return 0;
    }
}

/*
 * Function to get product count of a category
 */

function getProductCount($cat_id) {
    $ci = & get_instance();
    $query = $ci->db->get_where(ES_PRODUCTS, array('category_id' => $cat_id));
    return $query->num_rows();
}

/*
 * Function to get the array of subject for contect us form
 */

function getContactUsSubjects() {
    return array(
        '0' => 'I would like to know the advertising prices',
        '1' => 'I would like to know how to create my profile',
        '2' => 'I would like to make a booking with an escort',
        '3' => 'Problems logging in',
        '4' => "I didn't recieve my activation email",
        '5' => 'Technical Support',
        '6' => 'Other'
    );
}

/*
 * function to get most viewed blogs
 */

function getPopularBlogs($limit = '') {
    $ci = & get_instance();
    if ($limit != '') {
        $ci->db->limit($limit);
    }
    $ci->db->order_by('views', 'DESC');
    $ci->db->where(array('status' => '1', 'views > ' => '0'));
    $query = $ci->db->get(ES_BLOGS);
    $result = array();
    if ($query->num_rows() > 0) {
        $result = $query->result_array();
    }
    return $result;
}

/*
 * function to substr the text (text which is in html code)
 */

function html_cut($text, $max_length) {
    $tags = array();
    $result = "";

    $is_open = false;
    $grab_open = false;
    $is_close = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length) {
        $symbol = $text{$i};
        $result .= $symbol;

        switch ($symbol) {
            case '<':
                $is_open = true;
                $grab_open = true;
                break;

            case '"':
                if ($in_double_quotes)
                    $in_double_quotes = false;
                else
                    $in_double_quotes = true;

                break;

            case "'":
                if ($in_single_quotes)
                    $in_single_quotes = false;
                else
                    $in_single_quotes = true;

                break;

            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes) {
                    $is_close = true;
                    $is_open = false;
                    $grab_open = false;
                }

                break;

            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;

                break;

            case '>':
                if ($is_open) {
                    $is_open = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                } else if ($is_close) {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }

                break;

            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;

                if (!$is_open && !$is_close)
                    $stripped++;
        }

        $i++;
    }

    while ($tags)
        $result .= "</" . array_pop($tags) . ">";

    return $result;
}

/*
 * function to get usr's profile image
 */

function getUserProfilePic($data = array(), $width = '', $height = '') {
    $ci = & get_instance();
    $ci->db->select('p.gender, p.profile_id');
    $ci->db->from(ES_USERS_PROFILE . ' p');
    if (isset($data['user_id'])) {
        $ci->db->where(array('p.user_id' => $data['user_id']));
//$ci->db->where(array('p.user_id' => $data['user_id'], 'pp.status' => '1'));
    } elseif (isset($data['profile_id'])) {
        $ci->db->where(array('p.profile_id' => $data['profile_id']));
//$ci->db->where(array('p.profile_id' => $data['profile_id'], 'pp.status' => '1'));        
    }
    $query = $ci->db->get();
//echo $ci->db->last_query();
    if ($query->num_rows() > 0) {
        $result = $query->result_array();
//profile pic query
        if (isset($data['approved']) && $data['approved'] == '1') {
            $mediaQuery = $ci->db->get_where(ES_USERS_PROFILE_PICS, array('profile_id' => $result[0]['profile_id'], 'status' => "1", 'approved' => "1"));
        } else {
            $mediaQuery = $ci->db->get_where(ES_USERS_PROFILE_PICS, array('profile_id' => $result[0]['profile_id'], 'status' => "1"));
        }
        if (isset($mediaQuery) && $mediaQuery->num_rows() > 0) {
            $media_result = $mediaQuery->result_array(); //pr($media_result); die;
            if (isset($media_result[0]['media_id']) && $media_result[0]['media_id'] != NULL && $media_result[0]['media_id'] != '0') {
                return getMediaPath($media_result[0]['media_id'], $width, $height);
//$abc = getMediaPath($media_result[0]['media_id'], $width, $height);
//pr($abc); die;
            }
        } else {
            if ($result[0]['gender'] == "1") {
                if ($width == '' && $height == '') {
                    return DEFAULT_WOMAN_PROFILE_PIC;
                } else {
                    $imageArr = explode('.', DEFAULT_WOMAN_PROFILE_PIC);
                    $target = $imageArr[0] . '_' . $width . '_' . $height . '.' . $imageArr[1];
                    if (!file_exists(FCPATH . $target)) {
                        if (do_resize(FCPATH . DEFAULT_WOMAN_PROFILE_PIC, FCPATH . $target, $width, $height)) {
                            if (file_exists(FCPATH . $target)) {
                                return $target;
                            }
                        }
                    } else {
                        return $target;
                    }
                }
            } elseif ($result[0]['gender'] == "0") {
                if ($width == '' && $height == '') {
                    return DEFAULT_MAN_PROFILE_PIC;
                } else {
                    $imageArr = explode('.', DEFAULT_MAN_PROFILE_PIC);
                    $target = $imageArr[0] . '_' . $width . '_' . $height . '.' . $imageArr[1];
                    if (!file_exists(FCPATH . $target)) {
                        if (do_resize(FCPATH . DEFAULT_MAN_PROFILE_PIC, FCPATH . $target, $width, $height)) {
                            if (file_exists(FCPATH . $target)) {
                                return $target;
                            }
                        }
                    } else {
                        return $target;
                    }
                }
            } else {
                if ($width == '' && $height == '') {
                    return DEFAULT_MAN_PROFILE_PIC;
                } else {
                    $imageArr = explode('.', DEFAULT_MAN_PROFILE_PIC);
                    $target = $imageArr[0] . '_' . $width . '_' . $height . '.' . $imageArr[1];
                    if (!file_exists(FCPATH . $target)) {
                        if (do_resize(FCPATH . DEFAULT_MAN_PROFILE_PIC, FCPATH . $target, $width, $height)) {
                            if (file_exists(FCPATH . $target)) {
                                return $target;
                            }
                        }
                    } else {
                        return $target;
                    }
                }
            }
        }
    } else {
        return IMAGE_COMING_SOON;
    }
}

/*
 * function to process the unprocessed mails
 */

function addMailProcess($data) {
    $ci = & get_instance();
}

function get_country_data($country_id, $lang_id = FALSE) {
    $ci = &get_instance();
    $ci->db->where('country_id', $country_id);
    if (isset($lang_id) && ($lang_id != '')) {
        $ci->db->where('lang_id', $lang_id);
    }
    $query = $ci->db->get(COUNTRY_DATA);
    return $query->row_array();
}

function get_state_data($state_id, $lang_id = FALSE) {
    $ci = &get_instance();
    $ci->db->where('state_id', $state_id);
    if (isset($lang_id) && ($lang_id != '')) {
        $ci->db->where('lang_id', $lang_id);
    }
    $query = $ci->db->get(STATE_DATA);
    return $query->row_array();
}

function get_city_data($city_id, $lang_id = FALSE) {
    $ci = &get_instance();
    $ci->db->where('city_id', $city_id);
    if (isset($lang_id) && ($lang_id != '')) {
        $ci->db->where('lang_id', $lang_id);
    }
    $query = $ci->db->get(CITIES_DATA);
    return $query->row_array();
}

function get_allergy_data($allergy_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->where('allergies_id', $allergy_id);
    $ci->db->where('lang_id', $lang_id);
    $query = $ci->db->get(ALLERGIES_DATA);
    return $query->row_array();
}

function get_food_data($food_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->where('favourite_food_id', $food_id);
    $ci->db->where('lang_id', $lang_id);
    $query = $ci->db->get(FAVOURITE_FOOD_DATA);
    return $query->row_array();
}

function get_interest_data($int_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->where('interest_id', $int_id);
    $ci->db->where('lang_id', $lang_id);
    $query = $ci->db->get(INTERESTS_DATA);
    return $query->row_array();
}

function get_medical_conditions_data($int_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->where('medical_condition_id', $int_id);
    $ci->db->where('lang_id', $lang_id);
    $query = $ci->db->get(MEDICAL_CONDITION_DATA);
    return $query->row_array();
}

function get_pet_breed_data($pet_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->select("p.*,pd.*");
    $ci->db->from("pet_breed p");
    $ci->db->join("pet_breed_data pd", "pd.pet_breed_id = p.id", 'left');
    $ci->db->where('pd.lang_id', $lang_id);
    $ci->db->where('pd.pet_breed_id', $pet_id);
    $ci->db->group_by('pd.pet_breed_id');
    $query = $ci->db->get();
    return $query->row_array();
}

function get_pet_types_data($pet_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->where('pet_types_id', $pet_id);
    $ci->db->where('lang_id', $lang_id);
    $query = $ci->db->get(PET_TYPES_DATA);
    return $query->row_array();
}

function get_news_category_data($n_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->select("n.*,nd.*");
    $ci->db->from("news_category n");
    $ci->db->join("news_category_data nd", "nd.category_id = n.id", 'left');
    $ci->db->where('nd.lang_id', $lang_id);
    $ci->db->where('nd.category_id', $n_id);
    $ci->db->group_by('nd.category_id');
    $query = $ci->db->get();
    return $query->row_array();
}

function get_plans_data($p_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->select("p.*,pd.*");
    $ci->db->from("plans p");
    $ci->db->join("plans_data pd", "pd.plan_id = p.id", 'left');
    $ci->db->where('pd.lang_id', $lang_id);
    $ci->db->where('pd.plan_id', $p_id);
    $ci->db->group_by('pd.plan_id');
    $query = $ci->db->get();
    return $query->row_array();
}

function get_rules_data($r_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->select("r.*,rd.*");
    $ci->db->from("rules r");
    $ci->db->join("rules_data rd", "rd.rules_id = r.id", 'left');
    $ci->db->where('rd.lang_id', $lang_id);
    $ci->db->where('rd.rules_id', $r_id);
    $ci->db->group_by('rd.rules_id');
    $query = $ci->db->get();
    return $query->row_array();
}

function get_walking_other_data($r_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->select("w.*,wd.*");
    $ci->db->from("walking_other w");
    $ci->db->join("walking_other_data wd", "wd.walking_other_id = w.id", 'left');
    $ci->db->where('wd.lang_id', $lang_id);
    $ci->db->where('wd.walking_other_id', $r_id);
    $ci->db->group_by('wd.walking_other_id');
    $query = $ci->db->get();
    return $query->row_array();
}

function get_news_data($n_id, $lang_id = DEF_LANGUAGE) {
    $ci = &get_instance();
    $ci->db->select("n.*,nd.*,ncd.content as category_name,f.*");
    $ci->db->from("news n");
    $ci->db->join("news_data nd", "nd.news_id = n.id", 'left');
    $ci->db->join("news_category_data ncd", "ncd.category_id = n.category_id", 'left');
    $ci->db->join("files f", "f.id = n.image", 'left');
    $ci->db->where('nd.lang_id', $lang_id);
    $ci->db->where('nd.news_id', $n_id);
    $ci->db->group_by('n.id');
    $query = $ci->db->get();
    return $query->row_array();
}

function lang($parm) {
    $ci = &get_instance();
    return $ci->lang->line($parm);
}

function get_languages() {
    $ci = &get_instance();
    $query = $ci->db->get_where(LANG, array('is_active', '1'));
    return $query->result_array();
}

function get_column($table, $column, $where) {
    $ci = &get_instance();
    $query = $ci->db->get_where($table, $where);
    $res = $query->row_array();
    return $res[$column];
}

function showToastr() {
    $ci = &get_instance();
    $data = $ci->session->flashdata('toastr');
    if (isset($data['type'])) {
        $output = "<script>";
        if ($data['type'] == 'error') {
            $output .= "toastr.error('{$data['msg']}')";
        } else if ($data['type'] == 'success') {
            $output .= "toastr.success('{$data['msg']}')";
        }
        $output .= "</script>";
        return $output;
    }
    return "";
}

function userLoggedIn() {
    $ci = &get_instance();
    if ($ci->session->has_userdata('userSession')) {
        return $ci->session->userdata('userSession')['user_id'];
    }
    return false;
}

function save_to_files($field_name, $folder_name) {
    addtime_tofilename();

    $response = do_upload($field_name, $folder_name);
    if ($response['val'] == 1) {
        $ci = &get_instance();
        $name = $response['file_name'];
        $fileArr = array(
            'name' => $name,
            'type' => $response['file_type'],
            'size' => $response['file_size'],
            'path' => $folder_name . '/' . $name,
            'created_at' => date('Y-m-d H:i:s')
        );
        //compress_image(UPLOADS . $folder_name . '/' . $name, UPLOADS . $folder_name . '/' . $name, 80);
        return $ci->crud->insert(FILES, $fileArr);
    } else {
        return array('error' => $response['error']);
    }
}

function compress_image($source_url, $destination_url, $quality) {
    $info = getimagesize($source_url);
    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);
    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);
    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);
    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

function save_base64_image($base64, $path = UPLOADS) {
    $ci = & get_instance();
    $base64img = str_replace('data:image/png;base64,', '', $base64);
    $data = base64_decode($base64img);
    $name = time() . "_" . userLoggedIn() . ".png";
    $file = $path . $name;
    if (!file_exists(UPLOADS . $path)) {
        mkdir(UPLOADS . $path, 0777, true);
    }
    file_put_contents(UPLOADS . $file, $data);
    //compress_image(UPLOADS . $file, UPLOADS . $file, 80);
//    $name = $response['file_name'];
    $fileArr = array(
        'name' => $name,
        'type' => 'image/png',
        'size' => '',
        'path' => $file,
        'created_at' => date('Y-m-d H:i:s')
    );
    return $ci->crud->insert(FILES, $fileArr);
}

function addtime_tofilename() {
    foreach ($_FILES as $key => $file) {
        $_FILES[$key]['name'] = time() . "_" . $file['name'];
    }
}

function get_typefrom_id($id) {
    $ci = & get_instance();
    $ci->db->select("ft.*");
    $ci->db->from(FAQTYPE . " ft");
    $ci->db->where("ft.id", $id);
//$ci->db->distinct();
    $query_type = $ci->db->get();
    if ($query_type->num_rows() > 0) {
        $details = $query_type->result_array();
        return $details[0]['title'];
    }
}

function check_lead_id() {
    $ci = & get_instance();
    
     $rand_no = md5(rand(11111, 99999))."_lead";
    
    
    $qur = $ci->db->get_where(TABLE_LEAD, array('lead_id' => $rand_no));
    if($qur->num_rows() > 0) {
        return 0;
    }else{
        return $rand_no;
    }
}

function get_lead_id() {
   if(check_lead_id()){
       $rand_no = check_lead_id();
        return $rand_no;
    }else{
        check_lead_id();
    }
}
/*
 * This function will return live ETH price from CoinMarketCap website using Ticker
 */
function getCryptoLivePrice(){
    
    //$url = "https://api.coinmarketcap.com/v1/ticker/ethereum/";
    $url = "https://api.coinmarketcap.com/v2/ticker/?limit=10&structure=array";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    $transaction = json_decode($data, TRUE);
    curl_close($ch);
    
    //BTC
    $btcUsdPrice = 0;
    $cryptoSymbol_1 = isset($transaction['data'][0]['symbol'])?$transaction['data'][0]['symbol']:'';
    
    if($cryptoSymbol_1=="BTC"){
        $btcUsdPrice = isset($transaction['data'][0]['quotes']['USD']['price'])?$transaction['data'][0]['quotes']['USD']['price']:0;
    }
    //ETH
    $ethUsdPrice = 0;
    $cryptoSymbol_2 = isset($transaction['data'][1]['symbol'])?$transaction['data'][1]['symbol']:'';
    if($cryptoSymbol_2=="ETH"){
        $ethUsdPrice = isset($transaction['data'][1]['quotes']['USD']['price'])?$transaction['data'][1]['quotes']['USD']['price']:0;
    }
    //LTC
    $ltcUsdPrice = 0;
    $cryptoSymbol_3 = isset($transaction['data'][5]['symbol'])?$transaction['data'][5]['symbol']:'';
    if($cryptoSymbol_3=="LTC"){
        $ltcUsdPrice = isset($transaction['data'][5]['quotes']['USD']['price'])?$transaction['data'][5]['quotes']['USD']['price']:0;
    }    
    
    return ['eth_price_usd'=>$ethUsdPrice,'btc_price_usd'=>$btcUsdPrice,'ltc_price_usd'=>$ltcUsdPrice];
}

/*
* Get ICO user details
Sep-15-2018
Dev-S
*/
function get_user_details_byid($user_id) {

    $ci = & get_instance();
    $qur = $ci->db->query("SELECT * FROM `users` u INNER JOIN `users_profiles` up ON u.id = up.user_id WHERE u.id=".$user_id);
    if($qur->num_rows() > 0) {
        $row = $qur->result_array();
        $email = $row[0]['email'];
        $f_name = $row[0]['f_name'];
        return ['email'=>$email,'f_name'=>$f_name];
    }else{
        return [];
    }
}