<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Master extends MX_Controller {

    public function __construct() {
        //validateLogin();
        parent::__construct();
        $this->load->model('master_model');
    }
    
    public function coaches() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'master/coaches_list';
        $data['title'] = 'Coaches list';
        $data['cms'] = 'active';
        $data['active'] = 'coach';
        $data['list'] = $this->master_model->getCoachStandatds();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_coaches() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'master/add_coaches';
        $data['title'] = 'Coach Add';
        $data['cms'] = 'active';
        $data['active'] = 'coach';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function coaches_save() { 
        if ($this->input->post()) {
            $input = $this->input->post(); 
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('seats', 'Seats', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'name' => $input['name'],
                    'seats' => $input['seats']
                );
                $insert_id = 0;
                if(isset($input['coach_id']) && $input['coach_id'] != '') {
                    $result = $this->crud->get(COACH_STANDARDS, array('id' => $input['coach_id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(COACH_STANDARDS, $addData, array('id' => $input['coach_id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(COACH_STANDARDS, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Coach '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/master/coaches'));
    }
    
    public function coaches_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(COACH_STANDARDS, array('id' => $id));
            if(!empty($list)) {
                $data['view_file'] = 'master/add_coaches';
                $data['title'] = 'Coaches Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'coach';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/master/coaches'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/master/coaches'));
        }
    }
    
    public function coaches_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(COACH_STANDARDS, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(COACH_STANDARDS, array('id' => $input['id']));
        }
        if ($delete) {
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Coach standard deleted successfully.'));
            print true;
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
            print false;
        }
        die;
    }
    
    public function cities() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'master/city_list';
        $data['title'] = 'City list';
        $data['cms'] = 'active';
        $data['active'] = 'city';
        $data['list'] = $this->master_model->getAllCities();
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function add_city() {
        validateLogin();
        $data['module'] = 'admin';
        $data['view_file'] = 'master/add_city';
        $data['title'] = 'Add City';
        $data['cms'] = 'active';
        $data['active'] = 'city';
        echo Modules::run('template/adminPanel', $data);
    }
    
    public function city_save() {
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
            //if validation fails
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                $this->session->set_userdata('postData', $input);
            } else {
                $addData = array(
                    'name' => $input['name'],
                    'code' => $input['code']
                );
                $insert_id = 0;
                if(isset($input['city_id']) && $input['city_id'] != '') {
                    $result = $this->crud->get(CITIES, array('id' => $input['city_id']));
                    if(!empty($result)) {
                        $insert_id = $this->crud->update(CITIES, $addData, array('id' => $input['city_id']));
                        $value = 'updated';
                    }
                } else {
                    $insert_id = $this->crud->insert(CITIES, $addData);
                    $value = 'created';
                }
                if ($insert_id != 0) {
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'City '.$value.' successfully'));
                } else {
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Some error occurred. Please try again.'));
                }
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please fill the required fields.'));
        }
        redirect(base_url('admin/master/cities'));
    }
    
    public function city_edit($id) {
        validateLogin();
        if (isset($id)) {
            $list = $this->crud->get(CITIES, array('id' => $id));
            if(!empty($list)) {
                $data['view_file'] = 'master/add_city';
                $data['title'] = 'City Edit';
                $data['list'] = $list;
                $data['module'] = 'admin';
                $data['cms'] = 'active';
                $data['active'] = 'city';
                echo Modules::run('template/adminPanel', $data);
            } else {
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
                redirect(base_url('admin/master/cities'));
            }
        } else {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Data does not exists.'));
            redirect(base_url('admin/master/cities'));
        }
    }
    
    public function city_delete() { 
        $input = $this->input->post();
        if(!$input['id'] || $input['id'] == '') {
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'The page you requested is not valid.'));
            print false;
        }
        $list = $this->crud->get(CITIES, array('id' => $input['id']));
        $delete = 0;
        if($list) {
            $delete = $this->crud->delete(CITIES, array('id' => $input['id']));
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
    
}