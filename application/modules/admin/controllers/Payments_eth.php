<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Payments_eth extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/payments_eth_model','pem');
    }
    
    public function index() { 
        //echo "hello payments"; die;
        $listData = $this->pem->getETHpayments();
        $data['module'] = 'admin';
        $data['view_file'] = 'payments_eth';
        $data['title'] = 'Payments ETH';
        $data['eth_transactions'] = $listData;
        $data['active'] = 'payments_eth';
        echo Modules::run('template/adminPanel',$data);
	
    }
    
}