<?php
/**
 * @Name        Users Management
 * @Author      Ali Usman
 * @Description Template part to display users management under {Settings}.
 * @Since       May 4, 2017
 */
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
                global $wpdb;
                $current_user = wp_get_current_user();

if (learndash_is_group_leader_user($current_user->ID)) {
                    //get groups ids of group manager
                    $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
                    //$user_groups_course_ids = learndash_get_groups_courses_ids( $current_user->ID, $user_groups_ids );
                    //var_dump($user_groups_course_ids);
                    $admin_group_names_string .= '<select id="languages" name="languages" multiple >';
                        if (!empty($user_groups_ids)) {
                            ?>
                            <div>
                            <?php
                            //get all groups users
                            //limited the Group to 1 only
                            for ($g = 0; $g <= 0; $g++) {
                                //echo 'GROUP:'.$user_groups_ids[$g].'<br>';
                                $user_groups_users_ids = learndash_get_groups_users($user_groups_ids[$g], true);
                                $group = get_post($user_groups_ids[$g], OBJECT, 'raw');
                                ?>
                                <h3 class="rp_heading">
                                    <?php
                                    echo $group->post_title;
                                    $group_name = strtolower(str_replace(' ', '-', $group->post_title));
                                    ?><span style="font-size: 16px;
    text-transform: none;
    letter-spacing: 0px;
    font-weight: normal;">&nbsp;&nbsp;<span style="color:silver">></span>&nbsp;&nbsp;Users limit:
                                        <b><?php
                                            $all_meta_for_user = get_user_meta( $current_user->ID );
                                            //print_r( $all_meta_for_user );
                                            echo get_user_meta( $current_user->ID , 'rp_'.$group_name.'_users_limit' , true )?></b>
                                        &nbsp;&nbsp;<span
                                                style="color:silver">></span>&nbsp;&nbsp;<a href="<?php echo site_url() . '/enterprise_dashboard/settings/payments/extend' ?>"
                                                                                            title="Manage users limit for this group"><i
                                                    class="fa fa-edit fa-lg" style="margin:0 !important;"></i> Manage limit</a>
                    </span></h3>

                                <table class="subscriptions_table cart" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="product-name">Avatar</th>
                                        <th class="product-name">Name</th>
                                        <th class="product-status">Email</th>
                                        <th class="product-ordered-on">Action(s)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $total = count($user_groups_users_ids) - 1;

                                    //limit user groups to 1
                                    for ($i = 0; $i <= $total; $i++) {
                                        ?>
                                        <tr>
                                            <td class="avatar"><img style="width:32px;height:32px;"
                                                                    src="<?php echo get_avatar_url($user_groups_users_ids[$i]->data->ID, 32); ?>"
                                                                    alt="avatar"/></td>
                                            <td class="product-name"><?php echo $user_groups_users_ids[$i]->data->display_name; ?></td>
                                            <td class="product-status"><?php echo $user_groups_users_ids[$i]->data->user_email; ?></td>
                                            <td class="product-ordered-on">
                                                <?php echo '<a class="sendMessageToUser rp_link " id="message_' . $user_groups_users_ids[$i]->data->ID . '" name="message_' . $user_groups_ids[$g] . '" href="#" title="Send Message"><i class="fa fa-envelope-o"></i> </a>'; ?>
                                                <?php echo '&nbsp;|&nbsp;&nbsp;<a class="rp_link " href="' . site_url() . '/enterprise_dashboard/settings/manage-users/edit-user/' . $user_groups_users_ids[$i]->data->ID . '" title="Edit User"><i class="fa fa-edit"></i> </a>'; ?>
                                                <?php echo '&nbsp;|&nbsp;&nbsp;<a class="removeUserFromGroup rp_link " id="' . $user_groups_users_ids[$i]->data->ID . '" name="' . $user_groups_ids[$g] . '" href="#" title="Remove from Group"><i class="fa fa-remove"></i> </a>'; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <br>
                                <?php
                            }
                            ?>
                            <?php
                            //$user_groups_ids
                            $admin_group_names_string .= '<option value="' . $group->ID . '">' . $group->post_title . '</option>';
                            ?>
                    </div>

                    <!--<h2>Users Enrolled in Groups</h2>
                    <div class="admin_group_assigments">
                        <div class="wpb_column vc_column_container vc_col-sm-6">
                            <div class="vc_column-inner "
                                 style="padding-left:0px !important;padding-right:0px !important;">
                                <div class="wpb_wrapper">
                                    <?php
/*                                    $admin_group_names_string .= '</select>';
                                    echo $admin_group_names_string;
                                    */?>
                                </div>
                            </div>
                        </div>
                        <div class="wpb_column vc_column_container vc_col-sm-6">
                            <div class="vc_column-inner "
                                 style="padding-left:0px !important;padding-right:0px !important;">
                                <div class="wpb_wrapper">
                                    <?php
/*                                    $admin_group_names_string .= '</select>';
                                    echo $admin_group_names_string;
                                    */?>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <!--___________________________SEPERATOR____________________________-->
                    <div class="separator normal" style="background-color: #eaeaea;"></div>
                    <a href="<?php echo site_url() . '/enterprise_dashboard/settings/manage-users/add-new-user'; ?>"
                       target="_self" data-hover-color="#ffffff" class="submit qbutton small rp_btn"
                       style="color: rgb(48, 48, 48);">Add New User</a>
                    <?php
                 }else {
                            echo 'You have not created any group yet.'.'<a href="#" title="Coming Soon">Create New Group</a>';
                 }
                }
                ?>

            </div>
        </div>
    </div>
</div>


