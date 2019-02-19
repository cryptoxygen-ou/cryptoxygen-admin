<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Ico_settings extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/Ico_settings_model','im');
    }
    
    public function index() { 
        //echo "hello payments"; die;
        $icoPhases = $this->im->get_settings();
        $data['module'] = 'admin';
        $data['view_file'] = 'ico_settings';
        $data['title'] = 'ICO Settings';
        $data['active'] = 'settings';

        //Get all wallet users
        $data['data_icophases'] = $icoPhases;

        echo Modules::run('template/adminPanel',$data);
	
    }

    public function save(){
        
        if($this->input->post()){
            
            $input = $this->input->post(); //echo "<pre>"; print_r($input); die;
            $this->form_validation->set_rules('ico_phase', 'ICO Phases', 'trim|required');
            $this->form_validation->set_rules('ico_enddate', 'ICO end Date', 'trim|required');
            
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                redirect(base_url('admin/ico_settings'));
            }
            else{
                $result = $this->im->save_settings( array('ico_phase'=>$input['ico_phase'], 'end_Date' => $input['ico_enddate']) );
                if($result == 1){
                   $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Settings saved successfull!'));
		   redirect(base_url('admin/ico_settings'));
                } else {
		    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Unable to save details, Please try again.'));
            	    redirect(base_url('admin/ico_settings'));
		}
            }
        }
        else{
                $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please enter valid login details.'));
                redirect(base_url());
            }
        }
    
}