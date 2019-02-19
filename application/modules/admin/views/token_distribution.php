<div class="col-sm-12">
<input type="hidden" id="oneerc" />
<form class="navbar-form navbar-left" role="search"  type="get" action="admin/token_distribution/" style="float:right !important">
    <div class="form-group">
    <input type="text" class="form-control" placeholder="Search" name="s" value="<?php echo isset($_GET['s'])?($_GET['s']!='')?$_GET['s']:'':''; ?>">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <a class="btn btn-default" href="admin/token_distribution">Reset</a>
</form>

<table class="table table-hover table-responsive">
      <thead>
        <tr>
          <!-- <th><input type="checkbox" id="select_all"></th> -->
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>ERC Wallet</th>
          <th>Tokens</th>
          <!-- <th>Send Tokens</th> -->
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
        <tr class="rowid_<?php echo $value['id']; ?>">
          <!-- <td><input class="checkbox" type="checkbox" onclick="checkRec('<?php //echo $value['address']; ?>','<?php //echo $value['totalVal']; ?>')"></td> -->
          <td><?php echo $count; ?></th>
          <td><?php echo $value['f_name']." ".$value['l_name']; ?></td>
          <td><?php echo $value['email']; ?></td>
          <td><?php echo $value['address']; ?></td>
          <td><?php echo $value['totalVal']; ?></td>
          <!-- <td><button class="btn btn-danger" onclick="sendToken('<?php //echo $value['address']; ?>','<?php //echo $value['totalVal']; ?>')" >Send</button></td> -->
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
<div class="modal1"><!-- Place at bottom of page --></div>
<script type="text/javascript">
// $(document).ready(function(){ 

// });  
var reqData = [];
// function checkRec( address,tokens ){
//     var jsonData = {ercwallet:address,token:tokens};
//     reqData.push(jsonData);
//     console.log(reqData);
// }

$("#select_all").change(function(){  //"select all" change 
    $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    if($(this).prop("checked") == true){
        
    }
});

//".checkbox" change 
$('.checkbox').change(function(){ 
	//uncheck "select all", if one of the listed checkbox item is unchecked
    if(false == $(this).prop("checked")){ //if this item is unchecked
        $("#select_all").prop('checked', false); //change "select all" checked status to false
    }
	//check "select all" if all checkbox items are checked
	if ($('.checkbox:checked').length == $('.checkbox').length ){
		$("#select_all").prop('checked', true);
	}
});

function sendToken( address,tokens ){
    var verify = confirm("Are you sure to send tokens to selected ERC wallet?");
    if( verify == true ){
        $('.modal1').css('display','block');    
         $.ajax({
                url: '<?php echo API_URL;?>/v1/distribute_token',
                type: "POST",
                dataType: "json",
                crossDomain: true,
                async: true,
                data:{ "ercwallet":address,"token":tokens },
                //headers: { 'Access-Control-Allow-Origin': '*' },
                success: function (data) {
                     console.log(data);
                    $('.modal1').css('display','none');
                    $("#alertmsg").html( '<div class="alert alert-success custom-alert" role="alert">Tokens has been sent to selected ERC wallets!</div>');
                },
                error: function (err) {
                    console.log(err);
                    $('.modal1').css('display','none');
                    $("#alertmsg").html( '<div class="alert alert-success custom-alert" role="alert">Tokens has been sent to selected ERC wallets!</div>');
                }
            })
            // .success(function(response){
            //     callbackfn();
            // });
    }
        
};

</script>
