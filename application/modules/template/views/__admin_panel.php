<!DOCTYPE html>
<html lang="en">
    <head>        
        <!-- META SECTION -->
        <title>Cryptoxygen Wallet</title>  
        <base href="<?php echo base_url() ?>"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
                        
        <!-- CSS INCLUDE -->        
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,500,600,700&subset=latin,latin-ext" rel="stylesheet">
	<link href="assets/css/admin/jquery/jquery-ui.min.css" rel="stylesheet">
	<link href="assets/css/admin/bootstrap/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/admin/fontawesome/font-awesome.min.css" rel="stylesheet">
	<link href="assets/css/admin/summernote/summernote.css" rel="stylesheet">
	<!--<link href="assets/css/admin/codemirror/codemirror.css" rel="stylesheet">
	<link href="assets/css/admin/nvd3/nv.d3.css" rel="stylesheet">-->
	<link href="assets/css/admin/mcustomscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">
        <link href="assets/css/validationEngine.jquery.css" rel="stylesheet">
	<!--<link href="assets/css/admin/fullcalendar/fullcalendar.css" rel="stylesheet">
	<link href="assets/css/admin/blueimp/blueimp-gallery.min.css" rel="stylesheet">
	<link href="assets/css/admin/rickshaw/rickshaw.css" rel="stylesheet">
	<link href="assets/css/admin/dropzone/dropzone.css" rel="stylesheet">
	<link href="assets/css/admin/introjs/introjs.min.css" rel="stylesheet">
	<link href="assets/css/admin/animate/animate.min.css" rel="stylesheet">-->

        <link href="assets/admin/css/theme-default.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">
        
        <!-- START PLUGINS -->
        <script type="text/javascript" src="assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script> 
	<script type="text/javascript" src="assets/admin/js/bootstrap.min.js"></script> 
	<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery-validate.bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="assets/js/bootbox.min.js"></script>    
        <script type="text/javascript" src="assets/js/admin/plugins/summernote/summernote.js"></script>
        <!-- END PLUGINS -->  
        
        <!-- validation Engine -->
        <script src="assets/js/jquery.validationEngine.js"></script>  
        <script src="assets/js/jquery.validationEngine-en.js"></script>  
        <!-- validation Engine -->
        <script src="assets/js/jquery.form.js"></script>

        <!-- EOF CSS INCLUDE -->  
    </head>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">
            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="<?php echo base_url('admin/dashboard'); ?>">Cryptoxygen Wallet</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                    <li class="xn-openable <?php  echo (($active == 'coach') || ($active == 'city')) ? 'active' : ''; ?>">
                        <a href="#"><span class="fa fa-dashboard"></span> <span class="xn-text">Masters</span></a>
                        <ul>
                            <li class="<?php  echo ($active == 'coach') ? 'active' : ''; ?>">
                                <a href="admin/master/coaches"><span class="fa fa-file-text-o"></span><span class="xn-text">Coach Standards</span></a>
                            </li>
                            <li class="<?php  echo ($active == 'city') ? 'active' : ''; ?>">
                                <a href="admin/master/cities"><span class="fa fa-file-text-o"></span><span class="xn-text">City</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="xn-openable <?php  echo (($active == 'pages') || ($active == 'whyus') || ($active == 'whyus_title') || ($active == 'prices') || ($active == 'destination_title') || ($active == 'services') || ($active == 'service_title') || ($active == 'school_trips') || ($active == 'sports_teams') || ($active == 'event_occassions') || ($active == 'oversease') || ($active == 'faq') || ($active == 'questions') || ($active == 'faq_type') || ($active == 'home') || ($active == 'home_title') || ($active == 'home_box') || ($active == 'home_without_button') || ($active == 'attraction') || ($active == 'events') || ($active == 'attraction_service') || ($active == 'attraction_title') || ($active == 'home_with_button')) ? 'active' : ''; ?>">
                        <a href="#"><span class="fa fa-files-o"></span> <span class="xn-text">Content Management</span></a>
                        <ul>
                            <?php /*<li class="<?php  echo ($active == 'pages') ? 'active' : ''; ?>">
                                <a href="admin/cms/pages"><span class="fa fa-file-text-o"></span><span class="xn-text">Add Pages</span></a>
                            </li>*/ ?>
                            <li class="xn-openable <?php  echo (($active == 'whyus') || ($active == 'whyus_title')) ? 'active' : ''; ?>">
                                <a href="#"><span class="fa fa-file-text-o"></span><span class="xn-text">Why us</span></a>
                                <ul>
                                    <li class="<?php  echo ($active == 'whyus_title') ? 'active' : ''; ?>">
                                        <a href="admin/cms/whyus_title"><span class="fa fa-file-text-o"></span><span class="xn-text">Why us Title</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'whyus') ? 'active' : ''; ?>">
                                        <a href="admin/cms/why_us"><span class="fa fa-file-text-o"></span><span class="xn-text">Why us</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="xn-openable <?php  echo (($active == 'services') || ($active == 'service_title') || ($active == 'school_trips') || ($active == 'sports_teams') || ($active == 'event_occassions') || ($active == 'oversease') || ($active == 'events') || ($active == 'attraction_service')) ? 'active' : ''; ?>">
                                <a href="#"><span class="fa fa-file-text-o"></span><span class="xn-text">Services</span></a>
                                <ul>
                                    <li class="<?php  echo ($active == 'service_title') ? 'active' : ''; ?>">
                                        <a href="admin/cms/service_title"><span class="fa fa-file-text-o"></span><span class="xn-text">Service Title</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'services') ? 'active' : ''; ?>">
                                        <a href="admin/cms/services"><span class="fa fa-file-text-o"></span><span class="xn-text">Services</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'school_trips') ? 'active' : ''; ?>">
                                        <a href="admin/cms/school_trip"><span class="fa fa-file-text-o"></span><span class="xn-text">School Trips</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'sports_teams') ? 'active' : ''; ?>">
                                        <a href="admin/cms/sports_team"><span class="fa fa-file-text-o"></span><span class="xn-text">Sports Teams</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'events') ? 'active' : ''; ?>">
                                        <a href="admin/cms/events"><span class="fa fa-file-text-o"></span><span class="xn-text">Events and Occasions</span></a>
                                    <li class="<?php  echo ($active == 'oversease') ? 'active' : ''; ?>">
                                        <a href="admin/cms/over_trip"><span class="fa fa-file-text-o"></span><span class="xn-text">Overseas Trips</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'attraction_service') ? 'active' : ''; ?>">
                                <a href="admin/cms/attractions_service"><span class="fa fa-file-text-o"></span><span class="xn-text">Top Service</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="xn-openable <?php  echo (($active == 'prices') || ($active == 'destination_title')) ? 'active' : ''; ?>">
                                <a href="#"><span class="fa fa-file-text-o"></span><span class="xn-text">Destinations</span></a>
                                <ul>
                                    <li class="<?php  echo ($active == 'destination_title') ? 'active' : ''; ?>">
                                        <a href="admin/cms/destination_title"><span class="fa fa-file-text-o"></span><span class="xn-text">Destination Title</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'prices') ? 'active' : ''; ?>">
                                        <a href="admin/cms/destination_prices"><span class="fa fa-file-text-o"></span><span class="xn-text">Destination Price</span></a>
                                    </li>
                                </ul>
                            </li>
                            <!-- New code -->
                            <li class="xn-openable <?php  echo (($active == 'faq') || ($active == 'questions') || ($active == 'faq_type')) ? 'active' : ''; ?>">
                                <a href="#"><span class="fa fa-file-text-o"></span><span class="xn-text">FAQ</span></a>
                                <ul>
                                    <li class="<?php  echo ($active == 'faq') ? 'active' : ''; ?>">
                                        <a href="admin/cms/faq"><span class="fa fa-file-text-o"></span><span class="xn-text">FAQ</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'faq_type') ? 'active' : ''; ?>">
                                        <a href="admin/cms/faq_type"><span class="fa fa-file-text-o"></span><span class="xn-text">FAQ Type</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'questions') ? 'active' : ''; ?>">
                                        <a href="admin/cms/question_listing"><span class="fa fa-file-text-o"></span><span class="xn-text">FAQ Questions</span></a>
                                    </li>
                                    
                                </ul>
                            </li>
                            <!-- New code ends here -->
