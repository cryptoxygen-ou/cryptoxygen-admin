<div class="col-sm-12">
    
    <div class="col-sm-3"></div>
<div class="col-sm-6">
    
    <form method="post" action="admin/contract_settings/save">
    <div class="form-group">
    <label for="contract_addr">Wallet Address - Contract deployed from</label>
    <input value="<?php echo isset($wallet_address)?$wallet_address:'' ?>" name="wallet_address" type="text" class="form-control" id="wallet_address" placeholder="Wallet address - Contract deployed from">
  </div>
  <div class="form-group">
    <label for="contract_addr">Contract Address</label>
    <input value="<?php echo isset($contract_address)?$contract_address:'' ?>" name="contract_addr" type="text" class="form-control" id="contract_addr" placeholder="Contract Address">
  </div>
  <div class="form-group">
    <label for="contract_abi">Contract ABI</label>    
    <textarea class="form-control" rows="10" placeholder="Contract ABI" id="contract_abi" name="contract_abi"><?php echo isset($contract_abi)?$contract_abi:'' ?></textarea>
  </div>
  
  <button type="submit" class="btn btn-primary">Save</button>
</form>

</div>
    <div class="col-sm-3"></div>
</div>