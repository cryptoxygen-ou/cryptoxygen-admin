<?php

class Payments_btc_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    /*
    * Get all BTC payments received during ICO
    */
   function getBTCpayments(){
       $query = $this->db->query( "SELECT t1.*,t2.`address` FROM ".SYN_BTC." as t1 JOIN ".ADDRESS_BTC." as t2 ON t1.`address_id`=t2.`id`" ,0,100 );       
       
       if( $query->num_rows() > 0 ){           
           return $query->result_array();
       }else{
           return [];
       }
   }
}
