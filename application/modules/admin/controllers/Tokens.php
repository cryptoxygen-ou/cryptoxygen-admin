<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Tokens extends MX_Controller {
    public function __construct() {
        parent::__construct();        
        validateLogin();
        $this->load->model('admin/tokens_model','tm');
        $this->load->library('pagination');
    }
    
    public function index() { 
        //echo "hello payments"; die;
        $listData = $this->tm->getTransferredTokenUsers();
        $data['module'] = 'admin';
        $data['view_file'] = 'tokens';
        $data['title'] = 'Tokens Transferred';
        $data['active'] = 'tokens';
        $data['tokens_list'] = $listData;
        echo Modules::run('template/adminPanel',$data);
	
    }
    
}