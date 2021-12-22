<?php
/**
 * @Name        Profile Settings
 * @Author      Ali Usman
 * @Description Template part to display Profile settings under {Settings}.
 * @Since       May 4, 2017
 */
//dashboard left menu should be a separate module in the future
?>
<?php
//get menus
include_once __DIR__."/../get-menus.php";
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
                <h2>Profile</h2>
                <?php
                global $current_user;
                $user_info = get_userdata($current_user->ID);
                if(isset($_POST['updateuser'])){
                    //action hook for user update
                    do_action('update_group_manager_settings',$current_user->ID);
                }
                ?>
                <form method="post" id="" action="<?php get_permalink(); ?>">
                    <p class="form-username">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name"
                               value="<?php echo $user_info->user_firstname; ?>"/>
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name"
                               value="<?php echo $user_info->user_lastname; ?>"/>
                    </p><!-- .form-username -->
                    <p class="form-email">
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                        <input class="text-input" name="email" type="text" id="email"
                               value="<?php echo $user_info->user_email; ?>"/>
                    </p><!-- .form-email -->
                    <p class="form-url">
                        <label for="url"><?php _e('Website', 'profile'); ?></label>
                        <input class="text-input" name="url" type="text" id="url"
                               value="<?php echo $user_info->user_url; ?>"/>
                    </p><!-- .form-url -->
                    <p class="form-password">
                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                        <input class="text-input" name="pass1" type="password" id="pass1"/>
                    </p> <!--.form-password -->
                    <p class="form-textarea">
                        <label for="description"><?php _e('Biographical Information', 'profile') ?></label>
                        <textarea name="description" id="description" rows="3"
                                  cols="50"><?php echo get_user_meta( $current_user->ID, 'description', true ); ?></textarea>
                    </p><!-- .form-textarea -->
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="updateuser" type="submit" id="updateuser" class="submit qbutton"
                               value="<?php _e('Update', 'profile'); ?>"/>
                        <?php wp_nonce_field( 'update-user' ) ?>
                        <input name="action" type="hidden" id="action" value="update-user"/>
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            </div>
        </div>
    </div>
</div>
