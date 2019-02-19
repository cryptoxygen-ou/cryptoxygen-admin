<!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="<?php echo base_url('admin/dashboard'); ?>">Cryptoxygen Admin</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                    <?php //echo "activeeee==".$active;?>
                    <li class="<?php  echo ($active == 'dashboard') ? 'active' : ''; ?>">
                        <a href="admin/dashboard"><span class="fa fa-file-text-o"></span><span class="xn-text">Dashboard</span></a>
                    </li>                   
                    <!-- <li class="<?php  //echo ($active == 'payments_btc') ? 'active' : ''; ?>">
                        <a href="admin/payments_btc"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Payments BTC</span></a>
                    </li> -->
                    <li class="<?php  echo ($active == 'payments_eth') ? 'active' : ''; ?>">
                        <a href="admin/payments_eth"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Payments ETH</span></a>
                    </li>
                    <!-- <li class="<?php  //echo ($active == 'payments_ltc') ? 'active' : ''; ?>">
                        <a href="admin/payments_ltc"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Payments LTC</span></a>
                    </li> -->
                    <li class="<?php  echo ($active == 'payments_fiat') ? 'active' : ''; ?>">
                        <a href="admin/payments_fiat"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Payments Fiat</span></a>
                    </li>
                    <li class="<?php  echo ($active == 'manage_kyc') ? 'active' : ''; ?>">
                        <a href="admin/manage_kyc"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Manage User's KYC</span></a>
                    </li>
                    <li class="<?php  echo ($active == 'userslist') ? 'active' : ''; ?>">
                        <a href="admin/users_list"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">ICO Users</span></a>
                    </li>
                    
                    <li class="<?php  echo ($active == 'user_wallets') ? 'active' : ''; ?>">
                        <a href="admin/user_ercwallets"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">User ERC20 Wallets</span></a>
                    </li>
                    <li class="<?php  echo ($active == 'token_distribution') ? 'active' : ''; ?>">
                        <a href="admin/token_distribution"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Tokens Distribution</span></a>
                    </li>
                    <!-- <li class="<?php  //echo ($active == 'tokens') ? 'active' : ''; ?>">
                        <a href="admin/tokens"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Tokens Transferred</span></a>
                    </li> -->
                    <li class="<?php  echo ($active == 'contract_settings') ? 'active' : ''; ?>">
                        <a href="admin/contract_settings"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">Contract Settings</span></a>
                    </li>
                    <li class="<?php  echo ($active == 'settings') ? 'active' : ''; ?>">
                        <a href="admin/ico_settings"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">ICO Settings</span></a>
                    </li>
                    <li class="<?php  echo ($active == 'update password') ? 'active' : ''; ?>">
                        <a href="admin/updatePassword"><span class="glyphicon glyphicon-th-list"></span><span class="xn-text">ADMIN  Settings</span></a>
                    </li>
                </ul>
                <!-- END X-NAVIGATION -->
            </div>            
            <!-- END PAGE SIDEBAR -->