<!--                            <li>
                                <a href="admin/cms/home"><span class="fa fa-file-text-o"></span><span class="xn-text">Home</span></a>
                            </li>-->
                            <li class="xn-openable <?php  echo (($active == 'home') || ($active == 'home_title') || ($active == 'home_box') || ($active == 'home_without_button') || ($active == 'home_with_button')) ? 'active' : ''; ?>">
                                <a href="#"><span class="fa fa-file-text-o"></span><span class="xn-text">Home</span></a>
                                <ul>
                                    <li class="<?php  echo ($active == 'home_title') ? 'active' : ''; ?>">
                                        <a href="admin/cms/home_title"><span class="fa fa-file-text-o"></span><span class="xn-text">Home Title</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'home_box') ? 'active' : ''; ?>">
                                        <a href="admin/cms/home_box"><span class="fa fa-file-text-o"></span><span class="xn-text">Home Block</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'home_without_button') ? 'active' : ''; ?>">
                                        <a href="admin/cms/home_without_button"><span class="fa fa-file-text-o"></span><span class="xn-text">Home Content Without Price</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'home_with_button') ? 'active' : ''; ?>">
                                        <a href="admin/cms/home"><span class="fa fa-file-text-o"></span><span class="xn-text">Home Content With Price</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="xn-openable <?php  echo (($active == 'attraction') || ($active == 'attraction_title')) ? 'active' : ''; ?>">
                                <a href="#"><span class="fa fa-file-text-o"></span><span class="xn-text">Top Attractions</span></a>
                                <ul>
                                    <li class="<?php  echo ($active == 'attraction_title') ? 'active' : ''; ?>">
                                        <a href="admin/cms/attraction_title"><span class="fa fa-file-text-o"></span><span class="xn-text">Attraction Title</span></a>
                                    </li>
                                    <li class="<?php  echo ($active == 'attraction') ? 'active' : ''; ?>">
                                        <a href="admin/cms/attractions"><span class="fa fa-file-text-o"></span><span class="xn-text">Top Attractions</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php  echo ($active == '') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/dashboard'); ?>"><span class="fa fa-dashboard"></span> <span class="xn-text">User Management</span></a>
                    </li>
                    <li class="<?php  echo ($active == '') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/dashboard'); ?>"><span class="fa fa-files-o"></span> <span class="xn-text">Request Management</span></a>
                    </li>
                    <li class="<?php  echo ($active == '') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/dashboard'); ?>"><span class="fa fa-file-text-o"></span> <span class="xn-text">Payment</span></a>
                    </li>   
                    <li class="<?php  echo ($active == '') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/dashboard'); ?>"><span class="fa fa-file-text-o"></span> <span class="xn-text">Transportation Management</span></a>
                    </li>
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->
            
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
                            <li><a href="<?php echo base_url('admin/change_password'); ?>"><span class="fa fa-lock"></span> Change Password</a></li>
                            <li><a href="javascript:void(0);" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>
                        </ul>                        
                    </li> 
                    <!-- END POWER OFF -->
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->
                
                <!-- PAGE TITLE -->
                <div class="page-title">                    
                    <h2><?php echo $title; ?></h2>
                </div>
                <!-- END PAGE TITLE -->                
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    
                    <div class="row"> 
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
                        <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="<?php echo base_url('admin/logout'); ?>" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->  

    <!-- START SCRIPTS -->     

        <!-- THIS PAGE PLUGINS -->
        <script type='text/javascript' src="assets/admin/js/plugins/icheck/icheck.min.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
	<script type='text/javascript' src="assets/admin/js/plugins/datatables/jquery.dataTables.min.js"></script>
        
        <!--<script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-file-input.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/bootstrap/bootstrap-select.js"></script>
        <script type="text/javascript" src="assets/admin/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>-->
        <!-- END THIS PAGE PLUGINS -->  
        
        <!-- START TEMPLATE -->
	<!--<script type="text/javascript" src="assets/js/admin/settings.js"></script> -->     
        <script type="text/javascript" src="assets/js/plugins.js"></script>        
        <script type="text/javascript" src="assets/js/actions.js"></script> 
	<!--<script type="text/javascript" src="assets/js/custom.js"></script>  -->     
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->
        
                         
    </body>
</html>
