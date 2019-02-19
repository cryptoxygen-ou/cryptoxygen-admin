<?php

class Tokens_model extends CI_Model{
    function __construct() {
        parent::__construct();        
    }
    
    /*
    * Get all BTC payments received during ICO
    */
   function getTransferredTokenUsers(){
       $query = $this->db->query( "SELECT t1.*,(SELECT `f_name` FROM ".USERS_PROFILE." WHERE user_id=t1.user_id) as first_name,(SELECT `l_name` FROM ".USERS_PROFILE." WHERE user_id=t1.user_id) as last_name,(SELECT address from ".USER_WALLETS." WHERE user_id=t1.user_id) as erc20_address FROM ".ERC20_TOKEN_TRANSFER." as t1 " ,0,100 );        

       if( $query->num_rows() > 0 ){           
           return $query->result_array();
       }else{
           return [];
       }
   }

   function getTokenDistributionUsers($pageNumber,$limit,$searchStr=null){
       
    $start  = $pageNumber*$limit-$limit;
    $this->db->select('u.id,u.email');
    $this->db->select("up.f_name,up.l_name");
    $this->db->select('SUM(uc.token_value) as totalVal');
    $this->db->select('uw.address');
    $this->db->group_by('u.id');
    $this->db->order_by('u.id'); 
    $this->db->from(USERS_ICO. " u");
    $this->db->join(USERS_PROFILE. " up", "u.id = up.user_id", 'left');
    $this->db->join(USER_COLLECTIONS. " uc", "u.id = uc.user_id", 'right');
    $this->db->join(USER_WALLETS . " uw", "u.id = uw.user_id", 'left');

    //Custom Search
    if( $searchStr ){
        $searchStr = trim($searchStr);
        $this->db->like('up.f_name', $searchStr,'after');
        $this->db->or_like('up.l_name', $searchStr,'after');
        $this->db->or_like('u.email', $searchStr);
        $this->db->or_like('uw.address', $searchStr);
        $this->db->or_like('CONCAT_WS(" ",up.f_name, up.l_name)',$searchStr);
    }else{
        $this->db->limit($limit,$start);
    }
    $query   = $this->db->get();
    // print_r($query->result_array());
    // echo "<br>";
    // echo $this->db->last_query();
   if( $query->num_rows() > 0 ){
       return $query->result_array();
   }else{
       return [];
   }
} 
}
