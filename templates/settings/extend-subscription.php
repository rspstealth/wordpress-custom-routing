<?php
/**
 * @Name        Extend Subscription
 * @Author      Ali Usman
 * @Description Template part to display payments under {Settings}.
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
                    <li><a href="enterprise_dashboard/settings/users-management" title="">Manage Users</a></li>
                    <li class="active"><a href="enterprise_dashboard/settings/payments" title="">Payments</a></li>
                    <li><a href="#" title="">+ Add More</a></li>
                </ul>
                <div class="separator  normal" style="background-color: #eaeaea;"></div>
                <h2>Current Plan</h2>
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
                        <th class="product-slots">Total Slots</th>
                        <th class="product-slots-remaining">Slots Remaining</th>
                        <th class="product-status">Status</th>
                        <th class="product-updated">Last Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($items as $item) {
                        $item_id = $item['product_id'];
                        if ($order->get_status() !== 'completed') {
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

                            $slots_meta = get_user_meta($user_id, 'rp_' . $subscription_title . '_slots', true);
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
                                <td class="product-slots"><?php echo $slots_meta; ?></td>
                                <td class="product-slots-remaining"><?php echo(get_user_meta($user_id, 'rp_monthly_subscription_slots', true) - count($user_groups_users_ids)); ?></td>
                                <td class="product-status"
                                    style="color:<?php echo $td_color; ?>"><?php echo $order->get_status(); ?></td>
                                <td class="product-updated"><?php echo $order->order_date; ?></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    }
                    }
                    ?>
                    </tbody>
                </table>
                <div class="separator  normal" style="background-color: #eaeaea;"></div>
                <?php
                $current_user = wp_get_current_user();
                if (isset($_POST['extend-group-subscription'])) {
                    //action hook for group subscription extension
                    do_action('rp_extend_group_subscription', $current_user->ID);
                }

                //display subscription data
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                //$subscriptions = wcs_get_users_subscriptions( $user_id );
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
                        <th class="product-slots">Total Slots</th>
                        <th class="product-slots-remaining">Remaining Slots</th>
                        <td class="product-slots">Add More Slots</td>
                        <td class="product-slots">Subtotal</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($items as $item) {
                        $item_id = $item['product_id'];
                        if ($order->get_status() !== 'completed') {
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
                            $slots_meta = get_user_meta($user_id, 'rp_' . $subscription_title . '_slots', true);

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
                                <td class="product-slots"><?php echo $slots_meta; ?></td>
                                <td class="product-slots-remaining"><?php echo(get_user_meta($user_id, 'rp_monthly_subscription_slots', true) - count($user_groups_users_ids)); ?></td>
                                <td class="product-slots">
                                    <p class="form-cart-slots">
                                        <a href="#" id="rp-cart-minus" onclick="decreaseSlotsFromCartInput()" title="Decrease">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </a>
                                        <input class="text-input" name="cart-slots" type="text" id="rp-cart-slots"
                                               value="20"/>
                                        <a href="#" id="rp-cart-add" onclick="increaseSlotsFromCartInput()" title="Decrease">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a>
                                    </p>
                                </td>
                                <td id="cart-sub-total" class="product-slots">cal()</td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    }
                    }

                    ?>
                    </tbody>
                </table>
                    <p class="form-submit">
                        <?php echo $referer; ?>
                        <input name="extend-group-subscription" type="submit" id="extend-group-subscription" class="submit qbutton small rp_btn"
                               value="<?php _e('Order', 'profile'); ?>"/>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

