<?php

class Payments_fiat_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    /*
    * Get all BTC payments received during ICO
    */
   function getFiatpayments(){
       $query = $this->db->query( "SELECT up.*,u.email,(SELECT f_name FROM users_profiles where user_id=u.id) f_name,(SELECT l_name FROM users_profiles where user_id=u.id) l_name FROM `user_payments` up INNER JOIN users u ON u.id=up.user_id WHERE 1" ,0,100 );
       
       if( $query->num_rows() > 0 ){           
           return $query->result_array();
       }else{
           return [];
       }
   }

   //Update bonus status
   function update_bonus($rowid,$userid,$bonusStatus){
        $return = 0;
        if($userid != ''){
            $queryRes = $this->db->query("UPDATE `user_payments` SET `bonus`='".$bonusStatus."' WHERE `id`=".$rowid." AND `user_id`=".$userid);
            if($queryRes){
                $return = 1;
            }
        }
        return $return;
   }
}
