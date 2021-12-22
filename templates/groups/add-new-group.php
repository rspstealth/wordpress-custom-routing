<?php
/**
 * @Name        Add New Groups
 * @Author      Ali Usman
 * @Description Template part to display Add New Group under {Groups}.
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
                $user_id = $current_user->ID;
//                $all_meta_for_user = get_user_meta( $user_id );
//                print_r( $all_meta_for_user );
                //$subscriptions = wcs_get_users_subscriptions( $user_id );
                $subscriptions = wcs_get_users_subscriptions();
                $subscriptions = apply_filters('wcs_pre_get_users_subscriptions', array(), $user_id);
                if (empty($subscriptions) && 0 !== $user_id && !empty($user_id)) {
                    $post_ids = get_posts(array(
                        'posts_per_page' => -1,
                        'post_status' => 'any',
                        'post_type' => 'shop_subscription',
                        'orderby' => 'date',
                        'order' => 'desc',
                        'meta_key' => '_customer_user',
                        'meta_value' => $user_id,
                        'meta_compare' => '=',
                        'fields' => 'ids',
                    ));

                    //setting it to first subscription only
                    foreach ($post_ids as $post_id) {
                        $subscriptions[$post_id] = wcs_get_subscription($post_id);
                        $order = wc_get_order($post_id);
                        $items = $order->get_items();
                        $is_sub_active = $order->get_status();
                        $order->order_date;
                        //echo 'total:'.$order->subtotal;
                        foreach ($items as $item) {
                            $item_id = $item['product_id'];
                            $_product = wc_get_product($item_id);
                            $_product_id = $item_id;
                            $_product_price = $_product->get_price();

                            if ($order->get_status() === 'active') {
                                // if group leader
                                if (learndash_is_group_leader_user($current_user->ID)) {
                                    //get groups ids of group manager
                                    $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
                                    //if user has no group created
                                    if(empty($user_groups_ids[0])){
                                        if(isset($_POST['add_group'])){
                                            echo 'creating group.';
                                            //action hook for user update
                                            do_action('rp_add_new_group',$current_user->ID);
                                        }else{
                                        ?>
                                        <form method="post" id="add_new_group_settings_form" action="<?php get_permalink(); ?>">
                                            <p class="form-username">
                                                <label for="group-name">Group Name</label>
                                                <input class="text-input" name="group-name" type="text" id="group-name"
                                                       value=""/>
                                            </p><!-- .form-username -->
                                            <?php
                                            $editor_id = 'new_group_content';
                                            $settings = array( 'media_buttons' => true , 'drag_drop_upload' => true);
                                            wp_editor( $content, $editor_id, $settings );
                                            ?>
                                            <p class="form-submit">
                                                <?php echo $referer; ?>
                                                <input name="add_group" type="submit" id="add_group" class="submit qbutton"
                                                       value="<?php _e('Create Group', 'profile'); ?>"/>
                                            </p><!-- .form-submit -->
                                        </form><!-- #adduser -->
                                        <?php
                                        }
                                    }else {
                                        echo 'You cannot create more than one group.';
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
