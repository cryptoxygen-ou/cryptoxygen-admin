<!DOCTYPE html>
<html lang="en">
    <head>       
        <!-- META SECTION -->
        <title>Cryptoxygen Admin Panel</title>  
        <base href="<?php echo base_url() ?>"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="<?php echo site_url(); ?>assets/images/favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
                        
        <!-- CSS INCLUDE -->        
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,500,600,700&subset=latin,latin-ext" rel="stylesheet">
	<link href="<?php echo site_url(); ?>assets/css/admin/jquery/jquery-ui.min.css" rel="stylesheet">
	<link href="<?php echo site_url(); ?>assets/css/admin/bootstrap/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo site_url(); ?>assets/css/admin/fontawesome/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo site_url(); ?>assets/css/admin/summernote/summernote.css" rel="stylesheet">
	<!--<link href="assets/css/admin/codemirror/codemirror.css" rel="stylesheet">
	<link href="assets/css/admin/nvd3/nv.d3.css" rel="stylesheet">-->
	<link href="<?php echo site_url(); ?>assets/css/admin/mcustomscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">
        <link href="<?php echo site_url(); ?>assets/css/validationEngine.jquery.css" rel="stylesheet">
	<!--<link href="assets/css/admin/fullcalendar/fullcalendar.css" rel="stylesheet">
	<link href="assets/css/admin/blueimp/blueimp-gallery.min.css" rel="stylesheet">
	<link href="assets/css/admin/rickshaw/rickshaw.css" rel="stylesheet">
	<link href="assets/css/admin/dropzone/dropzone.css" rel="stylesheet">
	<link href="assets/css/admin/introjs/introjs.min.css" rel="stylesheet">
	<link href="assets/css/admin/animate/animate.min.css" rel="stylesheet">-->

        <link href="<?php echo site_url(); ?>assets/admin/css/theme-default.css" rel="stylesheet">
	<link href="<?php echo site_url(); ?>assets/css/custom.css" rel="stylesheet">
	<link href="<?php echo site_url(); ?>assets/css/animate.css" rel="stylesheet">
        
        <!-- START PLUGINS -->
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery-ui.min.js"></script> 
	<script type="text/javascript" src="<?php echo site_url(); ?>assets/admin/js/bootstrap.min.js"></script> 
	<script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery-validate.bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/bootbox.min.js"></script>    
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/admin/plugins/summernote/summernote.js"></script>
        <!-- END PLUGINS -->  
        
        <!-- validation Engine -->
        <script src="<?php echo site_url(); ?>assets/js/jquery.validationEngine.js"></script>  
        <script src="<?php echo site_url(); ?>assets/js/jquery.validationEngine-en.js"></script>  
        <!-- validation Engine -->
        <script src="<?php echo site_url(); ?>assets/js/jquery.form.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js/dist/web3.min.js"></script>

        <!-- EOF CSS INCLUDE -->  
    </head>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">            
            
            <?php echo $this->load->view('sidebar'); ?>
            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                
                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <!-- TOGGLE NAVIGATION -->
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
                    </li>
                    <!-- END TOGGLE NAVIGATION -->
                    <!-- POWER OFF -->
                    <li class="xn-icon-button pull-right last">
                        <a href="#"><span class="fa fa-power-off"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <?php /*<li><a href="<?php echo base_url('admin/change_password'); ?>"><span class="fa fa-lock"></span> Change Password</a></li>*/ ?>
                            <li><a href="javascript:void(0);" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>
                        </ul>                        
                    </li> 
                    <!-- END POWER OFF -->
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->
                
                <!-- PAGE TITLE -->
                <div class="page-title">                    
                    <h2><?php echo $title; ?></h2>
                    <div id="alertmsg"></div>
                </div>
                <!-- END PAGE TITLE -->                
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    
                    
                    
                    <div class="row"> 
                        <div class="col-sm-12">
			<?php 
		            if($this->session->flashdata() && !isset($noFlashData)){
		                echo showErrorMessages($this->session->flashdata());
		            }
		            if($this->session->flashdata('emessage') && !isset($noFlashData)){
		                echo showErrorMessages($this->session->flashdata('emessage'));
		            }                  
        		    $this->load->view($module ."/". $view_file); 
			?>
                            </div>
                    </div>
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->                                                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
        
        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>                    
                        <p>Press <b>No</b> if you want to continue work. Press <b>Yes</b> to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->  

    <!-- START SCRIPTS -->     

        <!-- THIS PAGE PLUGINS -->
        <script type='text/javascript' src="<?php echo site_url(); ?>assets/admin/js/plugins/icheck/icheck.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
	<script type='text/javascript' src="<?php echo site_url(); ?>assets/admin/js/plugins/datatables/jquery.dataTables.min.js"></script>
        
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/admin/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <!--<script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-file-input.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-select.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>-->
        <!-- END THIS PAGE PLUGINS -->  
        
        <!-- START TEMPLATE -->
	<!--<script type="text/javascript" src="assets/js/admin/settings.js"></script>     
        <script type="text/javascript" src="assets/js/plugins.js"></script>  --> 
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/actions.js"></script> 
	<!--<script type="text/javascript" src="assets/js/custom.js"></script>  -->     
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->
        
    <script type="text/javascript">
        $(document).ready(function(){
           if($("#alertmsg").length){
                setTimeout(function(){
                    if($("#alertmsg .custom-alert").length){
                        $("#alertmsg .custom-alert").fadeOut();
                    }
               },10000);
           }
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });       
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>
    </body>
</html>
