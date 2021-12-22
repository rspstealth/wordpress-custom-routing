<?php
/**
 * @Name        Group Settings
 * @Author      Ali Usman
 * @Description Template part to display group settings under {Settings}.
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
                <?php
                $current_user = wp_get_current_user();

                if (learndash_is_group_leader_user($current_user->ID)) {
                    //get groups ids of group manager
                    $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
                    if(!empty($user_groups_ids)){
                        ?>
                <form method="post" id="update_group_settings_form" action="<?php get_permalink(); ?>">
                    <?php
                    $group = get_post( $user_groups_ids[0], OBJECT, 'edit' );
                    $content = $group->post_content;
                    ?>
                    <p class="form-username">
                        <label for="group-name">Group Name</label>
                        <input class="text-input" name="group-name" type="text" id="group-name"
                               value="<?php echo $group->post_title;?>"/>
                    </p><!-- .form-username -->
                    <?php

                        $editor_id = 'editpost';
                        $settings = array( 'media_buttons' => true , 'drag_drop_upload' => true);
                        wp_editor( $content, $editor_id, $settings );
                    ?>
                    <p class="form-submit">
                        <?php echo $referer; ?>
                    <input name="update_group" type="submit" id="update_group" class="submit qbutton"
                           value="<?php _e('Update', 'profile'); ?>"/>
                    </p><!-- .form-submit -->
                </form><!-- #adduser -->
                        <?php
                        if(isset($_POST['update_group'])){
                            echo 'submitted';
                            //action hook for user update
                            do_action('rp_update_group_settings',$user_groups_ids[0]);
                        }
                    }else {
                        echo 'You have not created any group yet.' . '<a href="#" title="Coming Soon">Create New Group</a>';
                    }
                }


                ?>



            </div>
        </div>
    </div>
</div>
