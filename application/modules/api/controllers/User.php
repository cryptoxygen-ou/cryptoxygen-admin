<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'modules/api/libraries/REST_Controller.php';


class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['data_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['fetch']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['get1_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->methods['upload_post']['limit'] = 50; 
        $this->load->model('usermodel');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->helper('string');
    }
    
    public function data_post()
    {
       $data_var = json_decode(file_get_contents('php://input'), true);

            $data=array();
            $firstname=$data_var['firstName'];
            $lastname=$data_var['lastName'];
            $email=$data_var['userEmail'];
            $password=$data_var['userPassword'];
            $address=$data_var['comment'];
            $fetch_data=$this->usermodel->where(array('email'=>$email))->get();

            if($fetch_data ==''){
                    $data = [
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'password' =>$password,
                        'address' =>$address
                        
                    ];

                    $result= $this->usermodel->insert($data); 
                    $id=$this->db->insert_id();
                    if($result > 0){
                         
                        $fetch_data=$this->usermodel->where(array('id'=>$id))->get();
                        $message=array('status'=>true,'message'=>'Registration success. Please confirm your email address','status_code'=>200);
                       $this->set_response($message); 
                    }
            }
            else
            {
                   $message=array('status'=>false,'message'=>'Email already exist','status_code'=>208);
                   $this->set_response($message);
            }
       
       
    }

    public function login_post(){


        $data_var = json_decode(file_get_contents('php://input'), true);
        $data=array();
       
        $email=$data_var['email'];
        $password=$data_var['password'];

         $fetch_data=$this->usermodel->where(array('email'=>$email))->get();

        if($fetch_data ==''){

            $fetch_data1=$this->usermodel->where(array('email'=>$email,'password'=>$password))->get();
            // check id password match or not 
            if($fetch_data1 ==''){

                print_r($fetch_data1);

            }
            else
            {
                $message=array('status'=>false,'message'=>'Wrong Password','status_code'=>208);
                $this->set_response($message);
            }    

        }
        else
        {
            $message=array('status'=>false,'message'=>'Email not found','status_code'=>208);
            $this->set_response($message);
        }    

    }
        
 }