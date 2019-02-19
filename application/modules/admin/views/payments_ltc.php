<div class="col-sm-12">    
<table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Transaction Id</th>
          <th>From Address</th>
          <th>To Address</th>
          <th>Amount</th>
          <th>Token Allocated</th>
          <th>Trans. Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $count = 1;
    if(isset($ltc_transactions) && is_array($ltc_transactions) && count($ltc_transactions)>0 ) { 
        foreach ($ltc_transactions as $key => $value) { ?>
        <tr>
          <td><?php echo $count; ?></th>
          <td><a target="_blank" href="https://live.blockcypher.com/ltc/tx/<?php echo $value['txid']; ?>"><?php echo substr($value['txid'],0,10); ?></a></td>
          <td><?php echo $value['address_from']; ?></td>
          <td><?php echo $value['address_to']; ?></td>
          <td><?php echo $value['amount']; ?></td>
          <!-- <td><?php // echo $value['vgw_token_allotted']; ?></td> -->
          <td>fetching ....</td>
          <td><?php echo $value['createdAt']; ?></td>
          <td><?php echo $value['status']; ?></td>
        </tr>
    <?php 
        $count++;
        }  
    
        }else{ ?>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th>No records found</th>
          <th></th>
          <td></td>
          <td></td>
          <td></td>
        </tr>
    <?php } ?>
        
      </tbody>
    </table>

</div>