<div class="col-sm-12">    
<table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Email</th>
          <th>Payment($)</th>
          <th>ERC20 alloted</th>
          <th>Tx. Id</th>
          <th>Tx. Date</th>
          <th>Bonus <br/><small>Mark payment as bonus</small></th>
        </tr>
      </thead>
      <tbody>
<?php 
    $count = 1;
    if(isset($fiat_transactions) && is_array($fiat_transactions) && count($fiat_transactions)>0 ) { 
        foreach ($fiat_transactions as $key => $value) { ?>
        <tr>
          <td><?php echo $count; ?></th>
          <td><?php echo $value['f_name']." ".$value['l_name'] ?></td>
          <td><?php echo $value['email']; ?></td>
          <td>$<?php echo number_format($value['price'],2); ?></td>
          <td><?php echo number_format($value['erc_tokens'],5); ?></td>
          <td><?php echo $value['transaction_id']; ?></td>
          <td><?php echo $value['createdAt']; ?></td>
          <td>
          <?php if($value['bonus']=='YES'){ ?>
            <input class="class_bonus" data-rowid="<?php echo $value['id']?>" type="checkbox"  value="1" checked="checked" onchange='updateBonus(<?php echo $value['id']?>,<?php echo $value['user_id']?>,this)'>
          <?php }else{ ?>
            <input class="class_bonus" data-rowid="<?php echo $value['id']?>" type="checkbox" value="1"  onchange='updateBonus(<?php echo $value['id']?>,<?php echo $value['user_id']?>,this)'>
          <?php } ?>
          </td>
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
          <td></td>
          <td></td>
          <td></td>
        </tr>
    <?php } ?>
        
      </tbody>
    </table>

</div>

<script type="text/javascript">
function updateBonus(rowid,userid,obj){

    var r = confirm("Do you want to update user bonus status?");
    if (r == true) {
        var bonusCheck;
        if( $(obj).is(':checked') ){
            bonusCheck = 'YES';
        }else{
            bonusCheck = 'NO';
        }
        var userid = userid;
        $.ajax({
            url:'<?php echo base_url('/admin/payments_fiat/updatebonus'); ?>',
            type:'POST',
            dataType: "json",
            cache: false,
            data:{ record_id:rowid,uid:userid,bonus_check: bonusCheck },
            success: function(response){}
        }).done(function(){
            alert("done");
        })
        setTimeout(() => {
            alert("User bonus status updated successfully!");    
        }, 1500);
        
    } else {
        if( $(obj).is(':checked') ){
            $(obj).prop('checked',false)
        }else{
            $(obj).prop('checked',true)
        }
    }
}
</script>