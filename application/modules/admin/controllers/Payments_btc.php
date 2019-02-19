<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Payments_btc extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/payments_btc_model','pbm');
    }
    
    public function index() { 
        //echo "hello payments"; die;
        $listData = $this->pbm->getBTCpayments();
        
        $data['module'] = 'admin';
        $data['view_file'] = 'payments_btc';
        $data['title'] = 'Payments BTC';
        $data['btc_transactions'] = $listData;
        $data['active'] = 'payments_btc';
        echo Modules::run('template/adminPanel',$data);
	
    }
    
}