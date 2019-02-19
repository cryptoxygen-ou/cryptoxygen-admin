<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud {

    protected $CI;
    
    public function __construct($config = array()){
		$this->CI = &get_instance();

		if ( ! empty($config))
		{
			$this->initialize($config);
		}

		log_message('debug', 'Crud Class Initialized');
    }
    
    /*
     * Function to insert a row in a table
     * @params table_name, data to insert
     * @return true or false
     */
    public function insert($table, $data){
        $insert = $this->CI->db->insert($table, $data);
        if($insert){
            return $this->CI->db->insert_id();
        }
        else{
            return 0;
        }
    }
    
     /*
     * Function to insert multiple row in a table
     * @params table_name, array of data to insert
     * @return true or false
     */
    public function insert_batch($table, $data){
        $insert = $this->CI->db->insert_batch($table, $data);
        if($insert){
            return 1;
        }
        else{
            return 0;
        }
    }
    
     /*
     * Function to update a row in a table
     * @params table_name, data to update, where condition
     * @return true or false
     */
    public function update($table, $data, $where){
        $update = $this->CI->db->update($table, $data, $where);
        if($update){
            return 1;
        }
        else{
            return 0;
        }
    }
    
     /*
     * Function to delete a row in a table
     * @params table_name, where condition
     * @return true or false
     */
    public function delete($table, $where){
        $delete = $this->CI->db->delete($table, $where);
        if($delete){
            return 1;
        }
        else{
            return 0;
        }
    }
    
     /*
     * Function to retrieve single row from table
     * @params table_name, where condition
     * @return array
     */
    public function get($table, $where){
        $query = $this->CI->db->get_where($table, $where);
        if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result[0];
        }
        else{
            return array();
        }
    }
    
    /*
     * Function to retrieve all rows from table
     * @params table_name, where condition
     * @return array
     */
    public function getAll($table, $where,$limit=0){
        $fields = $this->CI->db->list_fields($table);
        if(in_array("created_at", $fields)){
            $this->CI->db->order_by('created_at', 'DESC');
        }
        if($limit!=0 && $limit!=""){
            $this->CI->db->limit($limit, 0);
        }
        $query = $this->CI->db->get_where($table, $where);
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return array();
        }
    }
}
