<?php
class MY_Form_validation extends CI_Form_validation{
    
     function __construct($rules = array()) {
        // Construct the parent class
        parent::__construct($rules);
       //$this->ci =& get_instance();
        $this->CI =& get_instance();
    }
    public function get_errors_as_array(){
        return $this->_error_array;
    }
    public function get_config_rules(){
        return $this->_config_rules;
    }
    public function get_fildes_name($form){
        $field_name=array();
        $rules = $this->get_config_rules();
        $rules = $rules[$form];
        foreach ($rules as $index=>$info){
            $field_name[]=$info['field'];
        }
        return $field_name;
    }
    
    /*public function set_error_message($lang,$val=''){
            if ( ! is_array($lang)){
                $lang = array($lang => $val);
            }
        $this->_error_messages = array_merge($this->_error_messages, $lang);
        return $this;
    }*/
}

?>