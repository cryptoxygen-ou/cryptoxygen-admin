<div class="col-sm-12">
    
<table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Transaction Id</th>
          <th>ERC20 addrs.</th>
          <th>ERC20 Tokens</th>
          <!-- <th>Token Allocated</th> -->
          <th>Trans. Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>

      <?php 
    $count = 1;
    if(isset($tokens_list) && is_array($tokens_list) && count($tokens_list)>0 ) { 
        foreach ($tokens_list as $key => $value) { ?>

        <tr>
          <th><?php echo $count; ?></th>
          <th><?php echo $value['first_name']." ".$value['last_name']; ?></th>
          <th><a title="<?php echo $value['tx_id']; ?>" target="_blank" href="<?php echo ETH_SCN_URL; ?>/tx/<?php echo $value['tx_id']; ?>"><?php echo substr($value['tx_id'],0,10); ?>..</a></th>
          <th><a title="<?php echo $value['erc20_address']; ?>" target="_blank" href="<?php echo ETH_SCN_URL; ?>/address/<?php echo $value['erc20_address']; ?>"><?php echo $value['erc20_address']; ?></a>
          </th>
          <th><?php echo $value['token_amount'] ?></th>
          <th><?php echo $value['transfer_date'] ?></th>
          <th><?php echo $value['transfer_status'] == 1 ? 'Sent' : 'Failed'; ?></th>
        </tr>
        <?php 
        $count++;
            } 
        }else{
            echo '<td colspan="7"><center>No records found</center></td>';
        }
            ?>
        
      </tbody>
    </table>

</div>