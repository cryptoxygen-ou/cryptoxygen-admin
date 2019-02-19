
    
<style>
.row{
    margin:12px;
}
</style>

  <form method="post" action="admin/updatePassword">
  <div class="row">
        <div class="col-md-12">
            <label>Current Password:</label>
            <input type="password" name="current_password" class="form-control" placeholder="Current Password"/>
        </div>
        </div>
    <div class="row">
        <div class="col-md-12">
            <label>New Password:</label>
            <input type="password" name="new_password" class="form-control" placeholder="New Password"/>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
            <label>confirm Password:</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password"/>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
            <input type="submit" class="pull-right btn btn-success" value="Update Password">
        </div>
        </div>
</form>
	
<!-- END BASIC TABLE SAMPLE --> 