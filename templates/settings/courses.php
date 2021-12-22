<?php
/**
 * @Name        Courses Management
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

                <div class="admin_group_course_assigments" style=" width: 100%;
    margin: 10px 0;
    display: flex;
    min-height: 200px">
                    <?php
                    global $wpdb;
                    $current_user = wp_get_current_user();

                    //get groups ids of group manager
                    if (learndash_is_group_leader_user($current_user->ID)){
                        //get groups ids of group manager
                    $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
                    $user_groups_users_ids = learndash_get_groups_users($user_groups_ids[0], true);
                    $total = count($user_groups_users_ids) - 1;

                    if (!empty($user_groups_ids[0])) {
                        if (!empty($user_groups_users_ids)) {
                        ?>
                        <form method="post" style="width:100%" id="assign_group_courses_to_group_users"
                              action="<?php get_permalink(); ?>">


                            <div class="wpb_column vc_column_container vc_col-sm-6" style="    padding-right: 10px;">
                                <div class="vc_column-inner "
                                     style="padding-left:0px !important;padding-right:0px !important;">
                                    <div class="wpb_wrapper">


                                        <h3 class="rp_heading">Select Courses</h3>
                                        <?php
                                        for ($g = 0; $g <= 0; $g++) {
                                            echo '<select style="    width: 100%;    box-shadow: 0px 1px 1px silver;
    border-radius: 4px;
    font-size: 14px;
    border: none;
    background: transparent;
    font-size: 14px;
    height: 300px;" id="group_courses" name="group_courses" multiple>';
                                            $user_groups_course_ids = learndash_group_enrolled_courses($user_groups_ids[$g], true);
                                            //var_export($user_groups_course_ids);
                                            foreach ($user_groups_course_ids as $course_id) {
                                                echo '$course_id' . $course_id;
                                                $course = get_post($course_id, OBJECT, 'raw');
                                                echo '<option style="height:20px;" value="' . $course->ID . '">' . $course->post_title . '</option>';

                                            }
                                            echo '</select>';
                                            //$course = get_post( $user_groups_ids[$g] , OBJECT, 'raw' );
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>


                            <?php
                            $group = get_post($user_groups_ids[0], OBJECT, 'raw');

                            ?>
                            <div class="wpb_column vc_column_container vc_col-sm-6" style="min-height:300px;">
                                <div class="vc_column-inner "
                                     style="padding-left:0px !important;padding-right:0px !important;">
                                    <div class="wpb_wrapper">
                                        <h3 class="rp_heading">All Users Selected</h3>
                                        <?php

                                        //get all groups users
                                        //limited the Group to 1 only
                                        echo '<select style="    width: 100%;    box-shadow: 0px 1px 1px silver;
    border-radius: 4px;
    font-size: 14px;
    border: none;
    background: transparent;
    font-size: 14px;
    height: 300px;" id="group_enrolled_users" name="group_enrolled_users" multiple>';

                                        //limit user groups to 1
                                        for ($i = 0; $i <= $total; $i++) {
                                            echo '<option style="height:20px;" value="' . $user_groups_users_ids[$i]->data->ID . '">' . $user_groups_users_ids[$i]->data->user_email . '</option>';
                                        }
                                        echo '</select>';

                                        ?>
                                    </div>
                                </div>
                            </div>

                            <input class="text-input" name="group_course_ids" type="hidden" id="group_course_ids"
                                   value=""/>
                            <input class="text-input" name="group_user_ids" type="hidden" id="group_user_ids"
                                   value=""/>
                            <div style="width:100%;margin:0 auto; ">
                                <button name="assign_group_courses_to_group_users" type="submit"
                                        id="assign_group_courses_to_group_users" class="submit qbutton small rp_btn">
                                    ASSIGN COURSES<i class="fa fa-angle-double-right fa-lg"></i>
                                </button>
                            </div>
                        </form>
                    <?php
                        }else{
                        echo 'You have no users in the group.Please add some users.' . ' <a href="'.site_url().'/enterprise_dashboard/settings/manage-users/add-new-user" title="Add Users">Add User(s) to Group</a>';
                        }
                    } else {
                        echo 'You have not created any group yet.' . '<a href="#" title="Coming Soon">Create New Group</a>';
                    }

                    if (isset($_POST['assign_group_courses_to_group_users'])) {
                        //action hook to add new user
                        do_action('rp_assign_group_courses_to_group_users');
                    }
                    ?>
                    <script>
                        jQuery('#group_courses').change(function () {
                            jQuery('#group_course_ids').val('');//empty it first
                            var course_ids = [];//array
                            jQuery('#group_courses :selected').each(function (i, selected) {
                                jQuery('#group_course_ids').val(jQuery('#group_course_ids').val() + ',' + jQuery(selected).attr('value'));
                                console.log("gc:" + jQuery(selected).attr('value'));
                            });
                        });

                        //selecting all users by default as we are assigning all users for the group course at once at the moment
                        jQuery('#group_enrolled_users option').prop('selected', true);
                        jQuery('#group_enrolled_users option').attr("disabled", true);
                        jQuery('#group_user_ids').val('');//empty it first
                        var course_ids = [];//array
                        // :selected instead of selected we are using each
                        jQuery('#group_enrolled_users :selected').each(function (i, selected) {
                            jQuery('#group_user_ids').val(jQuery('#group_user_ids').val() + ',' + jQuery(selected).attr('value'));
                            console.log("ui:" + jQuery(selected).attr('value'));
                        });

                    </script>
                    <!--___________________________SEPERATOR____________________________-->
                    <div class="separator normal" style="background-color: #eaeaea;"></div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


