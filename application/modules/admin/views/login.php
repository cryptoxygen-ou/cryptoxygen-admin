<div class="login-container lightmode">
    <div class="login-box animated fadeInDown">
        <!-- <div class="login-logo">etst</div> -->
        <?php 
        if($this->session->flashdata()){
            echo showErrorMessages($this->session->flashdata());
        }
        ?>
        <div class="login-body">
            <div class="login-title">
                <strong>Log In</strong> to your account
            </div>
            <form action="<?php echo base_url('admin/login'); ?>" class="form-horizontal" method="post" id="loginForm">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="email" class="form-control" name="email" placeholder="E-mail"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" class="form-control" name="password" placeholder="Passwordks" autocomplete="off"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <?php /*<a href="<?php echo base_url('admin/forgot_password'); ?>" class="btn btn-link btn-block">
                            Forgot your password?
                        </a>*/ ?>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-info btn-block" type="submit">Log In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
