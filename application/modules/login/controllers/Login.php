<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Login extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('login/login_model');
    }
    
    public function index() {
	
        $data['module'] = 'login';
        $data['view_file'] = 'login';
        echo Modules::run('template/login',$data);
	
    }
    
    /*
     * Function to login admin dashboard
     */
    public function login_validate(){ //echo "hello"; die;
        if($this->input->post()){
            $input = $this->input->post(); //echo "<pre>"; print_r($input); die;
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                redirect(base_url('login'));
            }
            else{
                $result = $this->login_model->checkLogin(array('email' => $input['email'], 'password' => $input['password']));
                if($result == '1'){
             
                   $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Login Successfull!'));
		   $sessData = array(
                        'email'   =>  $input['email'],
                   );
           $this->session->set_userdata('adminSession', $sessData);
    
            	   redirect(base_url('admin/dashboard'));
                } else {
		    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please enter valid login details.'));
            	    redirect(base_url('login'));
		}
            }
        }
	else{
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please enter valid login details.'));
            redirect(base_url());
        }
    }
    
    /*
     * Function user logout
     */
    public function logout(){
        $this->session->unset_userdata('adminSession');
        if(!$this->session->userdata('adminSession')){
            $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Logout successfully.'));
            redirect(base_url('login'));
        }        
    }
}