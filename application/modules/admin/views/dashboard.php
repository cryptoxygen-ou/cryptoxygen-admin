<!-- START BASIC TABLE SAMPLE -->
<script type="text/javascript">
    <!-- Web3 implementation -->
    if (typeof web3 !== 'undefined') {  
        web3 = new Web3(web3.currentProvider);
    } else {
        // set the provider you want from Web3.providers
        web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
    }
    
    var alertMSG;
    var accounts = web3.eth.getAccounts(function(err, accounts){
    
    if(accounts.length==0){
        alertMSG = '<div class="alert alert-warning custom-alert" role="alert">Please login to MetaMask Extension to fetch Token Sale details from Ethereum blockchain and refresh page</div>';
    }else{
        alertMSG = '<div class="alert alert-success custom-alert" role="alert">Your MetaMask account: '+web3.eth.accounts[0]+'</div>';
    }
    $("#alertmsg").html(alertMSG);
    });
    
    var contractAddress = '<?php echo isset($contract_address)?$contract_address:""; ?>';
    var contractAbi = web3.eth.contract(<?php echo isset($contract_abi)?$contract_abi:""; ?>);
    var walletAddress = '<?php echo isset($wallet_address)?$wallet_address:""; ?>';
                                                                                                           
    var contractObj = contractAbi.at(contractAddress);    

    var a = contractObj.balanceOf(walletAddress, function(err, result){

        if(err){
            $("#alertmsg").html('<div class="alert alert-warning custom-alert" role="alert">Unable to fetch data from Ethereum blockchain</div>');
        }else{
            console.log(result)
            var totaltokens = $("#totaltokens").data('totaltokens');
            var publictokens = $("#publictokens").data('publictokens');
            var availableTokensWei = result.toNumber();
            //var availableTokensWei = balance;
            if(availableTokensWei>0){
                //Get token decimals
                var decimals = 5;
                var availableTokensWeiBalance = parseFloat(availableTokensWei) / Math.pow(10, decimals);        
                var formattedBal = availableTokensWeiBalance.toLocaleString();
                $("#aval_token").text(formattedBal);
                var sold_token = publictokens-availableTokensWeiBalance;
                sold_token = sold_token.toLocaleString();
                $("#sold_token").text(sold_token);
            }
        }
    });
</script>
    
    
    <?php  //print_r($data); ?>
<div class="col-sm-12">
    
    <div class="col-sm-4">
        <div class="panel panel-danger">
            <div class="panel-heading"><h3 class="panel-title">Crypto Currency Received</h3></div>
            <div class="panel-body">
            <table class="table table-condensed">
                <tbody>              
                    <!-- <tr class="success"><td>BTC</td><td>=</td><td><label><?php //echo isset($total_btc)?$total_btc:0; ?></label></td></tr> -->
                  <tr class="warning"><td>ETH</td><td>=</td><td><label><?php echo isset($total_eth)?$total_eth:0; ?></label></td></tr>
                  <!-- <tr class="danger"><td>LTC</td><td>=</td><td><label><?php //echo isset($total_ltc)?$total_ltc:0; ?></label></td></tr> -->
  <!--                <tr class="info"><td>BTC</td><td>123</td></tr>-->
                  </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="panel panel-success">
            <div class="panel-heading"><h3 class="panel-title">ERC20 Token Sale</h3></div>
            <div class="panel-body">
            <table class="table table-condensed">
                <tbody>
                    <tr class="warning"><td>Total Tokens</td><td>=</td><td><label id="totaltokens" data-totaltokens="250000000">250,000,000</label></td></tr>
                    <tr class="success"><td>Public Tokens</td><td>=</td><td><label id="publictokens" data-publictokens="150000000">150,000,000</label></td></tr>
                    <tr class="danger"><td>Available Tokens</td><td>=</td><td><label id="aval_token"></label></td></tr>
                    <tr class="active"><td>Tokens Sold 
                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="We are pulling this data from ERC20 Contract (Blockchain), Login to MetaMask extension is essential."></span>
                        </td><td>=</td><td><label id="sold_token"></label></td></tr>
                  </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="panel panel-info">
            <div class="panel-heading"><h3 class="panel-title">Payments Received</h3></div>
            <div class="panel-body">
              <table class="table table-condensed">
                <tbody>              
                  <tr class="success"><td>Total ($)</td><td>=</td><td><label><?php echo isset($total_usd)?$total_usd:0; ?></label></td></tr>
<!--                  <tr class="warning"><td>Tokens Sold</td><td>=</td><td>xx</td></tr>-->
                  </tbody>
            </table>
            </div>
        </div>
    </div>

</div>

<div class="row">
<div class="col-sm-12">
    <div class="col-sm-8">
        <div class="panel panel-danger">
            <div class="panel-heading"><h3 class="panel-title">Crypto to Fiat</h3></div>
            <div class="panel-body">
            <table class="table table-condensed">
                <?php 
                $ethPriceUSDTotal = $btcPriceUSDTotal = $ltcPriceUSDTotal = 0;
                if( isset($crypto_rate)&& is_array($crypto_rate) ){
                    $ethPriceUSD = $crypto_rate['eth_price_usd'];
                    $btcPriceUSD = $crypto_rate['btc_price_usd'];
                    $ltcPriceUSD = $crypto_rate['ltc_price_usd'];                 
                    $ethPriceUSDTotal = $ethPriceUSD*$total_eth;
                    $btcPriceUSDTotal = $btcPriceUSD*$total_btc;
                    $ltcPriceUSDTotal = $ltcPriceUSD*$total_ltc;
                  
                }
                ?>
                <tbody>              
                    <!-- <tr class="success">
                        <td>BTC</td><td>=</td><td><label><?php //echo isset($total_btc)?$total_btc:0; ?></label></td><td>=</td><td>$<?php //echo number_format($btcPriceUSDTotal,2); ?></td></tr> -->
                  <tr class="warning"><td>ETH</td><td>=</td><td><label><?php echo isset($total_eth)?$total_eth:0; ?></label></td><td>=</td><td>$<?php echo number_format($ethPriceUSDTotal,2); ?></td></tr>
                  <!-- <tr class="danger"><td>LTC</td><td>=</td><td><label><?php //echo isset($total_ltc)?$total_ltc:0; ?></label></td><td>=</td><td>$<?php //echo number_format($ltcPriceUSDTotal,2); ?></td></tr> -->
  <!--                <tr class="info"><td>BTC</td><td>123</td></tr>-->
                  </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-sm-12">
    <div class="col-sm-8">
        <div class="panel panel-danger">
            <div class="panel-heading"><h3 class="panel-title">Sold Tokens</h3></div>
            <div class="panel-body">
            <table class="table table-condensed">
              
                <tbody>              
                    <tr class="success">
                        <td>Total Sold Tokens(From DB)</td>
                        <td>=</td>
                        <td><?php echo number_format($totalSoldtoken,5); ?></td>
                        </tr>
          
                  </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
	
<!-- END BASIC TABLE SAMPLE --> 