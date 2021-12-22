<?php
/**
 * @Name        Extend Subscription
 * @Author      Ali Usman
 * @Description Template part to display payments under {Settings}.
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
                <h2>Current Plan</h2>
                <form method="post" id="extend_sub_form" action="<?php get_permalink(); ?>">
                <?php
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
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
                        $_product = wc_get_product( $item_id );
                        $_product_id = $item_id;
                        $_product_price = $_product->get_price();

                        if ($order->get_status() === 'active') {
                            //get subscription id
                            $existing_sub_id = $post_id;
                            //echo 'existing sub id:'.$existing_sub_id;
                            $td_color = 'seagreen';
                ?>
                <table class="subscriptions_table cart" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="product-name">Subscription</th>
                        <th class="product-cost">Price</th>
                        <th class="product-slots">Total Slots</th>
                        <th class="product-slots-remaining">Slots Remaining</th>
                        <th class="product-status">Status</th>
                        <th class="product-updated">Last Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="product-name"><?php echo get_the_title($item_id); ?></td>
                        <td class="product-cost"><?php echo $order->get_formatted_order_total();?></td>
                        <label id="product-cost" style="display:none"><?php echo $_product_price;?></label>
                        <?php
                        //we need to current user id here, using static for testing
                        $user_groups_ids = learndash_get_administrators_group_ids($user_id);
                        //generate slots limit meta field name from Group Title
                            if (learndash_is_group_leader_user($current_user->ID)){
                                //get groups ids of group manager
                                $user_groups_ids = learndash_get_administrators_group_ids($current_user->ID);
                                if (!empty($user_groups_ids)) {
                                    $group = get_post($user_groups_ids[0], OBJECT, 'raw');
                                }
                            }
                        //converst spaces to underscores
                        $group_title = strtolower(str_replace(" ", "_", get_the_title($group->post_title)));
                        //create if meta doesnt exist
                        if(!get_post_meta($existing_sub_id , 'rp_' . $group_title . '_slots') ){
                            echo 'Users limit created.';
                            //setting it to 20 by default
                            update_post_meta($existing_sub_id , 'rp_' . $group_title . '_slots', 0, true );
                        }


                        $slots_meta = get_post_meta($existing_sub_id, 'rp_' . $group_title . '_slots', true);
                        if (!empty($user_groups_ids[0])) {
                            //get all users for this group
                            if ($slots_meta) {
                                //echo 'GROUP:'.$group_id.'<br>';
                                $user_groups_users_ids = learndash_get_groups_users($user_groups_ids[0], true);
                                //get current group user ids and merge distinct to group manager's group
                                //$all_manager_group_user_ids = array_unique (array_merge ($all_manager_group_user_ids, $user_groups_users_ids));
                            }


                            //meta fields for slots limit is: 'rp_monthly_subscription_slots'



                            ?>
                            <td class="product-slots">
                                <label name="slots-total" type="text" id="slots-total"><?php echo $slots_meta; ?></label>
                            </td></td>
                            <td class="product-slots-remaining">
                                <label name="slots-remaining" id="slots-remaining"><?php echo(get_post_meta($existing_sub_id, 'rp_'.$subscription_title.'_slots', true) - count($user_groups_users_ids)); ?></label>
                                </td>
                            <td class="product-status" style="color:<?php echo $td_color; ?>"><?php echo $order->get_status(); ?></td>
                            <td class="product-updated"><?php echo $order->order_date; ?></td>
                            <?php

                        }
                        ?>
                    </tr>
                    <tr>
                        <td class="product-name"><label id="new_total_label" style="width: 100%;">New Total:</label></td>
                        <td class="product-name"><label id="new_total_price" name="new_total_price">-</label></td>
                        <td class="product-name"></td>
                        <td class="product-name"></td>
                        <td class="product-name"></td>
                        <td class="product-name"></td>
                    </tr>
                    </tbody>
                </table>
                    <?php
                        } else {
                            $td_color = '#dcdcdc';
                            echo 'You have no Subscription.';
                        }

                        //stop running more than 1
                        break;
                    }
                    //stop running more than 1
                    break;
                    }
                    }
                    ?>
                <?php
                $current_user = wp_get_current_user();
                if (isset($_POST['extend-group-subscription'])) {
                    if( empty($_POST['product_quantity'] OR $_POST['product_id'] OR $_POST['existing_sub_id']) ){
                        exit;
                    }
                    //action hook for group subscription extension
                    do_action('rp_extend_group_subscription', $_POST['product_id'], $_POST['product_quantity'], $_POST['existing_sub_id']);
                }
                if($is_sub_active === 'active') {
                    ?>
                    <div style="text-align: left;display: block;font-size: 14px;font-weight: bold;    margin: 10px;">
                        <a style="color:orangered" href="#" id="rp-cart-add" onclick="increaseSlotsFromCartInput()"
                           title="Increase">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Add More Users
                        </a>&nbsp;&nbsp;&nbsp;
                        <a style="color:orangered" href="#" id="rp-cart-minus" onclick="decreaseSlotsFromCartInput()"
                           title="Decrease">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i> Remove Users
                        </a>
                    </div>
                    <div class="separator  normal" style="background-color: #eaeaea;"></div>
                    <label class="rp_message" style="display:none;width:100%;height:0;">Setting slots to 0 will force
                        subscription to be cancelled.Do you still want to perform this action?</label>
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input type="hidden" id="existing_sub_id" name="existing_sub_id"
                               value="<?php echo $existing_sub_id; ?>"/>
                        <input type="hidden" id="product_quantity" name="product_quantity" value=""/>
                        <input type="hidden" id="product_id" name="product_id" value="<?php echo $_product_id; ?>"/>
                        <input name="extend-group-subscription" type="submit" id="extend-group-subscription"
                               class="submit qbutton small rp_btn"
                               value="Update Plan"/>
                    </p><!-- .form-submit -->
                    <?php
                }
                    ?>
                </form><!-- #adduser -->
            </div>
        </div>
    </div>
</div>

