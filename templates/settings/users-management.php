<?php
/**
 * @Name        Users Management
 * @Author      Ali Usman
 * @Description Template part to display users management under {Settings}.
 * @Since       May 4, 2017
 */
?>
<div class="wpb_column vc_column_container vc_col-sm-3">
    <div class="vc_column-inner " style="padding-left:0px !important;padding-right:0px !important;">
        <div class="wpb_wrapper">
            <div id="dashboard_left">
                <ul>
                    <!-- get menu pages automatically later on -->
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

<div class="wpb_column vc_column_container vc_col-sm-9">
    <div class="vc_column-inner " style="padding-left:0px !important;">
        <div class="wpb_wrapper">
            <div id="dashboard_right">
                <ul>
                    <li><a href="/" title="">Account</a></li>
                    <li class="active"><a href="enterprise_dashboard/settings/users-management" title="">Manage Users</a></li>
                    <li><a href="enterprise_dashboard/settings/payments" title="">Payments</a></li>
                    <li><a href="#" title="">+ Add More</a></li>
                </ul>
                <div class="separator normal" style="background-color: #eaeaea;"></div>

                
<?php 
global $wpdb;
$current_user = wp_get_current_user();

            //get groups ids of group manager  
            $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
            //var_dump($user_groups_ids);

            //$user_groups_course_ids = learndash_get_groups_courses_ids( $current_user->ID, $user_groups_ids );
            //var_dump($user_groups_course_ids);
            ?>

            <div>

            <?php
            if(!empty($user_groups_ids)){
            ?>
                    
            <?php
                //get all groups users
                foreach ($user_groups_ids as $group_id) {
                    //echo 'GROUP:'.$group_id.'<br>';
                    $user_groups_users_ids =  learndash_get_groups_users( $group_id, true );
                    $group = get_post( $group_id , OBJECT, 'raw' );
?>
                    <h3>
                    <?php
                    echo $group->post_title;
                    ?><span style="font-size: 16px;
    color: silver;
    text-transform: none;
    letter-spacing: 0px;
    font-weight: normal;">
                        ~  Users limit: <b>20</b>  <a class="qbutton small rp_btn" href="#" title="Manage users limit for this group">Manage limit</a>
                    </span></h3>
                    
<table class="subscriptions_table cart" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="product-name">Name</th>
                            <th class="product-status">Email</th>
                            <th class="product-ordered-on">Action(s)</th>
                        </tr>
                        </thead>
                        <tbody>
<?php
                    $total = count($user_groups_users_ids)-1;
                    for( $i=0; $i <= $total ; $i++){
                        //var_dump($user_groups_users_ids);
                        // echo $user_groups_users_ids[$i]->data->ID;
                        ?>
                <tr>
                <td class="product-name"><?php echo $user_groups_users_ids[$i]->data->display_name; ?></td>
                <td class="product-status"><?php echo $user_groups_users_ids[$i]->data->user_email; ?></td>
                <td class="product-ordered-on"><?php echo '<a href="'.site_url().'/enterprise_dashboard/users/'.$user_groups_users_ids[$i]->data->ID.'" title="">edit</a> | <a href="'.site_url().'/enterprise_dashboard/users/'.$user_groups_users_ids[$i]->data->ID.'" title="">remove</a>'; ?></td>
                </tr>
                   
<?php
                    }
                  ?>
                </tbody>
                </table>
                <br>
                  <?php
                }
            }else{
                echo 'You have no created any group yet.'.' <a href="#" title="Coming Soon">Create New Group</a>';
            }
            ?>
            </div> 


<!--___________________________SEPERATOR____________________________-->
<div class="separator normal" style="background-color: #eaeaea;"></div>

                <a href="<?php echo site_url().'/enterprise_dashboard/settings/users-management/add-new-user';?>" target="_self" data-hover-color="#ffffff" class="submit qbutton small rp_btn" style="color: rgb(48, 48, 48);">Add New User</a>
            </div>
        </div>
    </div>
</div>


