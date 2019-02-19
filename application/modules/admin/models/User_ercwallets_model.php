<?php

class User_ercwallets_model extends CI_Model{
    function __construct() {
        parent::__construct();        
    }
    
    /*
    * Get all BTC payments received during ICO
    */
   function getUsersErcWallets(){
       $query = $this->db->query( "SELECT t2.f_name,t2.l_name,t1.* FROM ".USER_WALLETS." as t1 INNER JOIN ".USERS_PROFILE." as t2 ON t1.user_id=t2.user_id " ,0,100 );       
       
       if( $query->num_rows() > 0 ){           
           return $query->result_array();
       }else{
           return [];
       }
   }
}
