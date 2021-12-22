<?php
/**
 * @Name        Payments
 * @Author      Ali Usman
 * @Description Template part to display payments under {Settings}.
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
                <h2>Payment History</h2>
                <?php
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;


                $subscriptions = wcs_get_users_subscriptions($user_id);
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
                if (!empty($post_ids)){

                foreach ($post_ids as $post_id) {
                $subscriptions[$post_id] = wcs_get_subscription($post_id);
                $order = wc_get_order($post_id);

                $items = $order->get_items();
                $order->order_date;
                ?>
                <table class="subscriptions_table cart" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="product-name">Subscription</th>
                        <th class="product-slots">Total Users</th>
                        <th class="product-slots-remaining">Remaining Users</th>
                        <th class="product-status">Status</th>
                        <th class="product-updated">Last Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($items as $item) {
                        $item_id = $item['product_id'];
                        if ($order->get_status() !== 'active') {
                            $td_color = '#dcdcdc';
                        } else {
                            $td_color = 'seagreen';
                        }
                        ?>
                        <tr>
                            <td class="product-name"><?php echo get_the_title($item_id); ?></td>
                            <?php
                            //we need to current user id here, using static for testing
                            $user_groups_ids = learndash_get_administrators_group_ids($user_id);
                            //generate slots limit meta field name from subscription title
                            //converst spaces to underscores
                            $subscription_title = strtolower(str_replace(" ", "_", get_the_title($item_id)));
                            //add or update user slots meta for managers, currently doing manual each refresh
                            do_action('custom_func_call_to_update_slots', $subscription_title);
                            $slots_meta = get_user_meta($user_id, 'rp_' . $subscription_title . '_slots', true);
                                //get all users for this group
                                //echo 'GROUP:'.$group_id.'<br>';
                                $user_groups_users_ids = learndash_get_groups_users($user_groups_ids[0], true);
                                    //get current group user ids and merge distinct to group manager's group
                                    //$all_manager_group_user_ids = array_unique (array_merge ($all_manager_group_user_ids, $user_groups_users_ids));

                                //meta fields for slots limit is: 'rp_monthly_subscription_slots'
                                ?>
                                <td class="product-slots"><?php echo $slots_meta; ?></td>
                                <td class="product-slots-remaining"><?php echo(get_user_meta($user_id, 'rp_' . $subscription_title . '_slots', true) - count($user_groups_users_ids)); ?></td>
                                <td class="product-status"
                                    style="color:<?php echo $td_color; ?>"><?php echo $order->get_status(); ?></td>
                                <td class="product-updated"><?php echo $order->order_date; ?></td>

                        </tr>
                        <?php
                    }
                    }
                    } else {
                        echo 'No Payment history yet.';
                    }
                    } else {
                        echo 'No subscription found.';
                    }

                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

