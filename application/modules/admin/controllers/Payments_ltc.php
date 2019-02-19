<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Payments_ltc extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/payments_ltc_model','plm');
    }
    
    public function index() { 
        //echo "hello payments"; die;
        $listData = $this->plm->getLTCpayments();
        $data['module'] = 'admin';
        $data['view_file'] = 'payments_ltc';
        $data['title'] = 'Payments LTC';
        $data['ltc_transactions'] = $listData;
        $data['active'] = 'payments_ltc';
        echo Modules::run('template/adminPanel',$data);
	
    }
    
}