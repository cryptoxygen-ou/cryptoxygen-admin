<div class="col-sm-12">
    
<table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>ERC20 Wallet Address</th>          
          <th>Date</th>
        </tr>
      </thead>

      
      <?php 
    $count = 1;
    if(isset($data_wallets) && is_array($data_wallets) && count($data_wallets)>0 ) { 
        foreach ($data_wallets as $key => $value) { ?>
      <tbody>
        <tr>
          <th><?php echo $count; ?></th>
          <th><?php echo $value['f_name']." ".$value['l_name']; ?></th>
          <th><?php echo $value['address']; ?></th>
          <th><?php echo $value['createdAt']; ?></th>
        </tr>
        <?php 
        $count++;
        }
    
    }else{ ?>
        <tr>
            <td colspan="4"><center>No records found</center></td>
        </tr>
    <?php } ?>
      </tbody>
    </table>

</div>