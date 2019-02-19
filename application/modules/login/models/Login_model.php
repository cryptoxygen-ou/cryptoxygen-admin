<?php

class Login_model extends CI_Model {
    
   function __construct(){  
      parent::__construct();  
   }    

   /*
    * Function to check if user can login or not
    * @param (email, password)
    * return true(with user data) or false
    */
   function checkLogin($data) {       
       $query = $this->db->get_where(USERS, array('email' => $data['email'],'password'=>md5($data['password']),'user_status'=>1 ));
      
       if($query->num_rows() > 0){
           return '1';
       }
       else{
           //email address or password does not exist
           return '0';
       }
   }   
}