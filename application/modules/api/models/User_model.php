<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends MY_Model
{
	public function __construct()
	{
        $this->table = 'frontuser';
        $this->primary_key = 'id';
        //$this->soft_deletes = true;
       //$this->has_many['user'] = 'Specialty_model';
       //$this->has_many['services'] = 'Services_model';
        // $this->has_one['details'] = array('User_details_model','user_id','id');
        //$this->has_many['detail'] = array('local_key'=>'id', 'foreign_key'=>'user_id', 'foreign_model'=>'Specialty_model');
          
       // $this->has_many['posts'] = 'Post_model';

		parent::__construct();
	}
      
}
/* End of file '/User_model.php' */
/* Location: ./application/models//User_model.php */