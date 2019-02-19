<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class Usermodel extends MY_Model
{
	public function __construct()
	{
        $this->table = 'frontuser';
        $this->primary_key = 'id';
       
		parent::__construct();
	}

    public function read($id){
    if($id===NULL){
    $replace = "" ;
    }
    else{
    $replace = "=$id";
    }
    $query = $this->db->query("select * from user_signup where id".$replace);
    return $query->result_array();
    }

    public function delete1($id){

        $query = $this->db->query("delete from user_signup where id=$id");
        return 'data deleted successfully';
    }

    

  

}
