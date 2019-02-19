<?php
ob_clean();
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_kyc extends  MX_Controller{

    public function __construct(){
        parent::__construct();
        validateLogin();
        $this->load->model('admin/manage_kyc_model','km');
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

        $total_row  = $this->km->record_count();
        $baseUrl    = base_url().'admin/manage_kyc/index/';
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

        $listData = $this->km->getKYCApplications($page,$perPage,$searchStr);
        $data['module'] = 'admin';
        $data['view_file'] = 'manage_kyc_list';
        $data['title'] = 'KYC Applications';
        $data['kyc_list'] = $listData;
        $data['active'] = 'manage_kyc';
        $data['pagination_lilnks'] = $this->pagination->create_links();
        echo Modules::run('template/adminPanel',$data);        
        
    }
    
    public function kyc_status(){
        
        if($this->input->post())
        {
            
            $input  = $this->input->post();            
            
            if( isset($input['status']) &&  isset($input['id']) )
            {
                $uid = $input['id'];
                $data = get_user_details_byid($uid);
                $f_name = isset($data['f_name'])?$data['f_name']:'';
                $uEmail = $data['email'];
                $msg1 = '';

                if( $input['status'] == "approve" )
                {
                    $status = $this->km->updateUserKycStatus(1,$uid);
                    if($status){
                        $url = SITE_URL.'/tutorial';
                        //Send kyc approval email to user
                        $to = $uEmail;
                        $subject = "KYC approved";
                        $message = "Hello $f_name, <br/><br/>
                            <p>Thank you for your interest in our platform! Your recently submitted KYC has been accepted and you are now ready to buy your first OXY2 tokens! Please make sure to keep all information secure and if you need help follow the tutorial <a href='$url'>here</a>. We look forward to a bright future together!</p>
                            <br/><br/>
                            Regards,<br/>
                            The Cryptoxygen Team";
                        $sendStatus = send_email($to,$subject,$message);
                        
                        if( $sendStatus == 1 ){
                            $msg1 = "and email has been sent to user";
                        }else{
                            $msg1 = "but email not sent";
                        }

                        $msg = ["msg"=>"User's kyc status approved successfully!, $msg1","status"=>1];

                    }else{                        
                        $msg = ["msg"=>"Something wrong, Please try again.","status"=>0];
                    }
                }else if( $input['status'] == 'reject' )
                {
                    $status = $this->km->updateUserKycStatus(0,$uid);
                    if($status){

                        //Send kyc Unapproval email to user
                        $to = $uEmail;
                        $subject = "KYC deny";
                        $message = "Hello $f_name, <br/><br/>
                            <p>Thank you for your interest in our platform! Unfortunately we had to deny your submitted KYC documentation. Please make sure the provided document is either a Passport, Drivers License, or Government Identification. You must also provide a second picture of you holding that same form of identification next to your face. (Like a Selfie). Images must be of good quality with no edits. If you need help or are having trouble please e-mail our team.</p>
                            <br/><br/>
                            Regards,<br/>
                            The Cryptoxygen Team";
                        $sendStatus = send_email($to,$subject,$message);
                        
                        if( $sendStatus == 1 ){
                            $msg1 = "and email has been sent to user";
                        }else{
                            $msg1 = "but email not sent";
                        }

                        $msg = ["msg"=>"User's kyc status deny successfully!, $msg1","status"=>1];
                    }else{
                        $msg = ["msg"=>"Something wrong, Please try again.","status"=>0];
                    }
                }
            }else{                
                $msg = ["msg"=>"Oh! something wrong, Please try again.","status"=>0];
            }
            echo json_encode( $msg );
            exit;
        }
    }

}
