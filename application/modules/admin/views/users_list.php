<div class="col-sm-12">
<input type="hidden" id="oneerc" />
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
          <!-- <th>City</th>
          <th>State</th> -->
          <th>Country</th>
          <!-- <th>Address</th> -->
          <!-- <th>ERC20</th> -->
          <th>Date</th>
          <th>Email Verified?</th>
          <th>Add Tokens</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $count = 1;
    $paginationCount =  $this->uri->segment('4');
    if( $paginationCount >1 ){
        //$p = $paginationCount-1;
        $pageCount = (100*$paginationCount)-99;
        $count = $pageCount;
    }

    if(isset($ico_users_list) && is_array($ico_users_list) && count($ico_users_list)>0 ) {

        foreach ($ico_users_list as $key => $value) {
            ?>
        <tr class="rowid_<?php echo $value['user_id']; ?>">
          <td><?php echo $count; ?></th>
          <td><?php echo $value['f_name']." ".$value['l_name']; ?></td>
          <td><?php echo $value['email']; ?></td>
          <td><?php echo $value['phone']; ?></td>
          <!-- <td><?php // echo $value['city']; ?></td> -->
          <!-- <td><?php // echo $value['state']; ?></td> -->
          <td><?php echo $value['nicename']; ?></td>
          <!-- <td><?php //echo $value['address']; ?></td> -->
          <!-- <td><?php //echo $value['wallet_address']; ?></td> -->
          <td><?php echo $value['createdAt']; ?></td>
          <td><?php echo ($value['email_status']==1)?"yes":"No"; ?></td>
          <td><button class="btn btn-danger" onclick="addToken('<?php echo $value['f_name']." ".$value['l_name']; ?>','<?php echo $value['email']; ?>','<?php echo $value['id']; ?>')" >Add Tokens</button></td>
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
<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add ERC20</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <input type="hidden" id="uid"/>
                <div class="form-group">
                    <label> Username: </label>
                    <span id="mUsername"></span>
                </div>
                <div class="form-group">
                    <label> Email: </label>
                    <span id="mEmail"></span>
                </div>
            
            <div class="checkbox">
            <label class="checkbox-inline">
                <input type="checkbox" id="bonusVal" name="bonusVal" value="1"> Bonus
            </label>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" id="amountVal" onkeyup="getTokenValue(this)" aria-label="Amount">
                </div>
                    <!-- <input type="text"  class="form-control" placeholder="Amount"> -->
            </div>
            <div class="form-group">
            <label>Token</label>
                <input type="text" id="totalTokens"  class="form-control" placeholder="Token">
            </div>
            <div style="display:none;" class="alert alert-success alertAddToken">
                <strong>Success!</strong>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnsave">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){  
    // $.ajax({
    //         url:'https://api.coinmarketcap.com/v2/ticker/?limit=10&structure=array',
    //         type:'GET',
    //         dataType: "json",
    //         data:{}
    //     }).success(function(response){

    //         var eth = parseFloat(response.data[2].quotes.USD.price) / parseFloat('<?php //echo $activePhaseToken; ?>');
    //         var oneerc = eth;
    //         $('#oneerc').val(oneerc.toFixed(5));
    //         $('#amountVal').val(oneerc.toFixed(5));
    //         $('#totalTokens').val(1);

    //     });
    $('#oneerc').val(<?php echo $activePhaseAmt; ?>);
    $('#amountVal').val(<?php echo $activePhaseAmt; ?>);
    $('#totalTokens').val(1);  
});   


function addToken( name,email,id ){
  


    $('#uid').val(id);
    $('#mUsername').html(name);
    $('#mEmail').html(email);
    $('.modal').modal('show');
};
function getTokenValue(obj){

    

    var tokens = $(obj).val() / $('#oneerc').val();
    parseFloat($('#totalTokens').val(tokens)).toFixed(5);
}
$('#btnsave').on('click',function(){
    $('#btnsave').attr('disabled','disabled');
    var bonusCheck = ($('#bonusVal:checked').val())?$('#bonusVal:checked').val():0;
    $.ajax({
            url:'<?php echo base_url('/admin/users_list/addtoken'); ?>',
            type:'POST',
            dataType: "json",
            data:{ uid:$('#uid').val(),tokens:parseFloat($('#totalTokens').val()).toFixed(5),amount:parseFloat($('#amountVal').val()).toFixed(5),bonus_check: bonusCheck},
        }).success(function(response){
           $('.alertAddToken').css('display','block');

           setTimeout(function(){               
            $('.modal').modal('hide');
            $('.alertAddToken').css('display','none');
           }, 2000);
           $('#btnsave').removeAttr('disabled');
           $('#totalTokens').val('');
           $('#amountVal').val('');
           $('#bonusVal').prop('checked',false);
        }); 
});
</script>