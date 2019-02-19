<?php

class Users_model extends CI_Model{

    function __construct() {
        parent::__construct();
    }
    
    /**
     * Function to count all rows
     */
    
    public function record_count() {
        return $this->db->count_all(USERS_ICO);
    }
    
    /*
    * Get all BTC payments received during ICO
    */
   function getUsersList($pageNumber,$limit,$searchStr=null){
       
        $start  = $pageNumber*$limit-$limit;
        $this->db->select('u.*');
        $this->db->select('up.f_name,up.l_name,up.phone');
        $this->db->select('k.f_name first_name, k.l_name last_name,k.city, k.state,k.country,k.address,k.user_id,k.zipcode');
        $this->db->select('cc.nicename');
        $this->db->select("(SELECT address FROM ".USER_WALLETS." WHERE user_id=u.id) as wallet_address");
        $this->db->from(USERS_ICO. " u");
        $this->db->join(USERS_PROFILE. " up", "up.user_id = u.id", 'left');
        $this->db->join(KYC_USERS. " k", "u.id = k.user_id", 'left');
        $this->db->join(COUNTRY_CODES . " cc", "cc.id = k.country", 'left');

        //Custom Search
        if( $searchStr ){
            //$this->db->where("k.f_name LIKE '%".$searchStr."%' OR k.l_name LIKE '%".$searchStr."%'");
            $this->db->like('up.f_name', $searchStr);
            $this->db->or_like('up.l_name', $searchStr);
            $this->db->or_like('k.city', $searchStr);
            $this->db->or_like('k.state', $searchStr);
            $this->db->or_like('k.country', $searchStr);
            $this->db->or_like('k.address', $searchStr);
            $this->db->or_like('k.zipcode', $searchStr);
            $this->db->or_like('up.phone', $searchStr);
            $this->db->or_like('u.createdAt', $searchStr);
            $this->db->or_like('u.email', $searchStr);
            //$this->db->or_like('body', $match);
        }else{
            $this->db->limit($limit,$start);
        }
        
        $query   = $this->db->get();
        //echo $this->db->last_query();
       if( $query->num_rows() > 0 ){
           return $query->result_array();
       }else{
           return [];
       }
   }   
   
}