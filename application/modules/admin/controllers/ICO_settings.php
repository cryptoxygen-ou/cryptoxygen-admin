<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ICO_settings extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/User_ercwallets_model','um');
    }
    
    public function index() { 
        //echo "hello payments"; die;
        $userWallets = $this->um->getUsersErcWallets();
        $data['module'] = 'admin';
        $data['view_file'] = 'ico_settings';
        $data['title'] = 'ICO Settings';
        $data['active'] = 'settings';

        //Get all wallet users
        $data['data_wallets'] = $userWallets;

        echo Modules::run('template/adminPanel',$data);
	
    }
    
}