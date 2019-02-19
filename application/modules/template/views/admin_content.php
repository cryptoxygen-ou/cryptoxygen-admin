<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>
        <!-- META SECTION -->
        <title>Cryptoxygen Wallet</title> 
        <base href="<?php echo base_url() ?>"/>
        <link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png')">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="robots" content="noindex, nofollow" />
        
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->  
	<link rel="stylesheet" type="text/css" id="theme" href="<?php echo site_url(); ?>assets/css/admin/theme-default.css"/>    
	<link rel="stylesheet" media="screen" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.min.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/admin/jquery-confirm.css"/>
        <link href="<?php echo site_url(); ?>assets/css/waitMe.css" rel="stylesheet">  
	<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/css/admin/custom.css"/>
        <!-- EOF CSS INCLUDE -->  
    </head>
    <body style="background-color: #DEB887;">
        <?php     
            $this->load->view($module ."/". $view_file);     
        ?>
        <?php //$this->load->view('js/admin_js'); ?>
        
	<script type="text/javascript" src="<?php echo site_url(); ?>assets/js/admin/plugins/jquery/jquery.min.js"></script>   
	<script type="text/javascript" src="<?php echo site_url(); ?>assets/js/admin/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/admin/plugins/bootstrap/bootstrap.min.js"></script>        
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery.validate.min.js"></script>        
        <script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery-validate.bootstrap-tooltip.js"></script>                         
    </body>
</html>
