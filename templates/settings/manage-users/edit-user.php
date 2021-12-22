<?php
/**
 * @Name        Edit User
 * @Author      Ali Usman
 * @Description Template part to display Update User forms under {Manage Users}.
 * @Since       May 4, 2017
 */
?>
<?php
//get menus
include_once __DIR__."/../../get-menus.php";
?>
<div class="wpb_column vc_column_container vc_col-sm-2">
    <div class="vc_column-inner " style="padding-left:0px !important;padding-right:0px !important;">
        <div class="wpb_wrapper">
            <div id="dashboard_left">
                <?php echo $parent_items; ?>
            </div>
        </div>
    </div>
</div>

<div class="wpb_column vc_column_container vc_col-sm-10">
    <div class="vc_column-inner " style="padding-left:0px !important;">
        <div class="wpb_wrapper">
            <div id="dashboard_right">
                <?php
                echo $child_items;
                ?>
                <div class="separator normal" style="background-color: #eaeaea;"></div>
                <h2>Edit User <a href="#" title="how does it work?"><i class="fa fa-question-circle rp_right" aria-hidden="true"></i></a></h2>
                <?php
                $all_meta_for_user = get_user_meta( $endpoints[5] );
                print_r( $all_meta_for_user );

                if(isset($_POST['updateuser'])){
                    if(!empty($endpoints[5]) AND is_numeric($endpoints[5])){
                        $edited_user_id = $endpoints[5];
                        //action hook to update user
                        do_action('rp_update_user',intval($edited_user_id) );

                        //email user about the update
                        do_action_ref_array('rp_send_email_to_user');
                    }else{
                        echo 'user id not numeric or empty.';
                    }
                }

                $user_info = get_userdata($endpoints[5]);
//                $all_meta_for_user = get_user_meta( $endpoints[5] );
//                print_r( $all_meta_for_user );

                if(!empty($endpoints[5]) AND is_numeric($endpoints[5])){
                ?>
                <form method="post" id="" action="<?php get_permalink(); ?>">
                    <p class="form-username">
                        <label for="user-name"><?php _e('Username', 'profile'); ?></label>
                        <input class="text-input" name="user-name" type="text" id="user-name"
                               value="<?php echo $user_info->user_login;?>"/>
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name"
                               value="<?php echo $user_info->user_firstname;?>"/>
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name"
                               value="<?php echo $user_info->user_lastname;?>"/>
                    </p><!-- .form-username -->
                    <p class="form-email">
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                        <input class="text-input" name="email" type="text" id="email"
                               value="<?php echo $user_info->user_email;?>"/>
                    </p><!-- .form-email -->
                    <p class="form-password">
                        <label for="password"><?php _e('Password *', 'profile'); ?> </label>
                        <input class="text-input" name="password" type="password" id="password" value="<?php echo $user_info->user_pass;?>"/>
                    </p><!-- .form-password -->
                    <p class="form-email-me">
                        <label for="email-me"><?php _e('Email this user', 'profile'); ?> </label>
                        <input class="text-input" name="email-me" type="radio" id="email-me"/>
                    </p><!-- .form-password -->
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="updateuser" type="submit" id="updateuser" class="submit qbutton small rp_btn"
                               value="<?php _e('Update User', 'profile'); ?>"/>
                        <?php wp_nonce_field( 'update-user' ) ?>
                        <input name="action" type="hidden" id="action" value="update-user"/>
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
                <?php
                }
?>
            </div>
        </div>
    </div>
</div>

