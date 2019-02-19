<?php

class Contract_settings_model extends CI_Model {
    
   function __construct(){  
      parent::__construct();  
   }    

   /*
    * Function to check if user can login or not
    * @param (email, password)
    * return true(with user data) or false
    */
   function save_settings($data) {
       //Update row
       $date = date('Y-m-d H:i:s');
       $data = array(
           'wallet_address'=>$data['wallet_address'],
           'contract_address'=>$data['contract_addr'],
           'contract_abi'=>$data['contract_abi'],
           'created'=>$date
       );
       
       $this->db->where('id',1);
       $result = $this->db->update(CONTRACT_SETTINGS,$data);
       
       if($result ==1 ){
           return 1;
       }
       else{
           return 0;
       }
   }
   
   function get_settings(){
       $query = $this->db->get_where(CONTRACT_SETTINGS, array('id' => 1));
       if( $query->num_rows() > 0 ){
           $result = $query->row_array();
       }
       else{
           $result = [];
       }
       return $result;
   }
}