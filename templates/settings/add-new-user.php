<?php
/**
 * @Name        Add New Users
 * @Author      Ali Usman
 * @Description Template part to display Add New User forms under {Manage Users}.
 * @Since       May 4, 2017
 */
?>
<div class="wpb_column vc_column_container vc_col-sm-2">
    <div class="vc_column-inner " style="padding-left:0px !important;padding-right:0px !important;">
        <div class="wpb_wrapper">
            <div id="dashboard_left">
                <ul>
                    <li><a href="#" title=""><i class="fa fa-tachometer" aria-hidden="true"></i>Dashboard </a></li>
                    <li><a href="#" title=""><i class="fa fa-users" aria-hidden="true"></i> Users </a></li>
                    <li><a href="#" title=""><i class="fa fa-university" aria-hidden="true"></i> Courses </a></li>
                    <li class="active"><a href="#" title=""><i class="fa fa-sun-o" aria-hidden="true"></i>Settings</a>
                    </li>
                    <li><a href="#" title=""><i class="fa fa-download" aria-hidden="true"></i>Export </a></li>
                    <li><a href="#" title=""><i class="fa fa-sign-out" aria-hidden="true"></i>Logout </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="wpb_column vc_column_container vc_col-sm-10">
    <div class="vc_column-inner " style="padding-left:0px !important;">
        <div class="wpb_wrapper">
            <div id="dashboard_right">
                <ul>
                    <li><a href="/" title="">Account</a></li>
                    <li class="active"><a href="enterprise_dashboard/settings/users-management" title="">Manage Users</a></li>
                    <li><a href="enterprise_dashboard/settings/payments" title="">Payments</a></li>
                    <li><a href="#" title="">+ Add More</a></li>
                </ul>
                <div class="separator  normal" style="background-color: #eaeaea;"></div>
                <h2>Add New User <a href="#" title="how does it work?"><i class="fa fa-question-circle rp_right" aria-hidden="true"></i></a></h2>
                <?php
                $current_user = wp_get_current_user();
                if(isset($_POST['insertuser'])){
                    //action hook to add new user
                    do_action('insert_new_group_user',$current_user->ID);
                }
                ?>
                <form method="post" id="" action="<?php get_permalink(); ?>">
                    <p class="form-username">
                        <label for="user-name"><?php _e('Username', 'profile'); ?></label>
                        <input class="text-input" name="user-name" type="text" id="user-name"
                               value=""/>
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name"
                               value=""/>
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name"
                               value=""/>
                    </p><!-- .form-username -->
                    <p class="form-email">
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                        <input class="text-input" name="email" type="text" id="email"
                               value=""/>
                    </p><!-- .form-email -->
                    <p class="form-password">
                        <label for="password"><?php _e('Password *', 'profile'); ?> </label>
                        <input class="text-input" name="password" type="password" id="password"/>
                    </p><!-- .form-password -->
                    <p class="form-email-me">
                        <label for="email-me"><?php _e('Email this user', 'profile'); ?> </label>
                        <input class="text-input" name="email-me" type="radio" id="email-me"/>
                    </p><!-- .form-password -->
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="insertuser" type="submit" id="insertuser" class="submit qbutton small rp_btn"
                               value="<?php _e('Add User', 'profile'); ?>"/>
                        <?php wp_nonce_field( 'insert-user' ) ?>
                        <input name="action" type="hidden" id="action" value="insert-user"/>
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            </div>
        </div>
    </div>
</div>

