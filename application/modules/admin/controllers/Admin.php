<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Admin extends MX_Controller {
    public function __construct() {
        parent::__construct();
        validateLogin();
        $this->load->model('admin/admin_model');
        $this->load->model('admin/contract_settings_model','cs');
    }
    
    public function index() {
       
	if($this->session->userdata('adminSession') && count($this->session->userdata('adminSession')) > 0) {
		redirect(base_url('admin/dashboard'));
	} else {
		$data['module'] = 'admin';
		$data['view_file'] = 'login';
		echo Modules::run('template/admin',$data);
	}	
    }

    /*
     * Function admin dashboard
     */
    public function dashboard() {
        
        $cryptoDataAry = getCryptoLivePrice();
        $resultData = $this->cs->get_settings();
        $totalBTC = $this->admin_model->getTotalBtc();
        $totalETH = $this->admin_model->getTotalEth();
        $totalLTC = $this->admin_model->getTotalLtc();
        $totalUSDs = $this->admin_model->getTotalPaymentReceived();
        $totalSoldtoken = $this->admin_model->totalSoldsToken();
        
        if(is_array($resultData) && count($resultData)>0){
            $data['contract_address'] = $resultData['contract_address'];
            $data['contract_abi'] = $resultData['contract_abi'];
            $data['wallet_address'] = $resultData['wallet_address'];
        }
        $data['total_btc'] = $totalBTC;
        $data['total_eth'] = $totalETH;
        $data['total_ltc'] = $totalLTC;
        $data['total_usd'] = $totalUSDs;
        $data['totalSoldtoken'] = $totalSoldtoken;
        
        $data['crypto_rate'] = $cryptoDataAry;
	$data['module'] = 'admin';
	$data['view_file'] = 'dashboard';
	$data['title'] = 'Dashboard';
	$data['active'] = 'dashboard';
	echo Modules::run('template/adminPanel',$data);
    }
    function updatePassword(){
        
        if($this->input->post()){
            $this->form_validation->set_rules('current_password', 'current Password', 'trim|required');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[8]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata(array('type' => 'error', 'msg' => validation_errors()));
                redirect(base_url('admin/updatePassword'));
            }
            else{
                if($this->oldpassword_check($_POST['current_password']) == FALSE){
                    $this->form_validation->set_message('current_password', 'Old password not match');
                    $this->session->set_flashdata(array('type' => 'error', 'msg' => 'Current password not matched.'));
                    redirect(base_url('admin/updatePassword'));
                }else{
                    $this->session->set_flashdata(array('type' => 'success', 'msg' => 'Password updated successfully.'));
                    $password = $_POST['new_password'];
                    $this->admin_model->updatePassword($password);
                    redirect(base_url('admin/updatePassword'));
                }
            }




        }else{
            $data['module'] = 'admin';
            $data['view_file'] = 'update_password';
            $data['title'] = 'Update Password';
            $data['active'] = 'update password';
            echo Modules::run('template/adminPanel',$data);
        }
        
    }
    function oldpassword_check($currentPasswod){
       $oldPassword =  $this->admin_model->getOldPassword();
       if(md5($currentPasswod) != $oldPassword){
        
            return FALSE;
       }else{
           return TRUE;
       }
    }
    
}

