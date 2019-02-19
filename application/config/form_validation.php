<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config=array(
    'contact_post' =>array(
        array('field'=>'name','label'=>'Full name','rules'=>'trim|required'),
        array('field'=>'email','label'=>'Email','rules'=>'trim|required|valid_email'),
        array('field'=>'mobileno','label'=>'Mobile Number','rules'=>'trim|required'),
        array('field'=>'message','label'=>'Message','rules'=>'trim|required'),
    ),
    'leadAdd_post' =>array(
        array('field'=>'trip_type','label'=>'Trip type','rules'=>'trim|required'),
        array('field'=>'source_location','label'=>'Source','rules'=>'trim|required'),
        array('field'=>'destination_location','label'=>'Destination','rules'=>'trim|required'),
        array('field'=>'source_latitude','label'=>'Source latitude','rules'=>'trim|required'),
        array('field'=>'source_longitude','label'=>'Source longitude','rules'=>'trim|required'),
        array('field'=>'destination_latitude','label'=>'Destination latitude','rules'=>'trim|required'),
        array('field'=>'destination_longitude','label'=>'Destination longitude','rules'=>'trim|required'),
        array('field'=>'passengers','label'=>'Passengers','rules'=>'trim|required'),
        array('field'=>'dept_date','label'=>'Departure Date','rules'=>'trim|required'),
        array('field'=>'dept_time','label'=>'Departure Time','rules'=>'trim|required'),
        array('field'=>'first_name','label'=>'First Name','rules'=>'trim|required'),
        array('field'=>'email','label'=>'Email','rules'=>'trim|required|valid_email'),
        array('field'=>'phone','label'=>'Phone Number','rules'=>'trim|required')
    ),
    'paymentadd_post' =>array(
        array('field'=>'customer_name','label'=>'Customer Name','rules'=>'trim|required'),
        array('field'=>'customer_email','label'=>'Customer Email','rules'=>'trim|required|valid_email')
    ),
    
    'contact_us_save' =>array(
        array('field'=>'name','label'=>'Full name','rules'=>'trim|required'),
        array('field'=>'email','label'=>'Email','rules'=>'trim|required|valid_email'),
        array('field'=>'message','label'=>'Message','rules'=>'trim|required'),
    ),
    'email_signup' =>array(
        array('field'=>'name','label'=>'Full name','rules'=>'trim|required'),
        array('field'=>'email','label'=>'Email','rules'=>'trim|required|valid_email')
    ),
     
);
 ?>
