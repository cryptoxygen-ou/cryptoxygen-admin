<?php

class Admin_model extends CI_Model {
    
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
   /*
    * Get all btc received during ICO
    */
   function getTotalBtc(){
       $this->db->select_sum('amount');
       $this->db->where('status','confirmed' );       
       $query = $this->db->get(SYN_BTC);
       
       if( $query->num_rows() > 0 ){
           $queryData = $query->row_array();
           $erc_token_allotted = $queryData['amount'];
           return $erc_token_allotted;
       }else{
           return 0;
       }
   }
   /*
    * Get total BTC received during ICO
    */
//    function getTotalBtcTokenSold(){
//        $this->db->select_sum('vgw_token_allotted');
//        $this->db->where('status','confirmed' );       
//        $query = $this->db->get(SYN_BTC);
//        if( $query->num_rows() > 0 ){
//            $queryData = $query->row_array();
//            $vgw_token_allotted = $queryData['vgw_token_allotted'];
//            return $vgw_token_allotted;
//        }else{
//            return 0;
//        }
//    }
    /*
    * Get all ETH received during ICO
    */
   function getTotalEth(){
       $this->db->select_sum('amount');
       $this->db->where('status','confirmed' );       
       $query = $this->db->get(SYN_ETH);       
       
       if( $query->num_rows() > 0 ){
           $queryData = $query->row_array();
           $erc_token_allotted = $queryData['amount'];
           return $erc_token_allotted;
       }else{
           return 0;
       }
   }
   
   /*
    * Get all LTC received during ICO
    */
   function getTotalLtc(){
       $this->db->select_sum('amount');
       $this->db->where('status','confirmed' );       
       $query = $this->db->get(SYN_LTC);
       
       if( $query->num_rows() > 0 ){
           $queryData = $query->row_array();
           $erc_token_allotted = $queryData['amount'];
           return $erc_token_allotted;
       }else{
           return 0;
       }
   }
   
   /*
    * Get all LTC received during ICO
    */
    function getTotalPaymentReceived(){
        $this->db->select_sum('price');
        $this->db->where('transaction_id is NOT NULL',NULL, FALSE );
        $this->db->where('bonus','NO' );
        $query = $this->db->get(USER_PAYMENTS);
        
        if( $query->num_rows() > 0 ){
            $queryData = $query->row_array();
            $totalUsdReveived = $queryData['price'];
            return $totalUsdReveived;
        }else{
            return 0;
        }
    }

    function totalSoldsToken(){
        $query = $this->db->query('select sum(token_value) as total from users_collections');
        if($query->num_rows()>0){
            $data = $query->result_array();
           
            return $data[0]['total'];
        }else{
            return 0;
        }
    }
   function getOldPassword(){
       $email = $_SESSION['adminSession']['email'];
       $query = $this->db->query("select password from admin_users where email = '".$email."' and email_status = 1");
       if($query->num_rows()>0){
            $data = $query->result_array();
            return $data[0]['password'];
        }
   }
   function updatePassword($passwod){
        $email = $_SESSION['adminSession']['email'];
        $query = $this->db->query("update admin_users SET password='".md5($passwod)."' where email = '".$email."' and email_status = 1");
   }
}