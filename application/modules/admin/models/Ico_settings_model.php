<?php

class Ico_settings_model extends CI_Model {
    
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
       $icoPhase = $data['ico_phase'];
       $end_Date = $data['end_Date'];       

       $enddate = date('Y-m-d H:i:s', strtotime($end_Date));

       //Default set to 0
       $result = $this->db->query("UPDATE ".ICO_PHASES." SET `ico_phase_active`=0 WHERE 1");

       $data = array(
           'ico_phase_active'=>1,
           'end_date'=>$enddate,
       );      
       
       $this->db->where('id',$icoPhase);
       $result = $this->db->update(ICO_PHASES,$data);
       
       if($result ==1 ){
           return 1;
       }
       else{
           return 0;
       }
   }
   
   function get_settings(){
       $query = $this->db->query("SELECT * FROM ".ICO_PHASES." WHERE `ico_phase_active`=1");
       if( $query->num_rows() > 0 ){
           $result = $query->row_array();
       }
       else{
           $result = [];
       }
       return $result;
   }
}