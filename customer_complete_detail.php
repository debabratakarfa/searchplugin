<?php
global $wpdb;

$id = $_REQUEST['id'];
$table = $wpdb->prefix . 'cncsearch_pcps';
$queries = $wpdb->get_results( "SELECT * FROM $table WHERE id = $id" );    
                
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Customer Details</title>
    <style type="text/css">
    .formLayout
    {
        background-color: #f3f3f3;
        border: solid 1px #a1a1a1;
        padding: 10px;
        width: 300px;
    }
    
    .formLayout label, .formLayout input
    {
        display: block;
        width: 120px;
        float: left;
        margin-bottom: 10px;
    }

    .formLayout label
    {
        text-align: right;
        padding-right: 20px;
    }
    .pop_up_form{clear:both;}

    br
    {
        clear: left;
    }
    </style>
</head>
<body>
    <div class="formLayout">
        <h4 class="h4_account">Customer Details</h4>
        <div class="pop_up_form">
         <label>First Name:</label>
         <label><?php echo $queries->pcps_first_name; ?></label>
         <br>
         <label>Last Name:</label>
         <label><?php echo $queries->pcps_last_name; ?></label><br>
         
         <label>Contact No.:</label>
         <label><?php echo $queries->pcps_phone; ?></label><br>
         
         <label>Email:</label>
         <label><?php echo $customerDetail->user_email; ?></label><br>
         
         <label>Current Location:</label>
         <label><?php echo $customerDetail->current_location; ?></label><br>
         
         <label>City:</label>
         <label><?php echo $customerDetail->city ; ?></label><br>
         
         <label>State:</label>
         <label><?php echo $customerDetail->state ; ?></label><br>
         
         <label>Country:</label>
         <label><?php echo $customerDetail->country ; ?></label><br>
         
         <label>Zip:</label>
         <label><?php echo $customerDetail->zip  ; ?></label><br>
        </div>
        <div>
           <a href= 'javascript:void(0)' onclick="close_cd()" id='close_cd'>Close</a>
        </div>   
                 
    </div>
   
</body>
</html>