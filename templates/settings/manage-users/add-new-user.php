<?php
/**
 * @Name        Add New Users
 * @Author      Ali Usman
 * @Description Template part to display Add New User forms under {Manage Users}.
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
                <h2>Add New User <a href="#" title="how does it work?"><i class="fa fa-question-circle rp_right" aria-hidden="true"></i></a></h2>
                <?php
                $current_user = wp_get_current_user();
                //get groups ids of group manager
                if(learndash_is_group_leader_user($current_user->ID)){
                    $users_limit_remaining = get_user_meta('');
                    //get groups ids of group manager
                    $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
                }
                if(isset($_POST['insertuser'])){
                    //action hook to add new user
                    do_action('rp_insert_new_user',$current_user->ID);
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
                    <p class="form-email-user">
                        <label for="email-user"><?php _e('Email this user', 'profile'); ?> </label>
                        <input class="text-input" name="email-user" type="radio" id="email-user"/>
                    </p><!-- .form-password -->
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="insertuser" type="submit" id="insertuser" class="submit qbutton small rp_btn"
                               value="<?php _e('Add User', 'profile'); ?>"/>
                        <?php wp_nonce_field( 'insert-user' ) ?>
                        <input name="action" type="hidden" id="action" value="insert-user"/><input name="action" type="hidden" id="action" value="insert-user"/>
                        <!-- Manager Default Group Id used here in the next line -->
                        <input name="manager_group_id" type="hidden" id="manager_group_id" value="<?php echo $user_groups_ids[0];?>"/>
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
            </div>
        </div>
    </div>
</div>

