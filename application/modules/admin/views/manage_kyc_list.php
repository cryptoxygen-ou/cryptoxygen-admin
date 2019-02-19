<div class="col-sm-12">

<form class="navbar-form navbar-left" role="search"  type="get" action="admin/manage_kyc/" style="float:right !important">
    <div class="form-group">
    <input type="text" class="form-control" placeholder="Search" name="s" value="<?php echo isset($_GET['s'])?($_GET['s']!='')?$_GET['s']:'':''; ?>">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <a class="btn btn-default" href="admin/manage_kyc">Reset</a>
</form>

<table class="table table-hover table-responsive" style="display:block;">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>City</th>
          <th>State</th>
          <th>Country</th>
          <th>Address</th>          
          <th>Doc.Type</th>
          <th>Document</th>
          <th>Selfie</th>
          <th>ZipCode</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $count = 1;
    if(isset($kyc_list) && is_array($kyc_list) && count($kyc_list)>0 ) { 
                

        foreach ($kyc_list as $key => $value) { 
            ?>
        <tr class="rowid_<?php echo $value['user_id']; ?>">
          <td><?php echo $count; ?></th>
          <td><?php echo $value['f_name']." ".$value['l_name']; ?></td>
          <td><?php echo $value['email']; ?></td>
          <td><?php echo $value['phone']; ?></td>
          <td><?php echo $value['city']; ?></td>
          <td><?php echo $value['state']; ?></td>
          <td><?php echo $value['nicename']; ?></td>
          <td><?php echo $value['address']; ?></td>
          <td><?php echo $value['document_type']; ?></td>
          <td><a target="_blank" href="<?php echo $value['document_upload']; ?>">View</a></td>
          <td><a target="_blank" href="<?php echo $value['selfie_upload']; ?>">Selfie</a></td>
          <td><?php echo $value['zipcode']; ?></td>
          <td><?php echo $value['createdAt']; ?></td>
          <td>
              <?php echo ($value['kyc_status']==1)?'<span title="Approved" class="glyphicon green_kyc glyphicon-ok '.$value['user_id'].'"></span>':'<span title="Unapproved" class="glyphicon red_kyc glyphicon-remove '.$value['user_id'].'"></span>'; ?>
          </td>
          <td>
          <button <?php echo ($value['kyc_status']==1)?'style="display:none;"':''; ?> data-id="<?php echo $value['user_id']; ?>" data-toggle="tooltip1" data-placement="top" title="Approve user's KYC" data-original-title="Approve user's KYC" class="btn btn-default kyc_up"><span class="glyphicon glyphicon-thumbs-up"></span></button>
          <button <?php echo ($value['kyc_status']==0)?'style="display:none;"':''; ?> data-id="<?php echo $value['user_id']; ?>" data-toggle="tooltip1" data-placement="top" title="Reject user's KYC" data-original-title="Reject user's KYC" class="btn btn-default kyc_down"><span class="glyphicon glyphicon-thumbs-down"></span></button>
          <?php //echo $value['transaction_status']; ?></td>
        </tr>
    <?php 
        $count++;
        }  
    
        }else{ ?>
        <tr>
            <td colspan="15"><center>No records found</center></td>
        </tr>
    <?php } ?>
        
      </tbody>
    </table>
<?php echo $pagination_lilnks; ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
  
    kycStatus = function(status_type,obj,btnHtml){
        var uid = obj.data('id');            
        $.ajax({
            url:'<?php echo base_url(); ?>admin/manage_kyc/kyc_status',
            type:'POST',
            dataType: "json",
            data:{id:uid,status:status_type}
        }).success(function(response){
            obj.html(btnHtml)
            var data = jQuery.parseJSON(JSON.stringify(response));
            var msg = data.msg;
            var status = data.status;
            if( status == 1 ){
                if(status_type=='approve'){                    
                    obj.hide();
                    $("tr.rowid_"+uid).find(".kyc_down").show();
                    $("tr.rowid_"+uid).find('span.glyphicon.'+uid).removeClass('glyphicon-remove red_kyc').addClass('glyphicon-ok green_kyc');
                }else if(status_type=='reject'){
                    obj.hide();
                    $("tr.rowid_"+uid).find(".kyc_up").show();
                    $("tr.rowid_"+uid).find('span.glyphicon.'+uid).removeClass('glyphicon-ok green_kyc').addClass('glyphicon-remove red_kyc');
                }
                $("#alertmsg").html( '<div class="alert alert-success custom-alert" role="alert">'+msg+'</div>');
            }else{
                $("#alertmsg").html( '<div class="alert alert-warning custom-alert" role="alert">'+msg+'</div>');
            }
            obj.attr("disabled",false);
        });        
    }
    $(".kyc_up").on("click",function(){
        var obj = $(this);
        var btnHtml = obj.html();
        obj.html('<img src="<?php echo base_url() ?>assets/images/loader.gif">');
        obj.attr("disabled",true);
        var verify = confirm("Are you sure to approve user's KYC?");
        if( verify == true ){
            kycStatus('approve',obj,btnHtml);
        }else{
            obj.html(btnHtml);
            obj.attr("disabled",false);
        }
    });
    $(".kyc_down").on("click",function(){
        var obj = $(this);
        var btnHtml = obj.html();
        obj.html('<img src="<?php echo base_url() ?>assets/images/loader.gif">');
        obj.attr("disabled",true);
        var verify = confirm("Are you sure to reject user's KYC?");
        if( verify == true ){
            kycStatus('reject',obj,btnHtml);
        }else{
            obj.html(btnHtml);
            obj.attr("disabled",false);
        }
    });
});   
</script>