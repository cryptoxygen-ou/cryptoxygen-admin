<?php
ob_clean();
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_distribution extends  MX_Controller{

    public function __construct(){
        parent::__construct();
        validateLogin();
        $this->load->model('admin/tokens_model','tm');
        $this->load->model('admin/users_model','u');
        $this->load->library('pagination');
        $this->load->helper('site_helper');
    }
    
    public function _pagination_creator($baseURL,$perPage,$totalRow)
    {
        $config                 = array();
        $config['base_url']     = $baseURL;
        $config['total_rows']   = $totalRow;
        $config['per_page']     = $perPage;
        $config['use_page_numbers'] = TRUE;
        $config['num_links']    = $totalRow;
        $config['next_link']    = 'Next';
        $config['prev_link']    = 'Previous';        
        $config['cur_tag_open'] = '<li><a class="current">';
        $config['cur_tag_close'] = '</a></li>';        
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';        
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';    
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';        
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';        
        $this->pagination->initialize($config);
    }

    public function index($pageCount=''){
        
        $total_row  = $this->u->record_count();
        $baseUrl    = base_url().'admin/token_distribution/index/';
        $perPage    = 100;
        $this->_pagination_creator($baseUrl,$perPage,$total_row);
        
        if($pageCount){
            $page = $pageCount;
        }
        else{
            $page = 1;
        }

        //Custom Search
        $searchStr = '';
        if( isset($_GET['s']) && $_GET['s']!='' )
        {
            $searchStr = $_GET['s'];
        }
        $activePhase = $this->u->get_settings();
        
        $listData = $this->tm->getTokenDistributionUsers($page,$perPage,$searchStr);
        $data['activePhaseToken'] = $activePhase['erc_tokens'];
        $data['module'] = 'admin';
        $data['view_file'] = 'token_distribution';
        $data['title'] = 'Token Distribution';
        $data['ico_users_list'] = $listData;
        $data['active'] = 'token_distribution';
        $data['pagination_lilnks'] = $this->pagination->create_links();
        echo Modules::run('template/adminPanel',$data);        
    }  
    function addtoken(){
    //     $userId  = $_POST['uid'];
    //     $tokens  = $_POST['tokens'];
    //     $amount  = $_POST['amount'];
    //     $bonusCheck = $_POST['bonus_check'];
    //    $this->u->saveTokens($userId,$tokens,$amount,$bonusCheck);
    //    echo "1";
    }
}