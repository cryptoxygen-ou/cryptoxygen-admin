<?php

class Manage_kyc_model extends CI_Model{

    function __construct() {
        parent::__construct();
    }
    
    /**
     * Function to count all rows
     */
    
    public function record_count() {
        return $this->db->count_all(KYC_USERS);
    }
    
    /*
    * Get all BTC payments received during ICO
    */
   function getKYCApplications($pageNumber,$limit,$searchStr=null){
       
       $start  = $pageNumber*$limit-$limit;

    //    $this->db->from(KYC_USERS);
    //    $this->db->limit($limit,$start);
    //    $query   = $this->db->get();       

        // $ci->db->select('s.id as state_id, c.id as country_id, s.name as state, c.name as country, c.sortname as sortname, c.phonecode as phonecode');
        // $ci->db->from(ES_STATES . " s");
        // $ci->db->join(ES_COUNTRIES . " c", "c.id = s.country_id");

        $this->db->from(KYC_USERS. " k");
        $this->db->join(USERS_ICO . " u", "u.id = k.user_id");
        $this->db->join(COUNTRY_CODES . " cc", "cc.id = k.country");
        $this->db->order_by("k.user_id", "desc");

        //Custom Search
        if( $searchStr ){
            $searchStr = trim($searchStr);
            //$this->db->where("k.f_name LIKE '%".$searchStr."%' OR k.l_name LIKE '%".$searchStr."%'");
            $this->db->like('k.f_name', $searchStr);
            $this->db->or_like('k.l_name', $searchStr);
            $this->db->or_like('k.city', $searchStr);
            $this->db->or_like('k.state', $searchStr);
            $this->db->or_like('k.country', $searchStr);
            $this->db->or_like('k.address', $searchStr);
            $this->db->or_like('k.zipcode', $searchStr);
            $this->db->or_like('k.phone', $searchStr);
            $this->db->or_like('k.createdAt', $searchStr);
            $this->db->or_like('u.email', $searchStr);
            $this->db->or_like('CONCAT_WS(" ",k.f_name, k.l_name)',$searchStr);
            //$this->db->or_like('body', $match);
        }else{
            $this->db->limit($limit,$start);
        }

        //echo $this->db->last_query();        
        $query   = $this->db->get();
       
       if( $query->num_rows() > 0 ){
           return $query->result_array();
       }else{
           return [];
       }
   }
   
   /**
    * This function will update user kyc status approve/reject
    * @param type $status
    * @param type $userid
    */
   function updateUserKycStatus($status,$userid){
       
       $query = $this->db->query("UPDATE ".KYC_USERS." SET `kyc_status`=$status WHERE `user_id`=$userid ");
       if($query){
           return true;
       }else{
           return false;
       }
   }
}