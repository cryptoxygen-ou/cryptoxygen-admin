<div class="col-sm-12">

<form class="navbar-form navbar-left" role="search"  type="get" action="admin/users_list/" style="float:right !important">
    <div class="form-group">
    <input type="text" class="form-control" placeholder="Search" name="s" value="<?php echo isset($_GET['s'])?($_GET['s']!='')?$_GET['s']:'':''; ?>">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <a class="btn btn-default" href="admin/users_list">Reset</a>
</form>

<table class="table table-hover table-responsive">
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
          <th>ERC20</th>
          <th>Date</th>
          <th>Email Verified?</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $count = 1;
    if(isset($ico_users_list) && is_array($ico_users_list) && count($ico_users_list)>0 ) {

        foreach ($ico_users_list as $key => $value) {
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
          <td><?php echo $value['wallet_address']; ?></td>
          <td><?php echo $value['createdAt']; ?></td>
          <td><?php echo ($value['email_status']==1)?"yes":"No"; ?></td>
        </tr>
    <?php 
        $count++;
        }  
    
        }else{ ?>
        <tr>
            <td colspan="11"><center>No records found</center></td>
        </tr>
    <?php } ?>
        
      </tbody>
    </table>
<?php echo $pagination_lilnks; ?>
</div>

<script type="text/javascript">
$(document).ready(function(){    
});   
</script>