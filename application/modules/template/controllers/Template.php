<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Template extends MX_Controller {
	    
    public function hello($data) {
        $this->load->view('content', $data);
    }

    public function admin($data) {
        $this->load->view('admin_content', $data);
    }
    
    public function adminPanel($data) {
        $this->load->view('admin_panel', $data);
    }
    
    public function login($data){
        $this->load->view('admin_content',$data);
    }
    
}
