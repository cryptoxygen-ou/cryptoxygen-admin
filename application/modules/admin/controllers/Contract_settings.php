<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Contract_settings extends MX_Controller {
    
    public function __construct() {
        parent::__construct();
        validateLogin();
        $this->load->model('admin/contract_settings_model','cs');
    }
    
    public function index() {
        
        $resultData = $this->cs->get_settings();
        if(is_array($resultData) && count($resultData)>0){
            $data['contract_address'] = $resultData['contract_address'];
            $data['contract_abi'] = $resultData['contract_abi'];
            $data['wallet_address'] = $resultData['wallet_address'];            
        }
        
        $data['module'] = 'admin';
        $data['view_file'] = 'contract_settings';
        $data['title'] = 'Token Distribution Contract Settings';
        $data['active'] = 'contract_settings';
        echo Modules::run('template/adminPanel',$data);
	
    }
    
    public function save(){
        
        if($this->input->post()){
            
            $input = $this->input->post(); //echo "<pre>"; print_r($input); die;
            $this->form_validation->set_rules('contract_addr', 'Contract Address', 'trim|required');
            $this->form_validation->set_rules('contract_abi', 'Contract ABI', 'trim|required');
            $this->form_validation->set_rules('wallet_address', 'Contract Wallet Address', 'trim|required');
            
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                redirect(base_url('admin/contract_settings'));
            }
            else{
                $result = $this->cs->save_settings( array('wallet_address'=>$input['wallet_address'], 'contract_addr' => $input['contract_addr'], 'contract_abi' => $input['contract_abi']) );
                if($result == 1){
                   $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Settings saved successfull!'));
		   redirect(base_url('admin/contract_settings'));
                } else {
		    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Unable to save details, Please try again.'));
            	    redirect(base_url('admin/contract_settings'));
		}
            }
        }
	else{
            $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Please enter valid login details.'));
            redirect(base_url());
        }
    }
    
}