<div class="col-sm-12">
    
    <div class="col-sm-3"></div>
<div class="col-sm-6">
    
    <form method="post" action="admin/ico_settings/save">
    <div class="form-group">
        <label for="contract_addr">ICO Phase active</label>
        <select name="ico_phase" class="form-control">
            <option>Active ICO Phase</option>
            <option <?php echo ($data_icophases['id']==1)?"Selected":'' ?> value="1">Phase 1</option>
            <option <?php echo ($data_icophases['id']==2)?"Selected":'' ?> value="2">Phase 2</option>
            <option <?php echo ($data_icophases['id']==3)?"Selected":'' ?> value="3">Phase 3</option>
        </select>    
    </div>  

    <div class="form-group">
        <label for="contract_addr">ICO End Date</label>
        <input value="<?php echo isset($data_icophases['end_date'])?$data_icophases['end_date']:'' ?>" name="ico_enddate" type="text" class="form-control datepicker" id="ico_enddate" placeholder="ICO end date">        
    </div>
  
  <button type="submit" class="btn btn-primary">Save</button>
</form>

</div>
    <div class="col-sm-3"></div>
</div>
