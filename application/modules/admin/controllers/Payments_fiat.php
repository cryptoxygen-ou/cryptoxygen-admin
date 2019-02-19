<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Payments_fiat extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/payments_fiat_model','pbm');
    }
    
    public function index() {
        //echo "hello payments"; die;
        $listData = $this->pbm->getFiatpayments();
        
        $data['module'] = 'admin';
        $data['view_file'] = 'payments_fiat';
        $data['title'] = 'Payments Fiat';
        $data['fiat_transactions'] = $listData;
        $data['active'] = 'payments_fiat';
        echo Modules::run('template/adminPanel',$data);
	
    }

    //Update bonus status
    function updatebonus(){
        $userid = $_POST['uid'];
        $bonusStatus = $_POST['bonus_check'];
        $recordId = $_POST['record_id'];
        
        $status = $this->pbm->update_bonus($recordId,$userid,$bonusStatus);
        if($status==1){
            $jsonMsg = ['status'=>1,'msg'=>'User bonus status updated successfully!'];
        }else{
            $jsonMsg = ['status'=>0,'msg'=>'Unable to update user bonus status.'];
        }
        echo json_encode($jsonMsg);
    }
    
}