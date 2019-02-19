<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Cms extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
    }
    
    public function pages() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'page_list';
        $data['title'] = 'Pages list';
        $data['cms'] = 'active';
        $data['active'] = 'pages';
        $data['list'] = $this->cms_model->getPageType();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_page() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'page_add';
        $data['title'] = 'Page Add';
        $data['cms'] = 'active';
        $data['active'] = 'pages';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function page_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'title' => $input['title'],
                    'left_desc' => $input['left_description'],
                    'right_desc' => $input['right_description']
                );
                $insert_id = 0;
                if(isset($input['page_id']) && $input['page_id'] != '') {
                    $result = $this->crud->get(PAGE_TYPE, array('id' => $input['page_id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(PAGE_TYPE, $addData, array('id' => $input['page_id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(PAGE_TYPE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/pages'));
    }
    
    public function page_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(PAGE_TYPE, array('id' => $id));
            if(!empty($list)) {
                $data['view_file'] = 'page_add';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'pages';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/pages'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/pages'));
        }
    }
    
    public function page_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(PAGE_TYPE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(PAGE_TYPE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function why_us() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'why_us/why_list';
        $data['title'] = 'Why us list';
        $data['cms'] = 'active';
        $data['active'] = 'whyus';
        $data['list'] = $this->cms_model->getwhyUs();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_why() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'why_us/why_add';
        $data['title'] = 'Page Why us';
        $data['cms'] = 'active';
        $data['active'] = 'whyus';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function why_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/whyus');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'file_id' => $media_id,
                            'color_code' => $input['color_code']
                        );
                    } else {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'color_code' => $input['color_code']
                        );
                    }
                    $insert_id = 0;
                    if(isset($input['why_id']) && $input['why_id'] != '') {
                        $result = $this->crud->get(WHY_US, array('id' => $input['why_id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(WHY_US, $addData, array('id' => $input['why_id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(WHY_US, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Why us page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/why_us'));
    }
    
    public function why_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getwhyUsEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'why_us/why_add';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'whyus';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/why_us'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/why_us'));
        }
    }
    
    public function why_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(WHY_US, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(WHY_US, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Why us page deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function home() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'home/home_list';
        $data['title'] = 'Home List';
        $data['cms'] = 'active';
        $data['active'] = 'home_with_button';
        $data['home_list'] = $this->cms_model->get_home();
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_home() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'home/home_add';
        $data['title'] = 'Page Home';
        $data['cms'] = 'active';
        $data['active'] = 'home_with_button';
        echo Modules::run('template/adminPanel', $data);
    }
    public function home_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]|max_length[500]');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('button_link', 'Button Link', 'trim|required');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/home');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'file_id' => $media_id,
                            'sub_description' => isset($input['sub_description']) ? $input['sub_description'] : '',
                            'price' => $input['price'],
                            'button_text' => $input['button_text'],
                            'button_link' => $input['button_link'],
                            'color_code' => $input['color_code'],
                            'class_name' => $input['class_name']
                        );
                    } else {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'sub_description' => isset($input['sub_description']) ? $input['sub_description'] : '',
                            'price' => $input['price'],
                            'button_text' => $input['button_text'],
                            'button_link' => $input['button_link'],
                            'color_code' => $input['color_code'],
                            'class_name' => $input['class_name']
                        );
                    }
                    $insert_id = 0;
                    if(isset($input['home_id']) && $input['home_id'] != '') {
                        $result = $this->crud->get(HOME, array('id' => $input['home_id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(HOME, $addData, array('id' => $input['home_id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(HOME, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/home'));
    }
    
    public function home_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->gethomesEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'home/home_add';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'home_with_button';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/home'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/home'));
        }
    }
    
    public function home_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(HOME, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(HOME, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home page deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function faq() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'faq';
        $data['title'] = 'FAQ';
        $data['cms'] = 'active';
        $data['active'] = 'faq';
        $data['faq'] = $this->cms_model->get_faq();
        //echo "<pre>"; print_r($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function faq_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getfaqsEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'faq_edit';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'faq';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/faq'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/faq'));
        }
    }
    public function faq_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);
            //pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            //$this->form_validation->set_rules('heading', 'Heading', 'trim|required');
            //$this->form_validation->set_rules('sub-heading', 'Sub Heading', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                //pre($media_id); die;
                    
                    $addData = array(
                        'title' => $input['title'],
                        'heading' => $input['heading'],
                        'sub_heading' => $input['sub-heading']
                    );
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(FAQ, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(FAQ, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(FAQ, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'FAQ page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
               
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/faq'));
    }
    
    public function faq_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(FAQ, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(FAQ, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home title deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function faq_type() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'faq_type_listing';
        $data['title'] = 'FAQ Type';
        $data['cms'] = 'active';
        $data['active'] = 'faq_type';
        $data['faq_type'] = $this->cms_model->get_faq_type();
        //echo "<pre>"; print_r($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
     public function add_faq_type() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_faq_type';
        $data['title'] = 'Page FAQ type';
        $data['cms'] = 'active';
        $data['active'] = 'faq_type';
        echo Modules::run('template/adminPanel', $data);
    }
    
    
    public function services() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/services_list';
        $data['title'] = 'Services';
        $data['cms'] = 'active';
        $data['active'] = 'services';
        $data['list'] = $this->cms_model->getServices();
        //echo "<pre>"; print_r($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_service() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/add_services';
        $data['title'] = 'Page Services';
        $data['cms'] = 'active';
        $data['active'] = 'services';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function service_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            //$this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[5]|max_length[500]');
            $this->form_validation->set_rules('link', 'Link', 'trim|required');
            
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/service');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["image"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["description"] = $input['description'];
                    $addData["button_text"] = $input['button_text'];
                    $addData["link"] = $input['link'];
                    $addData['color_code'] = $input['color_code'];
                    
                    $insert_id = 0;
                    if(isset($input['ser_id']) && $input['ser_id'] != '') {
                        $result = $this->crud->get(SERVICES, array('id' => $input['ser_id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(SERVICES, $addData, array('id' => $input['ser_id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(SERVICES, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Service '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/services'));
    }
    
    public function service_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getServiceByID($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'services/add_services';
                $data['title'] = 'Service Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'services';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/services'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/services'));
        }
    }
    
    public function service_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(SERVICES, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(SERVICES, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Service deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function sub_service($id) {
        validateLogin();
        if (isset($id)) {
            $active = ($id == '1') ? 'school_trips' : (($id == '2') ? 'sports_teams' : 'oversease');
            $list = $this->cms_model->getSubServiceByID($id);
            $data['view_file'] = 'services_sub';
            $data['title'] = 'Service Edit';
            $data['module'] = 'admin';
            $data['cms'] = 'active';
            $data['active'] = $active;
            $data['list'] = [];
            if(!empty($list)) {
                $data['list'] = $list;
            }
            //pre($data); die;
            echo Modules::run('template/adminPanel', $data);
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/services'));
        }
    }
    
    public function sub_service_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('subtitle', 'Sub Title', 'trim|required');
            if($input['type'] == "school_trip"){
                $this->form_validation->set_rules('sub_desc', 'Sub Description', 'trim|required');
            }
            
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            $this->form_validation->set_rules('left_description', 'Left Description', 'trim|required');
            $this->form_validation->set_rules('right_description', 'Right Description', 'trim|required');
            $this->form_validation->set_rules('content', 'Content', 'trim|required');
            $this->form_validation->set_rules('quote_text', 'Quote Text', 'trim|required');
            $this->form_validation->set_rules('quote_link', 'Quote link', 'trim|required');
           
            
            
            //if validation fails
            if ($this->form_validation->run() == FALSE) { 
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id1 = save_to_files('userfile', 'admin/service/school');
                }
                if ($_FILES && $_FILES['userfile1']['name'] != "") {
                    $media_id2 = save_to_files('userfile1', 'admin/service/sports');
                }
                if ($_FILES && $_FILES['userfile2']['name'] != "") {
                    $media_id3 = save_to_files('userfile2', 'admin/service/oversease');
                }
                if (isset($media_id1['error']) || isset($media_id2['error']) || isset($media_id3['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => "Error occur while upload image. Please try again later.")));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["image_first"] = $media_id1;
                    }
                    if ($_FILES && $_FILES['userfile1']['name'] != "") {
                        $addData["image_two"] = $media_id2;
                    }
                    if ($_FILES && $_FILES['userfile2']['name'] != "") {
                        $addData["image_three"] = $media_id3;
                    }
                    $addData["title"] = $input['title'];
                    $type = $input['type'];
                    $addData["subtitle"] = isset($input['subtitle']) ? $input['subtitle'] : '';
                    if($input['type'] == "school_trip"){
                        $addData["sub_desc"] = isset($input['sub_desc']) ? $input['sub_desc'] : '';
                    }
                    $addData["left_desc"] = isset($input['left_description']) ? $input['left_description'] : '';
                    $addData["right_desc"] = isset($input['right_description']) ? $input['right_description'] : '';
                    $addData["content "] = isset($input['content']) ? $input['content'] : '';
                    $addData["quote_text"] = isset($input['quote_text']) ? $input['quote_text'] : '';
                    $addData["quote_link"] = isset($input['quote_link']) ? $input['quote_link'] : '';
                    $addData["description"] = isset($input['description']) ? $input['description'] : '';
                    $addData["color_code"] = isset($input['color_code']) ? $input['color_code'] : '';
                    
                    $insert_id = 0;
                    if(isset($input['ser_id']) && $input['ser_id'] != '') {
                        $result = $this->crud->get(SUB_SERVICES, array('id' => $input['ser_id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(SUB_SERVICES, $addData, array('id' => $input['ser_id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(SUB_SERVICES, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => $addData["title"]." ".$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms').'/'.$type);
//       redirect(base_url('admin/cms/over_trip'));
    }
    
    public function faq_type_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]|max_length[500]');
          
           //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/faq_type');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(FAQTYPE, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(FAQTYPE, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(FAQTYPE, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Service '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/faq_type'));
    }
    
    public function faq_type_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getfaq_typeEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'add_faq_type';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'faq_type';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/faq_type'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/faq_type'));
        }
    }
    
    public function faq_type_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(FAQTYPE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(FAQTYPE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'FAQ Type deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function question_listing() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'question_listing';
        $data['title'] = 'Question Listing';
        $data['cms'] = 'active';
        $data['active'] = 'questions';
        if(isset($_GET["fval"]) && !empty($_GET["fval"])){
            $filter = $_GET["fval"];
            $data['questions'] = $this->cms_model->questions_listing_withfilter($filter);
        }
        else{
            $data['questions'] = $this->cms_model->questions_listing();
        }
        
        $data['all_types'] = $this->cms_model->type_list();
        //echo "<pre>"; print_r($data['faq_type']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    public function questions_add() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_questions';
        $data['title'] = 'Page FAQ';
        $data['cms'] = 'active';
        $data['active'] = 'questions';
        $data['all_types'] = $this->cms_model->type_list();
        //echo "<pre>"; print_r($data['all_types']); die("fhf");
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function questions_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);
            //pre($input); die;
            $this->form_validation->set_rules('faqtype', 'Type', 'trim|required');
            $this->form_validation->set_rules('question', 'Question', 'trim|required');
            $this->form_validation->set_rules('answer', 'Answer', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                
                $addData = array(
                            'u_type' => $input['faqtype'],
                            'question' => $input['question'],
                            'answer' => $input['answer']
                        );
                  
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(QUESTIONS, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(QUESTIONS, $addData, array('id' => $input['id']));
                            //echo $input['id'];
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(QUESTIONS, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Questions page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
               
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/question_listing'));
    }
    
    public function questions_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getquestion_typeEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'add_questions';
                $data['title'] = 'Page Edit';
                $data['all_types'] = $this->cms_model->type_list();
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'questions';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/question_listing'));
                
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/question_listing'));
        }
    }
    
    public function questions_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(QUESTIONS, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(QUESTIONS, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Question deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function question_listing_filter() {
//        $data['questions'] = $this->cms_model->questions_listing_search($_POST['fid']);
//        $result='';
//        $sr=1;
//        foreach ($data['questions'] as $key => $question) {
//            $result .= '<tr>'
//                    . '<td>'.$sr.'</td>'
//                    . '<td>'.$question['title'].'</td>'
//                    . '<td>'.$question['question'].'</td>'
//                    . '<td>'.$question['answer'].'</td>'
//                    . '</tr>';
//            $sr++;
//        }
//        echo $result;
        //echo "<pre>"; print_r($data['questions']);
    }
    public function attractions() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'top_attraction/attractions';
        $data['title'] = 'Top Attractions';
        $data['cms'] = 'active';
        $data['active'] = 'attraction';
        $data['attractions'] = $this->cms_model->top_attractions();
        //echo "<pre>"; print_r($data['attractions']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_attraction() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'top_attraction/add_attraction';
        $data['title'] = 'Page Attraction';
        $data['cms'] = 'active';
        $data['active'] = 'attraction';
        echo Modules::run('template/adminPanel', $data);
    }
    // new function to add attractions
    
    public function attraction_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);
            //pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/attractions');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["description"] = $input['description'];
                    $addData["view_type"] = $input['view_type'];
                    $addData["option_1_title"] = $input['option_1_title'];
                    $addData["option_1_price"] = $input['option_1_price'];
                    $addData["option_1_button"] = $input['option_1_button'];
                    $addData["option_2_title"] = $input['option_2_title'];
                    $addData["option_2_price"] = $input['option_2_price'];
                    $addData["option_2_button"] = $input['option_2_button'];
                    $addData["option_3_title"] = $input['option_3_title'];
                    $addData["option_3_price"] = $input['option_3_price'];
                    $addData["option_3_button"] = $input['option_3_button'];
                    $addData["option_4_title"] = $input['option_4_title'];
                    $addData["option_4_price"] = $input['option_4_price'];
                    $addData["option_4_button"] = $input['option_4_button'];
                    $addData["color_code"] = $input['color_code'];
                    $addData["address"] = $input['address'];
                    
                    
                    
                    /*if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'file_id' => $media_id,
                            'view_type' => $input['view_type'],
                            'option_1_title' => $input['option_1_title'],
                            'option_1_price' => $input['option_1_price'],
                            'option_1_button' => $input['option_1_button'],
                            //'option_1_link' => $input['option_1_link'],
                            'option_2_title' => $input['option_2_title'],
                            'option_2_price' => $input['option_2_price'],
                            'option_2_button' => $input['option_2_button'],
                            //'option_2_link' => $input['option_2_link'],
                            'option_3_title' => $input['option_3_title'],
                            'option_3_price' => $input['option_3_price'],
                            'option_3_button' => $input['option_3_button'],
                            //'option_3_link' => $input['option_3_link'],
                            'option_4_title' => $input['option_4_title'],
                            'option_4_price' => $input['option_4_price'],
                            'option_4_button' => $input['option_4_button'],
                            //'option_4_link' => $input['option_4_link'],
                            'color_code' => $input['color_code'],
                            'address' => $input['address']
                        );
                    } else {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'option_1_title' => $input['option_1_title'],
                            'option_1_price' => $input['option_1_price'],
                            'option_1_button' => $input['option_1_button'],
                            //'option_1_link' => $input['option_1_link'],
                            'option_2_title' => $input['option_2_title'],
                            'option_2_price' => $input['option_2_price'],
                            'option_2_button' => $input['option_2_button'],
                            //'option_2_link' => $input['option_2_link'],
                            'option_3_title' => $input['option_3_title'],
                            'option_3_price' => $input['option_3_price'],
                            'option_3_button' => $input['option_3_button'],
                            //'option_3_link' => $input['option_3_link'],
                            'option_4_title' => $input['option_4_title'],
                            'option_4_price' => $input['option_4_price'],
                            'option_4_button' => $input['option_4_button'],
                            //'option_4_link' => $input['option_4_link'],
                            'color_code' => $input['color_code'],
                            'address' => $input['address']
                        );
                    }*/
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(ATTRACTIONS, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(ATTRACTIONS, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(ATTRACTIONS, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Attraction page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/attractions'));
    }
    
     public function attraction_edit($id) {
         validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getattractionEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'top_attraction/add_attraction';
                $data['title'] = 'Page Edit';
               // $data['all_types'] = $this->cms_model->type_list();
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'attraction';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/attractions'));
                
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/attractions'));
        }
    }
    
     public function attraction_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(ATTRACTIONS, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(ATTRACTIONS, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Attraction deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function events() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/events_listing';
        $data['title'] = 'Add Events';
        $data['cms'] = 'active';
        $data['active'] = 'events';
        $data['events'] = $this->cms_model->events_listing();
        //$data['all_types'] = $this->cms_model->type_list();
        //echo "<pre>"; print_r($data['events']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
     public function add_events() {
        //validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/add_events';
        $data['title'] = 'Page Events';
        $data['cms'] = 'active';
        $data['active'] = 'events';
        echo Modules::run('template/adminPanel', $data);
    }
    
     public function events_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('heading', 'Heading', 'trim|required');
            $this->form_validation->set_rules('sub_heading', 'Sub Heading', 'trim|required');
            $this->form_validation->set_rules('content', 'Content', 'trim|required');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "" && $_FILES && $_FILES['userfile1']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/add_events');
                    $media_id2 = save_to_files('userfile1', 'admin/add_events');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    if ($_FILES && $_FILES['userfile']['name'] != "" && $_FILES && $_FILES['userfile1']['name'] != "") {
                        $addData = array(
                            'title' => $input['title'],
                            'heading' => $input['heading'],
                            'sub_heading'=> $input['sub_heading'],
                            'file_id_1' => $media_id,
                            'file_id_2' => $media_id2,
                            'content'=> $input['content'],
                            'button_text'=> $input['button_text'],
                            'button_link'=> $input['button_link'],
                            'color_code' => $input['color_code']
                        );
                    } else {
                        $addData = array(
                            'title' => $input['title'],
                            'heading' => $input['heading'],
                            'sub_heading'=> $input['sub_heading'],
                            'content'=> $input['content'],
                            'button_text'=> $input['button_text'],
                            'button_link'=> $input['button_link'],
                            'color_code' => $input['color_code']
                        );
                    }
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(EVENTS, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(EVENTS, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(EVENTS, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Events page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/events'));
    }
    
    public function events_edit($id) {
        if (isset($id)) {
            $list = $this->cms_model->geteventsEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'services/add_events';
                $data['title'] = 'Event Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'events';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/events'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/events'));
        }
    }
    public function events_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(EVENTS, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(EVENTS, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Events deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function home_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'home/home_title';
        $data['title'] = 'Home Title';
        $data['cms'] = 'active';
        $data['active'] = 'home_title';
        $data['home_title'] = $this->cms_model->get_home_title();
        echo Modules::run('template/adminPanel', $data);
    }
    public function home_title_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->gethome_titleEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'home/home_title_edit';
                $data['title'] = 'Home Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'home_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/home_title'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/home_title'));
        }
    }
    public function home_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                //pre($media_id); die;
                    
                    $addData = array(
                        'title' => $input['title'],
                        'sub_title' => $input['sub_title'],
                        'description' => $input['description']
                    );
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(HOMETITLE, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(HOMETITLE, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(HOMETITLE, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
               
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/home_title'));
    }
    /*public function home_without_button() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'home/home_without_list';
        $data['title'] = 'Home List';
        $data['cms'] = 'active';
        $data['active'] = 'home_without_button';
        $data['home_list'] = $this->cms_model->get_without_home();
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_home_without() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'home/home_add_without';
        $data['title'] = 'Page Home';
        $data['cms'] = 'active';
        $data['active'] = 'home_without_button';
        echo Modules::run('template/adminPanel', $data);
    }
    public function home_save_without() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]|max_length[500]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('button_link', 'Button Link', 'trim|required');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/home');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'file_id' => $media_id,
                            'button_text' => $input['button_text'],
                            'button_link' => $input['button_link'],
                            'color_code' => $input['color_code']
                        );
                    } else {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'button_text' => $input['button_text'],
                            'button_link' => $input['button_link'],
                            'color_code' => $input['color_code']
                        );
                    }
                    $insert_id = 0;
                    if(isset($input['home_id']) && $input['home_id'] != '') {
                        $result = $this->crud->get(HOME, array('id' => $input['home_id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(HOME, $addData, array('id' => $input['home_id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(HOME, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/home_without_button'));
    }
    public function home_edit_without($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->gethomesEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'home/home_add_without';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'home_without_button';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/home'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/home'));
        }
    }*/
    public function home_box() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'home_box_listing';
        $data['title'] = 'Home Blocks';
        $data['cms'] = 'active';
        $data['active'] = 'home_box';
        $data['home_box'] = $this->cms_model->get_home_box();
        //echo "<pre>"; print_r($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_home_box() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_home_box';
        $data['title'] = 'Page Home Boxes';
        $data['cms'] = 'active';
        $data['active'] = 'home_box';
        echo Modules::run('template/adminPanel', $data);
    }
    public function home_box_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
           
           //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/faq_type');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["description"] = $input['description'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(HOMEBOX, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(HOMEBOX, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(HOMEBOX, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home Box '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/home_box'));
    }
    public function home_box_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->gethome_boxEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'add_home_box';
                $data['title'] = 'Page Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'home_box';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/home_box'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/home_box'));
        }
    }
     public function home_box_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(HOMEBOX, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(HOMEBOX, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'FAQ Type deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
     public function attractions_service() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/attractions_service';
        $data['title'] = 'Top Services';
        $data['cms'] = 'active';
        $data['active'] = 'attraction_service';
        $data['attractions'] = $this->cms_model->top_attractions_service();
        //echo "<pre>"; print_r($data['attractions']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_attraction_service() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/add_attraction_service';
        $data['title'] = 'Page Attraction';
        $data['cms'] = 'active';
        $data['active'] = 'attraction_service';
        echo Modules::run('template/adminPanel', $data);
    }
    public function attraction_save_service() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);
            //pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/attractions');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["description"] = $input['description'];
                    $addData["view_type"] = $input['view_type'];
                    $addData["option_1_title"] = $input['option_1_title'];
                    $addData["option_1_price"] = $input['option_1_price'];
                    $addData["option_1_button"] = $input['option_1_button'];
                    $addData["option_2_title"] = $input['option_2_title'];
                    $addData["option_2_price"] = $input['option_2_price'];
                    $addData["option_2_button"] = $input['option_2_button'];
                    $addData["option_3_title"] = $input['option_3_title'];
                    $addData["option_3_price"] = $input['option_3_price'];
                    $addData["option_3_button"] = $input['option_3_button'];
                    $addData["option_4_title"] = $input['option_4_title'];
                    $addData["option_4_price"] = $input['option_4_price'];
                    $addData["option_4_button"] = $input['option_4_button'];
                    $addData["color_code"] = $input['color_code'];
                    $addData["address"] = $input['address'];
                    
                    /*if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'file_id' => $media_id,
                            'view_type' => $input['view_type'],
                            'option_1_title' => $input['option_1_title'],
                            'option_1_price' => $input['option_1_price'],
                            'option_1_button' => $input['option_1_button'],
                            'option_1_link' => $input['option_1_link'],
                            'option_2_title' => $input['option_2_title'],
                            'option_2_price' => $input['option_2_price'],
                            'option_2_button' => $input['option_2_button'],
                            'option_2_link' => $input['option_2_link'],
                            'option_3_title' => $input['option_3_title'],
                            'option_3_price' => $input['option_3_price'],
                            'option_3_button' => $input['option_3_button'],
                            'option_3_link' => $input['option_3_link'],
                            'option_4_title' => $input['option_4_title'],
                            'option_4_price' => $input['option_4_price'],
                            'option_4_button' => $input['option_4_button'],
                            'option_4_link' => $input['option_4_link'],
                            'color_code' => $input['color_code'],
                            'address' => $input['address']
                        );
                    } else {
                        $addData = array(
                            'title' => $input['title'],
                            'description' => $input['description'],
                            'option_1_title' => $input['option_1_title'],
                            'option_1_price' => $input['option_1_price'],
                            'option_1_button' => $input['option_1_button'],
                            'option_1_link' => $input['option_1_link'],
                            'option_2_title' => $input['option_2_title'],
                            'option_2_price' => $input['option_2_price'],
                            'option_2_button' => $input['option_2_button'],
                            'option_2_link' => $input['option_2_link'],
                            'option_3_title' => $input['option_3_title'],
                            'option_3_price' => $input['option_3_price'],
                            'option_3_button' => $input['option_3_button'],
                            'option_3_link' => $input['option_3_link'],
                            'option_4_title' => $input['option_4_title'],
                            'option_4_price' => $input['option_4_price'],
                            'option_4_button' => $input['option_4_button'],
                            'option_4_link' => $input['option_4_link'],
                            'color_code' => $input['color_code'],
                            'address' => $input['address']
                        );
                    }*/
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(ATTRACTIONS, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(ATTRACTIONS, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(ATTRACTIONS, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Attraction page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/attractions_service'));
    }
    public function attraction_service_edit($id) {
         validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getattractionEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'services/add_attraction_service';
                $data['title'] = 'Page Edit';
               // $data['all_types'] = $this->cms_model->type_list();
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'attraction_service';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/attractions'));
                
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/attractions_service'));
        }
    }
    public function home_title_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(HOMETITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(HOMETITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Home title deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function attraction_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'top_attraction/attraction_title_list';
        $data['title'] = 'Attraction Title';
        $data['cms'] = 'active';
        $data['active'] = 'attraction_title';
        $data['home_list'] = $this->cms_model->attraction_title();
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_attraction_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'top_attraction/add_attraction_title';
        $data['title'] = 'Page Attraction Title';
        $data['cms'] = 'active';
        $data['active'] = 'attraction_title';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function attraction_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'title' => $input['title']
                );
                    
                $insert_id = 0;
                if(isset($input['id']) && $input['id'] != '') {
                    $result = $this->crud->get(ATTRACTION_TITLE, array('id' => $input['id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(ATTRACTION_TITLE, $addData, array('id' => $input['id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(ATTRACTION_TITLE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Attraction page '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/attraction_title'));
    }
    
    public function attraction_title_edit($id) {
         validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getattractionTitleEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'top_attraction/add_attraction_title';
                $data['title'] = 'Edit Attraction Title';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'attraction_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/attraction_title'));
                
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/attraction_title'));
        }
    }
    
    public function attraction_title_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(ATTRACTION_TITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(ATTRACTION_TITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Attraction title deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function whyus_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'why_us/why_title_list';
        $data['title'] = 'Why us';
        $data['cms'] = 'active';
        $data['active'] = 'whyus_title';
        $data['list'] = $this->cms_model->getWhyUsTitle();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_whyus_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'why_us/why_title_add';
        $data['title'] = 'Why us Title';
        $data['cms'] = 'active';
        $data['active'] = 'whyus_title';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function why_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('left_description', 'Left Description', 'trim|required|min_length[3]|max_length[500]');
            $this->form_validation->set_rules('right_description', 'Right Description', 'trim|required|min_length[3]|max_length[500]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'title' => $input['title'],
                    'left_desc' => $input['left_description'],
                    'right_desc' => $input['right_description']
                );
                $insert_id = 0;
                if(isset($input['title_id']) && $input['title_id'] != '') {
                    $result = $this->crud->get(WHYUS_TITLE, array('id' => $input['title_id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(WHYUS_TITLE, $addData, array('id' => $input['title_id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(WHYUS_TITLE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/whyus_title'));
    }
    
    public function why_title_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(WHYUS_TITLE, array('id' => $id));
            if(!empty($list)) {
                $data['view_file'] = 'why_us/why_title_add';
                $data['title'] = 'Why Title Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'whyus_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/whyus_title'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/whyus_title'));
        }
    }
    
    public function why_title_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(WHYUS_TITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(WHYUS_TITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function service_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/service_title_list';
        $data['title'] = 'Service Title';
        $data['cms'] = 'active';
        $data['active'] = 'service_title';
        $data['list'] = $this->cms_model->getServiceTitle();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_ser_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/service_title_add';
        $data['title'] = 'Service Title';
        $data['cms'] = 'active';
        $data['active'] = 'service_title';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function ser_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            //$this->form_validation->set_rules('left_desc', 'Left Description', 'trim|required|min_length[3]');
            //$this->form_validation->set_rules('right_desc', 'Right Description', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'title' => $input['title'],
                    'left_desc' => $input['left_description'],
                    'right_desc' => $input['right_description']
                );
                $insert_id = 0;
                if(isset($input['title_id']) && $input['title_id'] != '') {
                    $result = $this->crud->get(SERVICE_TITLE, array('id' => $input['title_id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(SERVICE_TITLE, $addData, array('id' => $input['title_id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(SERVICE_TITLE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/service_title'));
    }
    
    public function ser_title_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(SERVICE_TITLE, array('id' => $id));
            if(!empty($list)) {
                $data['view_file'] = 'services/service_title_add';
                $data['title'] = 'Service Title Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'service_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/service_title'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/service_title'));
        }
    }
    
    public function ser_title_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(SERVICE_TITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(SERVICE_TITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function destination_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'destination_title_list';
        $data['title'] = 'Destination Title';
        $data['cms'] = 'active';
        $data['active'] = 'destination_title';
        $data['list'] = $this->cms_model->getDestinationTitle();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_dest_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'destination_title_add';
        $data['title'] = 'Destination Title';
        $data['cms'] = 'active';
        $data['active'] = 'destination_title';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function dest_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            //$this->form_validation->set_rules('left_desc', 'Left Description', 'trim|required|min_length[3]');
            //$this->form_validation->set_rules('right_desc', 'Right Description', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'title' => $input['title'],
                    'left_desc' => $input['left_description'],
                    'right_desc' => $input['right_description']
                );
                $insert_id = 0;
                if(isset($input['title_id']) && $input['title_id'] != '') {
                    $result = $this->crud->get(DESTINATION_TITLE, array('id' => $input['title_id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(DESTINATION_TITLE, $addData, array('id' => $input['title_id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(DESTINATION_TITLE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/destination_title'));
    }
    
    public function dest_title_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(DESTINATION_TITLE, array('id' => $id));
            if(!empty($list)) {
                $data['view_file'] = 'destination_title_add';
                $data['title'] = 'destination Title Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'destination_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/destination_title'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/destination_title'));
        }
    }
    
    public function dest_title_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(DESTINATION_TITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(DESTINATION_TITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Page deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function school_trip() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/school_trip_list';
        $data['title'] = 'School Trip';
        $data['cms'] = 'active';
        $data['active'] = 'school_trips';
        $data['list'] = $this->cms_model->getschooltrip(SERVICE_TRIP);
        echo Modules::run('template/adminPanel', $data);
    }
    public function service_trip_edit($id) {
        validateLogin();
        if (isset($id)) {
            $active = ($id == '1') ? 'school_trips' : (($id == '2') ? 'sports_teams' : 'oversease');
            $list = $this->cms_model->getSubServiceByID($id);
            //echo "<pre>"; print_r($list); die("bfh");
            $data['view_file'] = 'services/services_sub_edit';
            $data['title'] = $list[0]['title']." Edit"; //Service Edit';
            $data['module'] = 'admin';
            $data['cms'] = 'active';
            $data['active'] = $active;
            $data['listed'] = [];
            if(!empty($list)) {
                $data['listed'] = $list;
            }
            //pre($data); die;
            echo Modules::run('template/adminPanel', $data);
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/services'));
        }
    }
    public function sports_team() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/sports_team_list';
        $data['title'] = 'Sports Trip';
        $data['cms'] = 'active';
        $data['active'] = 'sports_teams';
        $data['list'] = $this->cms_model->getschooltrip(SPORTS_TEAM);
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function destination_prices() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'master/price_list';
        $data['title'] = 'Import Coach Prices';
        $data['cms'] = 'active';
        $data['active'] = 'prices';
        echo Modules::run('template/adminPanel', $data);
    }
    public function import_coach_prices() {  
        $this->load->library('excel');//load PHPExcel library
        
        if (isset($_FILES['importfile']['name'])) {
            $field_name = 'importfile';
            $folder_name = 'admin/excel_file';
            addtime_tofilename();

            $response = do_upload($field_name, $folder_name);
            if ($response['val'] == 1) {
                $name = $response['file_name'];
               
                include APPPATH . "third_party/PHPExcel/Classes/PHPExcel/IOFactory.php";
                
                $inputFileName = UPLOADS . $folder_name . '/' . $name;
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($inputFileName);
                $num = $objPHPExcel->getSheetCount();
                $sheetnames = $objPHPExcel->getSheetNames();
                $objWorksheet = $objPHPExcel->getActiveSheet();
                $highestRow = $objWorksheet->getHighestRow();
                $sheetnum = $num-1;
                
                $CurrentWorkSheetIndex = 0;
                
                $resultCityAll = $this->crud->getAll(CITIES, array());
                $cityArray = [];
                foreach($resultCityAll as $city) {
                    $cityArray[$city['name']] = $city['id'];
                }
                
                /*$cityArray = array(
                    'London' => 1,
                    'Manchester' => 2,
                    'Birmingham' => 5,
                    'Edinburgh' => 6,
                    'Bristol' => 7,
                    'Liverpool' => 8,
                    'Blackpool' => 9,
                    'Glasgow' => 10,
                    'Cardiff' => 11,
                    'Newcastle' => 12
                );*/

                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestDataRow();
                    $highestColumn = $worksheet->getHighestDataColumn();
                    $headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1,NULL,TRUE,FALSE);
                    
                    $sheetIndex = $CurrentWorkSheetIndex++;
                    $sheetName = $sheetnames[$sheetIndex];
                    $cityFrom = $cityArray[$sheetName];
                    //echo 'WorkSheet' . $sheetName;
                    for ($row = 3; $row <= $highestRow; $row++) {
                        $data = [];
                        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $resultRow = $rowData[0];
                        $cityTo = $cityArray[$resultRow[2]];
                        if($cityFrom == $cityTo) {
                            continue;
                        }
                        //pre($resultRow);
                        $cell1 = $worksheet->getCellByColumnAndRow(0, $row);
                        $leave_time = PHPExcel_Style_NumberFormat::toFormattedString($cell1->getCalculatedValue(), 'hh:mm:ss');
                        $cell2 = $worksheet->getCellByColumnAndRow(1, $row);
                        $return_time = PHPExcel_Style_NumberFormat::toFormattedString($cell2->getCalculatedValue(), 'hh:mm:ss');
                        $cell3 = $worksheet->getCellByColumnAndRow(18, $row);
                        $journey_time = PHPExcel_Style_NumberFormat::toFormattedString($cell3->getCalculatedValue(), 'hh:mm:ss');
                        $cell4 = $worksheet->getCellByColumnAndRow(19, $row);
                        $arrival = PHPExcel_Style_NumberFormat::toFormattedString($cell4->getCalculatedValue(), 'hh:mm:ss');
                        $cell5 = $worksheet->getCellByColumnAndRow(20, $row);
                        $return_arival = PHPExcel_Style_NumberFormat::toFormattedString($cell5->getCalculatedValue(), 'hh:mm:ss');
                        $cell6 = $worksheet->getCellByColumnAndRow(21, $row);
                        $duration_hrs = PHPExcel_Style_NumberFormat::toFormattedString($cell6->getCalculatedValue(), 'hh:mm:ss');
                        //$rowData[0] = array_combine($headings[0], $rowData[0]);
                        $data = array(
                            'city_from' => $cityFrom,
                            'city_to' => $cityTo,
                            'leave_time' => $leave_time,
                            'return_time' => $return_time,
                            'postcode' => $resultRow[3],
                            'sixt_mini_full' => round($resultRow[4]),
                            'sixt_mini_pp' => round($resultRow[5]),
                            'fe_standard_full' => round($resultRow[6]),
                            'fe_standard_pp' => round($resultRow[7]),
                            'fe_executive_full' => round($resultRow[8]),
                            'fe_executive_pp' => round($resultRow[9]),
                            'sf_executive_full' => round($resultRow[10]),
                            'sf_executive_pp' => round($resultRow[11]),
                            'twentyf_vip_full' => round($resultRow[12]),
                            'twentyf_vip_pp' => round($resultRow[13]),
                            'thirtyf_vip_full' => round($resultRow[14]),
                            'thirtyf_vip_pp' => round($resultRow[15]),
                            'distance' => $resultRow[16],
                            'total_distance' => $resultRow[17],
                            'journey_time' => $journey_time,
                            'arrival' => $arrival,
                            'return_arival' => $return_arival,
                            'duration_hrs' => $duration_hrs,
                            'duration_calc' => $resultRow[22],
                            'xs_miles' => $resultRow[23]
                        );
                        
                        if(!empty($data)) {
                            $result = $this->crud->get(COACH_PRICES, array('city_from' => $cityFrom, 'city_to' => $cityTo));
                            if(!empty($result)) {
                                $insert_id = $this->crud->update(COACH_PRICES, $data, array('city_from' => $cityFrom, 'city_to' => $cityTo));
                            } else {
                                $insert_id = $this->crud->insert(COACH_PRICES, $data);
                            }
                        }
                    }
                }
                $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Prices imported successfully'));
                redirect(base_url('admin/cms/destination_prices'));
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => $response['error']));
                redirect(base_url('admin/cms/destination_prices'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => "Please select file to upload"));
            redirect(base_url('admin/cms/destination_prices'));
        }
    }
    public function over_trip() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'services/overseas_trip_list';
        $data['title'] = 'Overseas Trip';
        $data['cms'] = 'active';
        $data['active'] = 'oversease';
        $data['list'] = $this->cms_model->getschooltrip(OVERSEAS_TRIP);
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function city_list() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'destination/city_list';
        $data['title'] = 'City List';
        $data['cms'] = 'active';
        $data['active'] = 'city_list';
        $data['city_list'] = $this->cms_model->city_list();
        //echo "<pre>"; print_r($data['list']); echo "</pre>"; die("dgdg");
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_city() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'destination/add_city';
        $data['title'] = 'Add City';
        $data['cms'] = 'active';
        $data['active'] = 'city_list';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function city_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('left_side', 'Left Description', 'trim|required');
            $this->form_validation->set_rules('color_code', 'Color Code', 'trim|required|min_length[4]|max_length[7]');
           
           //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/city');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["image_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["left_side"] = $input['left_side'];
                    $addData['color_code'] = $input['color_code'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(CITY_LIST, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(CITY_LIST, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(CITY_LIST, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/city_list'));
    }
    public function city_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getcityEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'destination/add_city';
                $data['title'] = 'City Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'city_list';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/city_list'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/city_list'));
        }
    }
    public function city_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(CITY_LIST, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(CITY_LIST, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
     public function city_section() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'city_sections';
        $data['title'] = 'City Sections';
        $data['cms'] = 'active';
        $data['active'] = 'city_section';
        $data['city_sections'] = $this->cms_model->city_sections();
        //echo "<pre>"; print_r($data['list']); echo "</pre>"; die("dgdg");
        echo Modules::run('template/adminPanel', $data);
    }
     public function add_city_section() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_city_section';
        $data['title'] = 'Add City Section';
        $data['cms'] = 'active';
        $data['active'] = 'city_section';
        $data['all_cities'] = $this->cms_model->city_list();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function city_section_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[3]');
           
           
           //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/city_sections');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["section_image"] = $media_id;
                    }
                    $addData["section_title"] = $input['title'];
                    $addData["city_id"] = $input['city'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(CITY_SECTION, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(CITY_SECTION, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(CITY_SECTION, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City Section '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/city_section'));
    }
    
    public function city_section_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getcitysectionEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'add_city_section';
                $data['title'] = 'City Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'city_section';
                $data['all_cities'] = $this->cms_model->city_list();
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/city_list'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/city_section'));
        }
    }
    public function city_section_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(CITY_SECTION, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(CITY_SECTION, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City Section deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function city_images() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'city_sections_images';
        $data['title'] = 'City Sections Images';
        $data['cms'] = 'active';
        $data['active'] = 'city_images';
        if(isset($_GET["fval"]) && !empty($_GET["fval"])){
            $filter = $_GET["fval"];
            $data['city_images'] = $this->cms_model->city_sections_images(array('t.id' => $filter), '1');
        }
        else{
            $data['city_images'] = $this->cms_model->city_sections_images(array(), '1');
        }
        $data['city_list'] = $this->cms_model->get_city_info();
        //$data['city_images'] = $this->cms_model->city_sections_images();
        //echo "<pre>"; print_r($data['city_images']); echo "</pre>"; die("dgdg");
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_city_images() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_city_images';
        $data['title'] = 'Add City Images';
        $data['cms'] = 'active';
        $data['active'] = 'city_images';
        $data['all_cities'] = $this->cms_model->city_list();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function city_section_fetch(){
       $city_id = $_POST['city'];
       $result = '<option value="">Select Section</option>';
       $all_cities = $this->cms_model->city_sections_list($city_id);
       //echo "<pre>"; print_r($all_cities); echo "</pre>"; die("gdfg");
       foreach($all_cities as $key => $city_image){
           $result.= "<option value='".$city_image['id']."'>".$city_image['section_title']."</option>";
       }
       echo $result;
       
       
    }
     public function city_section_fetch_section(){
        $city_id = $_POST['city'];
        $section_id = $_POST['section_id'];
       $result = '<option value="">Select Section</option>';
       $all_cities = $this->cms_model->city_sections_list($city_id);
       //echo "<pre>"; print_r($all_cities); echo "</pre>"; die("gdfg");
       foreach($all_cities as $key => $city_image){
           if($section_id == $city_image['id'])
               {
               $seect= "selected"; 
               }
           else{
               $seect = '';
               }
           
           $result.= "<option value='".$city_image['id']."' ".$seect." >".$city_image['section_title']."</option>";
       }
       echo $result;
    }
    public function popular_cities() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'city_popular_images';
        $data['title'] = 'Other Popular Destinations';
        $data['cms'] = 'active';
        $data['active'] = 'popular_cities';
        $data['city_images'] = $this->cms_model->city_sections_images(array('p.section_image' => '0'));
        //echo "<pre>"; print_r($data['city_images']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    public function add_popular_city() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_popular_city';
        $data['title'] = 'Add Popular Destinations';
        $data['cms'] = 'active';
        $data['active'] = 'popular_cities';
        $data['all_cities'] = $this->cms_model->city_list();
        echo Modules::run('template/adminPanel', $data);
    }
    public function popular_city_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); 
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('link', 'Link', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/popular_city');
                }
                if (isset($media_id['error'])) {
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else {
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["image_id"] = $media_id;
                    }
                    $addData["section_id"] = $this->cms_model->get_sectionID_cityID(array('w.city_id' => $input['city'], 'w.section_image' => '0'));
                    $addData["city_id"] = $input['city'];
                    $addData["image_name"] = $input['title'];
                    $addData["link"] = $input['link'];
                    //pre($input); die;
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(CITY_IMAGES, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(CITY_IMAGES, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(CITY_IMAGES, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Popular City Image '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/popular_cities'));
    }
    public function popular_city_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getcityimagesEditByID($id);
            //echo "<pre>"; print_r($list); die("fds");
            if(!empty($list)) {
                $data['view_file'] = 'add_popular_city';
                $data['title'] = 'Popular Destinations Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'popular_cities';
                $data['all_cities'] = $this->cms_model->city_list();
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/popular_cities'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/popular_cities'));
        }
    }
    public function popular_city_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(CITY_IMAGES, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(CITY_IMAGES, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City Images deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function contact() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'contact';
        $data['title'] = 'Contact';
        $data['cms'] = 'active';
        $data['active'] = 'contact';
        $data['contact'] = $this->cms_model->get_contact();
        echo Modules::run('template/adminPanel', $data);
    }
    public function contact_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getcontactEditByID($id);
            if(!empty($list)) {
                $data['view_file'] = 'contact_edit';
                $data['title'] = 'Contact Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'contact';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/contact'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/faq'));
        }
    }
    
    public function contact_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);
            //pre($input); die;
            $this->form_validation->set_rules('heading', 'Heading', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('sub_heading', 'Sub Heading', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('right_heading', 'Right Heading', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                //pre($media_id); die;
                    
                    $addData = array(
                        'heading' => $input['heading'],
                        'sub_heading' => $input['sub_heading'],
                        'address' => $input['address'],
                        'phone' =>$input['phone'],
                        'email' =>$input['email'],
                        'right_heading' =>$input['right_heading'],
                    );
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(CONTACT, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(CONTACT, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(CONTACT, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Contact page '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
               
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/contact'));
    }
    public function city_images_save() {
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($_FILES);pre($input); die;
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('city_sections', 'City Sections', 'trim|required');
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('link', 'Link', 'trim|required');
                
           
           
           //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/city_images');
                }
                if (isset($media_id['error'])) { //pre($media_id['error']); die;
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($media_id); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["image_id"] = $media_id;
                    }
                    $addData["section_id"] = $input['city_sections'];
                    $addData["city_id"] = $input['city'];
                    $addData["image_name"] = $input['title'];
                    $addData["link"] = $input['link'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(CITY_IMAGES, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(CITY_IMAGES, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(CITY_IMAGES, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City Images '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/city_images'));
    }
    
    public function city_images_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->getcityimagesEditByID($id);
            //echo "<pre>"; print_r($list); die("fdg");
            if(!empty($list)) {
                $data['view_file'] = 'add_city_images';
                $data['title'] = 'Edit City Images';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'city_images';
                $data['all_cities'] = $this->cms_model->city_list();
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/city_images'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            redirect(base_url('admin/cms/city_images'));
        }
    }
    
    public function city_images_delete() { 
        $input = $this->input->post(); //print_r($input['id']); die;
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(CITY_IMAGES, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(CITY_IMAGES, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City Images deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    public function privacy_policy() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'privacy_policy_list';
        $data['title'] = 'Privacy Policy';
        $data['cms'] = 'active';
        $data['active'] = 'privacy_policy';
        $data['list'] = $this->cms_model->get_privacy_policy(PRIVACY_POLICY_ID);
        echo Modules::run('template/adminPanel', $data);
    }
    public function terms_condition() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'privacy_policy_list';
        $data['title'] = 'Terms and Conditions';
        $data['cms'] = 'active';
        $data['active'] = 'terms_condition';
        $data['list'] = $this->cms_model->get_privacy_policy(TERMS_CONDITION_ID);
        echo Modules::run('template/adminPanel', $data);
    }
    public function policy_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(PRIVACY_POLICY, array('id' => $id));
            if(!empty($list)) {
                if($id == PRIVACY_POLICY_ID) {
                    $data['title'] = 'Edit Privacy Policy';
                    $data['active'] = 'privacy_policy';
                } else if($id == TERMS_CONDITION_ID) {
                    $data['title'] = 'Terms and Conditions Edit';
                    $data['active'] = 'terms_condition';
                }
                $data['view_file'] = 'privacy_policy_add';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/privacy_policy'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/privacy_policy'));
        }
    }
    
    public function privacy_policy_save() {
        $return = 'privacy_policy';
        $page = 'Privacy Policy';
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if($input['id'] == TERMS_CONDITION_ID) {
                    $return = 'terms_condition';
                    $page = 'Terms and Conditions';
                }
                $addData = array(
                    'title' => $input['title'],
                    'description' => $input['description']
                );
                $insert_id = 0;
                if(isset($input['id']) && $input['id'] != '') {
                    $result = $this->crud->get(PRIVACY_POLICY, array('id' => $input['id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(PRIVACY_POLICY, $addData, array('id' => $input['id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(PRIVACY_POLICY, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => $page.' '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/'.$return));
    }
    
    public function destination_learn_more() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'learn_more_list';
        $data['title'] = 'Destinations learn more';
        $data['cms'] = 'active';
        $data['active'] = 'learn_more';
        $data['list'] = $this->cms_model->get_learn_more();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_learn_more() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_learn_more';
        $data['title'] = 'Learn More Add';
        $data['cms'] = 'active';
        $data['active'] = 'learn_more';
        $data['all_cities'] = $this->cms_model->city_list();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function learn_more_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('btn_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('link', 'Link', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/learn_more');
                }
                if (isset($media_id['error'])) {
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($input); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["city_id"] = $input['city'];
                    $addData["button_text"] = $input['btn_text'];
                    $addData["link"] = $input['link'];
                    $addData["description"] = $input['description'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(DESTINATION_LEARN_MORE, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(DESTINATION_LEARN_MORE, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(DESTINATION_LEARN_MORE, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Learn more '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/destination_learn_more'));
    }
    
    public function learn_more_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_learn_more_by_id($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'add_learn_more';
                $data['title'] = 'Learn More Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'learn_more';
                $data['all_cities'] = $this->cms_model->city_list();
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/destination_learn_more'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/destination_learn_more'));
        }
    }
    
    public function learn_more_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(DESTINATION_LEARN_MORE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(DESTINATION_LEARN_MORE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function booking_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'booking_title';
        $data['title'] = 'Booking Title';
        $data['cms'] = 'active';
        $data['active'] = 'booking_title';
        $data['list'] = $this->cms_model->get_booking_title();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_booking_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_booking_title';
        $data['title'] = 'Booking Title Add';
        $data['cms'] = 'active';
        $data['active'] = 'booking_title';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function booking_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title_one', 'Title One', 'trim|required');
            $this->form_validation->set_rules('title_two', 'Title Two', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData["title_one"] = $input['title_one'];
                $addData["description_one"] = $input['description_one'];
                $addData["title_two"] = $input['title_two'];
                $addData["description_two"] = $input['description_two'];

                $insert_id = 0;
                if(isset($input['id']) && $input['id'] != '') {
                    $result = $this->crud->get(BOOKING_TITLE, array('id' => $input['id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(BOOKING_TITLE, $addData, array('id' => $input['id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(BOOKING_TITLE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Booking Title '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/booking_title'));
    }
    
    public function booking_title_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_booking_title_by_id($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'add_booking_title';
                $data['title'] = 'Booking Title Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'booking_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/booking_title'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/booking_title'));
        }
    }
    
    public function booking_title_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(BOOKING_TITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(BOOKING_TITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function booking_coach() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'booking_coach';
        $data['title'] = 'Booking Coach';
        $data['cms'] = 'active';
        $data['active'] = 'booking_coach';
        $data['list'] = $this->cms_model->get_booking_coach();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_booking_coach() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'add_booking_coach';
        $data['title'] = 'Booking Coach Add';
        $data['cms'] = 'active';
        $data['active'] = 'booking_coach';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function booking_coach_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('option_title', 'Option Title', 'trim|required');
            $this->form_validation->set_rules('option_value', 'Option Value', 'trim|required|numeric');
            $this->form_validation->set_rules('standard_title', 'Standard Title', 'trim|required');
            $this->form_validation->set_rules('standard_type', 'Standard Type', 'trim|required');
            $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
            $this->form_validation->set_rules('per_person', 'Price Per Person', 'trim|required|numeric');
            $this->form_validation->set_rules('button_title', 'Button Title', 'trim|required');
            $this->form_validation->set_rules('button_link', 'Button link', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/booking_coach');
                }
                if (isset($media_id['error'])) {
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($input); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["option_title"] = $input['option_title'];
                    $addData["option_value"] = $input['option_value'];
                    $addData["standard_title"] = $input['standard_title'];
                    $addData["standard_type"] = $input['standard_type'];
                    $addData["descrpition"] = $input['description'];
                    $addData["seat_title"] = $input['seat_title'];
                    $addData["seats"] = $input['seats'];
                    $addData["price"] = $input['price'];
                    $addData["per_person"] = $input['per_person'];
                    $addData["button_title"] = $input['button_title'];
                    $addData["button_link"] = $input['button_link'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(BOOKING_COACH_DETAILS, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(BOOKING_COACH_DETAILS, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(BOOKING_COACH_DETAILS, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Booking Coach '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/booking_coach'));
    }
    
    public function booking_coach_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_booking_coach_by_id($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'add_booking_coach';
                $data['title'] = 'Booking Coach Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'booking_coach';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/booking_coach'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/booking_coach'));
        }
    }
    
    public function booking_coach_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(BOOKING_COACH_DETAILS, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(BOOKING_COACH_DETAILS, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function booking_complete_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'booking_complete/booking_complete_title';
        $data['title'] = 'Booking Complete Title';
        $data['cms'] = 'active';
        $data['active'] = 'booking_complete_title';
        $data['list'] = $this->cms_model->get_booking_complete_title();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_booking_complete_title() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'booking_complete/add_booking_complete_title';
        $data['title'] = 'Booking Complete Title Add';
        $data['cms'] = 'active';
        $data['active'] = 'booking_complete_title';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function booking_complete_title_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData["title"] = $input['title'];
                $addData["button_text"] = $input['button_text'];
                $addData["description"] = $input['description'];

                $insert_id = 0;
                if(isset($input['id']) && $input['id'] != '') {
                    $result = $this->crud->get(BOOKING_COMPLETE_TITLE, array('id' => $input['id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(BOOKING_COMPLETE_TITLE, $addData, array('id' => $input['id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(BOOKING_COMPLETE_TITLE, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Booking Complete Title '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/booking_complete_title'));
    }
    
    public function booking_complete_title_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_booking_complete_title_by_id($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'booking_complete/add_booking_complete_title';
                $data['title'] = 'Booking Complete Title Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'booking_complete_title';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/booking_complete_title'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/booking_complete_title'));
        }
    }
    
    public function booking_complete_title_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(BOOKING_COMPLETE_TITLE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(BOOKING_COMPLETE_TITLE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function booking_complete_type() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'booking_complete/booking_complete_type';
        $data['title'] = 'Booking Complete Type';
        $data['cms'] = 'active';
        $data['active'] = 'booking_complete_type';
        $data['list'] = $this->cms_model->get_booking_complete_type();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_booking_complete_type() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'booking_complete/add_booking_complete_type';
        $data['title'] = 'Booking Complete Type Add';
        $data['cms'] = 'active';
        $data['active'] = 'booking_complete_type';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function booking_complete_type_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Title', 'trim|required');
            $this->form_validation->set_rules('title_popup', 'Pop up Title', 'trim|required');
            $this->form_validation->set_rules('box_button', 'Pop up Button Title', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                if ($_FILES && $_FILES['userfile']['name'] != "") {
                    $media_id = save_to_files('userfile', 'admin/booking_complete');
                }
                if (isset($media_id['error'])) {
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(array('msg' => $media_id['error'])));
                } else { //pre($input); die;
                    $addData = [];
                    if ($_FILES && $_FILES['userfile']['name'] != "") {
                        $addData["file_id"] = $media_id;
                    }
                    $addData["title"] = $input['title'];
                    $addData["description"] = $input['description'];
                    $addData["button_text"] = $input['button_text'];
                    $addData["title_popup"] = $input['title_popup'];
                    $addData["box_one"] = $input['box_one'];
                    $addData["box_two"] = $input['box_two'];
                    $addData["box_button"] = $input['box_button'];
                    
                    $insert_id = 0;
                    if(isset($input['id']) && $input['id'] != '') {
                        $result = $this->crud->get(BOOKING_COMPLETE_TYPE, array('id' => $input['id']));
                        if(!empty($result)) {
                            $insert_id = $this->crud->update(BOOKING_COMPLETE_TYPE, $addData, array('id' => $input['id']));
                            $value = 'updated';
                        }
                    } else {
                        $insert_id = $this->crud->insert(BOOKING_COMPLETE_TYPE, $addData);
                        $value = 'created';
                    }
                    if ($insert_id != 0) {
                        $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Booking Complete Type '.$value.' successfully'));
                    } else {
                        $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                    }
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/booking_complete_type'));
    }
    
    public function booking_complete_type_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_booking_complete_type_by_id($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'booking_complete/add_booking_complete_type';
                $data['title'] = 'Booking Complete Type Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'booking_complete_type';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/booking_complete_type'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/booking_complete_type'));
        }
    }
    
    public function booking_complete_type_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(BOOKING_COMPLETE_TYPE, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(BOOKING_COMPLETE_TYPE, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function bottom_links() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'bottom_links/bottom_links_list';
        $data['title'] = 'Bottom Links';
        $data['cms'] = 'active';
        $data['active'] = 'bottom_links';
        $data['list'] = $this->cms_model->get_bottom_links();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_bottom_links() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'bottom_links/add_bottom_links';
        $data['title'] = 'Bottom Links Add';
        $data['cms'] = 'active';
        $data['active'] = 'bottom_links';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function bottom_links_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('page', 'Page', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData["title"] = $input['title'];
                $addData["page"] = $input['page'];
                $addData["button_text"] = $input['button_text'];

                $insert_id = 0;
                if(isset($input['id']) && $input['id'] != '') {
                    $result = $this->crud->get(BOTTOM_LINKS, array('id' => $input['id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(BOTTOM_LINKS, $addData, array('id' => $input['id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(BOTTOM_LINKS, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Links '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/bottom_links'));
    }
    
    public function bottom_links_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_bottom_links_by_id($id);
            //pre($list); die;
            if(!empty($list)) {
                $data['view_file'] = 'bottom_links/add_bottom_links';
                $data['title'] = 'Bottom Links Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'bottom_links';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/bottom_links'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/bottom_links'));
        }
    }
    
    public function bottom_links_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(BOTTOM_LINKS, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(BOTTOM_LINKS, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function disclaimer() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'disclaimer/disclaimer';
        $data['title'] = 'Destination Disclaimer';
        $data['cms'] = 'active';
        $data['active'] = 'disclaimer';
        $data['list'] = $this->cms_model->get_disclaimer();
        //pre($data['list']); die;
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_disclaimer() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'disclaimer/add_disclaimer';
        $data['title'] = 'Destination Disclaimer Add';
        $data['cms'] = 'active';
        $data['active'] = 'disclaimer';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function disclaimer_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData["title"] = $input['title'];
                $addData["description"] = $input['description'];

                $insert_id = 0;
                if(isset($input['id']) && $input['id'] != '') {
                    $result = $this->crud->get(DESTINATION_DISCLAMER, array('id' => $input['id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(DESTINATION_DISCLAMER, $addData, array('id' => $input['id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(DESTINATION_DISCLAMER, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Disclaimer '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/disclaimer'));
    }
    
    public function disclaimer_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->cms_model->get_disclaimer_by_id($id);
            if(!empty($list)) {
                $data['view_file'] = 'disclaimer/add_disclaimer';
                $data['title'] = 'Destination Disclaimer Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'disclaimer';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/disclaimer'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/disclaimer'));
        }
    }
    
    public function disclaimer_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(DESTINATION_DISCLAMER, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(DESTINATION_DISCLAMER, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Record deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function attraction_disclaimer() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'top_attraction/attraction_disclaimer';
        $data['title'] = 'Top Attraction Disclaimer';
        $data['cms'] = 'active';
        $data['active'] = 'attraction_disclaimer';
        $data['list'] = $this->cms_model->get_attraction_disclaimer();
        echo Modules::run('template/adminPanel', $data);
    }
    public function attraction_disclaimer_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(ATTRACTIONS_DISCLAIMER, array('id' => $id));
            if(!empty($list)) {
                $data['title'] = 'Edit Top Attraction Disclaimer';
                $data['active'] = 'privacy_policy';
                $data['view_file'] = 'top_attraction/edit_attraction_disclaimer';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/cms/attraction_disclaimer'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/cms/attraction_disclaimer'));
        }
    }
    
    public function attraction_disclaimer_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $addData = array(
                'title' => $input['title'],
                'subtitle' => $input['subtitle'],
                'details' => $input['details']
            );
            $insert_id = 0;
            if(isset($input['id']) && $input['id'] != '') {
                $result = $this->crud->get(ATTRACTIONS_DISCLAIMER, array('id' => $input['id']));
                if(!empty($result)) {
                    $insert_id = $this->crud->update(ATTRACTIONS_DISCLAIMER, $addData, array('id' => $input['id']));
                    $value = 'updated';
                }
            } else {
                $insert_id = $this->crud->insert(ATTRACTIONS_DISCLAIMER, $addData);
                $value = 'created';
            }
            if ($insert_id != 0) {
                $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Disclaimer '.$value.' successfully'));
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/cms/attraction_disclaimer'));
    }
    
}