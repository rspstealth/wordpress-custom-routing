<?php
if (!defined('ABSPATH')) {
    exit;
}
//dashboard left menu should be a separate module in the future
//register stylesheets
add_action ( 'wp_head' , 'enqueue_css',1);
function enqueue_css() {
    //load js composer css
    wp_enqueue_style( 'bridge-composer' , plugin_dir_url().'js_composer/assets/css/js_composer.min.css' );

    //load plugin css
    wp_enqueue_style( 'routepress-css' , plugin_dir_url().'routepress/frontend/css/style.css' );

    // Datatable style
    wp_enqueue_style( 'routepress-datatable-css' , plugin_dir_url().'routepress/frontend/css/datatable.min.css' );
}

//register scripts
add_action ( 'wp_footer' , 'enqueue_js',10);
function enqueue_js() {
    //load plugin css
    wp_enqueue_script( 'routepress-js' , plugin_dir_url().'routepress/frontend/js/javascript.js' );

    wp_enqueue_script( 'routePress-datatable-js' , plugin_dir_url().'routepress/frontend/js/jquery.datatable.min.js' );

}

/* loading main dashboard page template */
function load_dashboard_page_template()
{
    $url = $_SERVER['REQUEST_URI'];
    $endpoints = explode('/',$url);
    if( $endpoints[1] === 'enterprise_dashboard'){
        require_once(get_stylesheet_directory().'/templates/dashboard.php');
        exit;
    }
}

/* theme specific actions & hooks */
add_action('rp_update_user', 'rp_update_user_func',10,1);
function rp_update_user_func($user_id) {
    if ( current_user_can('edit_user',$user_id) ){
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first-name']));
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last-name']));
        update_user_meta($user_id, 'email', sanitize_text_field($_POST['email']));
        update_user_meta($user_id, 'password', sanitize_text_field($_POST['password']));
        if ( !empty($_POST['password'] )) {
                wp_update_user( array( 'ID' => $user_id, 'user_pass' => esc_attr( $_POST['password'] ) ) );
        }
        echo 'User updated.';
    }else{
        echo 'You dont have permissions to edit this user.';
    }
}

/* Update Group Settings */
add_action('rp_update_group_settings', 'rp_update_group_settings_func',10,1);
function rp_update_group_settings_func($group_id) {
    $group_name = $_POST['group-name'];
    $description = $_POST['editpost'];

    //update group wp post
    $group_post = array(
        'ID'           => $group_id,
        'post_title'     => sanitize_text_field($group_name),
        'post_content'       => $description,
    );

    // Update the post into the database
    if(wp_update_post( $group_post )){
        echo 'Group updated.';
    }else{
        echo 'Sorry, there was some error.Please try later.';
    }
}

add_action('update_group_manager_settings', 'rp_update_manager_func',10,1);
function rp_update_manager_func($user_id) {
    if ( current_user_can('edit_user',$user_id) ){
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first-name']));
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last-name']));
        update_user_meta($user_id, 'email', sanitize_text_field($_POST['email']));
        update_user_meta($user_id, 'password', sanitize_text_field($_POST['password']));
        wp_update_user( array( 'ID' => $user_id, 'user_url' => sanitize_text_field($_POST['url']) ) );
        update_user_meta($user_id, 'description', sanitize_text_field($_POST['description']));

        if ( !empty($_POST['password'] )) {
            wp_update_user( array( 'ID' => $user_id, 'user_pass' => esc_attr( $_POST['password'] ) ) );
        }
        echo 'Profile updated.';
    }else{
        echo 'You dont have permissons to edit the profile.';
    }
}

add_action('rp_send_email_to_user','rp_send_email_to_user_func');
function rp_send_email_to_user_func(){
    //$site_info = get_bloginfo();
    //$attachments = "";
    $subject = "Group Name Contact";
    $name =  $_POST['first-name'].' '.$_POST["last-name"];
    $email = $_POST["email"];
    $message = "Hi, User...Your Group admin has updated your Cudoo login details:";
    $headers = "From: ".$name."<littlewebdeveloper@gmail.com>"."\n";
    $headers .= "Return-Path: littlewebdeveloper@gmail.com";
    $headers .= "MIME-Version: 1.0";
    $headers .= "Content-Type: text/html; charset=UTF-8";
    $headers .= "BCC: user@email.com";
    wp_mail( $email, $subject, $message, $headers, $attachments );
}

add_action('rp_extend_group_subscription','rp_extend_group_subscription_func',10,3);
function rp_extend_group_subscription_func($product_id,$updated_slots,$existing_sub_id){
    global $woocommerce,$wp;
    //cancel subscription if slots are 0
    if($updated_slots==0){
        $sub_post = array(
            'ID'           => $existing_sub_id,
            'post_status' => 'wc-active',
        );

        // Update the post into the database
        if(wp_update_post( $sub_post )){
            echo 'Subscription Cancelled.';
            return;
        }
    }



    $product_object = get_product($product_id);
    // if price is provided by the partner, then override the existing ...
    // product price with the price provided by partner
    if (!empty($price)) {
        $product_price = $price;
    } else {
        $product_price = $product_object->get_price();
    }
    $product_object->set_price($updated_slots * $product_price);

    $user_id = get_current_user_id();
    // Get current user info
    $user_info = get_userdata($user_id);

    $address = array(
        'first_name' => $user_info->first_name,
        'last_name'  => $user_info->last_name,
        //'company'    => 'ETON',
        'email'      => $user_info->from_email,
       // 'phone'      => '123-123-123',
        //'address_1'  => 'address 1',
       // 'address_2'  => '2',
        'city'       => $user_info->city,
       // 'state'      => 'DU ',
       // 'postcode'   => '123333',
        'country'    => $user_info->country
    );
    // Now we create the order
    $order = wc_create_order();

    // The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
    $order->add_product( $product_object, 1); // Use the product IDs to add

    // Set addresses
    $order->set_address( $address, 'billing' );
    $order->set_address( $address, 'shipping' );
    // Set payment gateway
    $payment_gateways = WC()->payment_gateways->payment_gateways();
    $order->set_payment_method( $payment_gateways['bacs'] );

    // Calculate totals
    $order->calculate_totals();
    $order->update_status( 'pending', 'Order created dynamically', TRUE);

    // add_post_meta($order->id, '_order_total', $product_price, true);
    add_post_meta($order->id, '_order_total', 0, true);
    add_post_meta($order->id, '_customer_user', $user_id, true);
    add_post_meta($order->id, '_completed_date', date('Y-m-d H:i:s e'), true);
    add_post_meta($order->id, '_paid_date', date('Y-m-d H:i:s e'), true);

    // billing info
    add_post_meta($order->id, '_billing_city', $user_info->city, true);
    add_post_meta($order->id, '_billing_country', $user_info->country, true);
    add_post_meta($order->id, '_billing_email', $user_info->from_email, true);
    add_post_meta($order->id, '_billing_first_name', $user_info->first_name, true);
    add_post_meta($order->id, '_billing_last_name', $user_info->last_name, true);


    //set subscription status to pending
    $sub_post = array(
        'ID'           => $existing_sub_id,
        'post_status' => 'wc-pending',
    );

    // Update the post into the database
    if(wp_update_post( $sub_post )){
        echo 'Subscription Updated.';
    }

if(update_post_meta( $existing_sub_id, '_order_total', ($updated_slots * $product_price) ) ){
    //get product title
    $subscription_title = strtolower(str_replace(" ", "_", get_the_title($product_id)));
    // update/create the slots meta
    update_post_meta($existing_sub_id , 'rp_' . $subscription_title . '_slots', $updated_slots);
}

//add item to cart
$woocommerce->cart->add_to_cart($product_id);
$woocommerce->cart->total = $updated_slots * $product_price;
//add action to - update cart
do_action('rp_set_sub_price',($updated_slots * $product_price));
//redirect to
?>
    <script>
        window.location.href = '<?php echo site_url().'/cart/';?>';
    </script>
<?php
}


add_action('rp_set_sub_price','rp_set_sub_price_func',10);
function rp_set_sub_price_func($price){
    //if sess was not created, create it
    if(!isset($_SESSION['rp_sub_price'])) {
        session_start();
        $_SESSION['rp_sub_price'] = $price;
        echo 'price was not set.'.$_SESSION['rp_sub_price'];
    }else{
        echo 'price set.';
        //update it
        $_SESSION['rp_sub_price'] = $price;
    }
}

add_action('template_redirect','rp_get_referer',10);
function rp_get_referer(){
    if(wp_get_referer() == site_url().'/enterprise_dashboard/settings/payments/extend'){
        //load price
        add_action('wp_head', 'rp_update_cart_func',10,1);
    }
}

function rp_update_cart_func($price ){
    global $woocommerce,$RP_Install;
    $woocommerce->cart->add_to_cart($product_id);
        if(isset($_SESSION['rp_sub_price'])) {
            $price = $_SESSION['rp_sub_price'];
        } else {
           echo 'no price found.';
        }

    if($price){
        echo 'Price:'.$price;
    }
    if( is_page('cart') ){
        echo 'rp_cart price.'.$price;
        echo '<pre>';
        var_dump($woocommerce->cart);
        echo '</pre>';

        //$woocommerce->cart->cart_contents_total = $price;
        foreach ( $woocommerce->cart->cart_contents as $key => $value ) {
           // echo 'key:'.$key.' value:'.$value['line_subtotal'];
           // $value['line_subtotal'] = $price;
          //  echo 'GET PRICE:';
            $value['data']->price = $price;
            $value['data']->subscription_price = $price;
            $woocommerce->cart->cart_contents_total = $price;
            $woocommerce->cart->cart_session_data->cart_contents_total['total'] = $price;
            //$woocommerce->cart->cart_session_data->cart_contents_total['subtotal'] = $price;
            //$woocommerce->cart->cart_contents->$value['line_total']
            echo 'line total:';

            $woocommerce->cart->cart_session_data['cart_contents_total'] = $price;
            $woocommerce->cart->cart_session_data['subtotal'] = $price;
            $woocommerce->cart->cart_session_data['total'] = $price;
            var_dump($woocommerce->cart->cart_session_data);
        }
    }
}

/*add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );
function add_custom_price( $cart_object ) {
    var_dump($cart_object);
    $custom_price = 160;
    foreach ( $cart_object->cart_contents as $key => $value ) {
        $value['data']->price = $custom_price;
    }
}*/

//add slots meta if subscription is set to active from WP backend
add_action('add_slots_to_user_group_on_active_subscription','add_slots_to_user_group_on_active_subscription_func', 10,1);
function add_slots_to_user_group_on_active_subscription_func($subscription){
    echo 'sub set to active:';
    var_dump($subscription);
}


/* Assing group Courses to Group Users */
add_action('rp_assign_group_courses_to_group_users','rp_assign_group_courses_to_group_users_func');
function rp_assign_group_courses_to_group_users_func(){
    $group_course_ids = explode( ',' , sanitize_text_field($_POST['group_course_ids'] ) );
    $group_user_ids   = explode( ',' , sanitize_text_field($_POST['group_user_ids']   ) );
//    echo 'gc:';
//    var_dump($group_course_ids);
//    echo 'gu:';
//    var_dump($group_user_ids);

    $timestamp = date( "Y-m-d H:i:s", mktime(0, 0, 0) );
    foreach($group_course_ids as $course_id){
        foreach($group_user_ids as $user_id){
            if(!empty($user_id)){
                if( get_user_meta( $user_id , 'course_'.$course_id.'_access_from',true ) ){
                    echo $user_id.'user has access to:'.$course_id.'<br>';
                }else{
                    echo '<br>'.$user_id.'user has NO access to:'.$course_id.'<br>';
                    ld_update_course_access($user_id, $course_id, $remove = false);
                }
            }

        }
    }

}

/* Insert new user to website group */
add_action('rp_insert_new_user','insert_new_user_func');
function insert_new_user_func(){
    $manager_group_id = sanitize_text_field($_POST['manager_group_id']);
    echo 'manager group id:'.$manager_group_id;
    $user_data = array(
        'ID' => '',
        'user_pass' => sanitize_text_field($_POST['password']),//wp_generate_password(),
        'user_login' => sanitize_text_field($_POST['user-name']),
        'display_name' => sanitize_text_field($_POST['first-name']).' '.sanitize_text_field($_POST['last-name']),
        'first_name' => sanitize_text_field($_POST['first-name']),
        'last_name' => sanitize_text_field($_POST['last-name']),
        'role' => 'subscriber', //get_option('default_role'),
        'user_email' => sanitize_text_field($_POST['email']),
    );
    $user_id = wp_insert_user( $user_data );

    if(!empty($user_id)){
        echo 'New user has been registered.';
        //add user to the group by default
        if(add_user_meta( $user_id , 'learndash_group_users_'.$manager_group_id , $manager_group_id )){
            echo 'User added to the group.';
        }
       // var_dump($user_id);
    }else{
        echo 'Sorry, New user was not added , please try again.';
    }
}

/* Get all learndash courses */
add_action('get_all_learndash_courses','get_all_courses');
function get_all_courses(){
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $postsPerPage = 12;

        $query = array(
            'paged' => $paged,
            'posts_per_page'    => $postsPerPage,
            'post_type'         => 'sfwd-courses',
            'post_status'       => 'publish',
        );
        $loop = new WP_Query( $query );
        while ( $loop->have_posts() ) {
            $id = get_the_ID();
            $loop->the_post();
            echo '<a href="'.site_url().'/enterprise_dashboard/courses/'.$id.'" title="'.get_the_title().'">'.get_the_title().'</a><br>';
        }
}

/* Get user enrolled courses */
add_action('rp_get_user_enrolled_courses','rp_get_user_enrolled_courses_func',10,2);
function rp_get_user_enrolled_courses_func($user_id,$paged){
    global $enrolled_courses,$rp_max_pages,$total_courses;
    if(empty($user_id)){
        return false;
    }
    $user_enrolled_courses = learndash_user_get_enrolled_courses($user_id);
    $total_courses = count($user_enrolled_courses);
    if(!isset($paged)){
        $paged = 1;
    }

    $postsPerPage = 12;
    $query = new WP_Query( array(
        'paged'             => $paged,
        'posts_per_page'    => $postsPerPage,
        'post_type'         => 'sfwd-courses',
        'post_status'       => 'publish',
        'post__in'          =>  $user_enrolled_courses,
        'orderby'       	=> 'title',
        'order'   			=> 'ASC',
    ) );
    $user_enrolled_courses = $query->posts;
    if($total_courses > $postsPerPage){
        $rp_max_pages = ceil($total_courses / $postsPerPage);
    }

    if (sizeof($user_enrolled_courses) > 0) {
        foreach($user_enrolled_courses as $course) {
            $enrolled_courses[$course->ID]['title'] =  $course->post_title;
            $enrolled_courses[$course->ID]['link'] =  $course->guid;
        }
    }
    wp_reset_query();
    return $enrolled_courses;
}

add_action('rp_add_new_group','rp_add_new_group_func');
function rp_add_new_group_func(){
    $current_user = wp_get_current_user();
    $manager_user_id = $current_user->ID;
    $group_name = wp_strip_all_tags(sanitize_text_field($_POST['group-name']));
    echo 'manager user id:'.$manager_user_id;
    $group_data = array(
        'ID' => '',
        'post_title' => $group_name,
        'post_content'  => sanitize_text_field($_POST['new_group_content']),
        'post_type'     => 'groups',
        'post_status'   => 'publish',
        'post_author'   => $manager_user_id,
    );
    // Insert the post into the database
    $group_post_id = wp_insert_post( $group_data );

    //set group leader
    if(add_user_meta( $manager_user_id , 'learndash_group_leaders_'.$group_post_id , $group_post_id , true ) ){
        echo 'Group leader created.';
    }else{
        echo 'Group leader could not be created.';
    }

    $group_name = strtolower(str_replace(' ', '-', $group_name));
    echo 'group name for meta:'.$group_name;
    if(!empty($group_post_id)){
        echo 'New Group has been created.';
        //add user to the group by default, set users limit to 1
        if(update_user_meta( $manager_user_id , "rp_".$group_name."_users_limit" , 1 , true)){
            echo 'Slots added to the group.';
        }
    }else{
        echo 'Sorry, unable to create new group please try again.';
    }
}


add_action('rp_get_user_courses_activity','rp_getUserActivityTotals',10,2);
 function rp_getUserActivityTotals($user_id, $course_ids) {
     global $user_course_activity;
        $courses_reports = array();
        $running_steps_total = 0;
        $completed_steps_total = 0;
        $completed_courses_total = 0;
        $in_progress_courses_total = 0;
        $not_started_courses_total = 0;

        // run a report for all allowed courses and get all results
        $activity_query_args = array(
            "post_ids" => $course_ids,
            'per_page' =>   0,
        );

        $report = learndash_report_user_courses_progress($user_id, array(), $activity_query_args);

        foreach ($report['results'] as $result) {
            $activity_meta = $result->activity_meta;
            $courses_reports['courses'][$result->post_id]['steps_total'] = $activity_meta['steps_total'];
            $courses_reports['courses'][$result->post_id]['steps_completed'] = $activity_meta['steps_completed'];
            $courses_reports['courses'][$result->post_id]['steps_last_id'] = '';

            // support older activity ID.
            if (!empty($activity_meta['last_id'])) {
                $courses_reports['courses'][$result->post_id]['steps_last_id'] = $activity_meta['last_id'];
            }

            // replace with a new one if both are present
            if (!empty($activity_meta['steps_last_id'])) {
                $courses_reports['courses'][$result->post_id]['steps_last_id'] = $activity_meta['steps_last_id'];
            }

            // add to running total
            $running_steps_total = $running_steps_total + $activity_meta['steps_total'];

            // add to completed total
            $completed_steps_total = $completed_steps_total + $activity_meta['steps_completed'];

            // record a course status and running totals
            if ($activity_meta['steps_completed'] >= $activity_meta['steps_total']) {
                $courses_reports['courses'][$result->post_id]['status'] = 'completed';
                $completed_courses_total = $completed_courses_total + 1;
            } elseif ($activity_meta['steps_completed'] < $activity_meta['steps_total'] && $activity_meta['steps_completed'] > 0) {
                $courses_reports['courses'][$result->post_id]['status'] = 'in_progress';
                $in_progress_courses_total = $in_progress_courses_total + 1;
            } else {
                $courses_reports['courses'][$result->post_id]['status'] = 'not_started';
                $not_started_courses_total = $not_started_courses_total + 1;
            }
     }

     $user_course_activity = array(
            'courses' => $courses_reports['courses'],
            'running_steps_total' => $running_steps_total,
            'completed_steps_total' => $completed_steps_total,
            'completed_courses_total' => $completed_courses_total,
            'in_progress_courses_total' => $in_progress_courses_total,
            'not_started_courses_total' => $not_started_courses_total,
        );
        return $user_course_activity;
    }

add_action('rp_generate_activity_graph_data','generate_activity_graph_data',10,3);
function generate_activity_graph_data($days = 14, $user_id, $current_user_id) {
    global $learndash;
    // Set graph colors - this should be moved to plugin settings OR be passed from parent function
    // $activity_inprogress_color = "#FF5622";
    global $line_graph_labels, $in_progress_graph_data, $completed_graph_data;
    $activity_completed_color = "#3ACA60";

    // Figure out the dates labels.
    $mark_limit = $days;
    $empty_data = false;
    for ($mark = 0; $mark <= $mark_limit; $mark++) {
        if ($days == 0) {
            $line_graph_labels .= '"Today",';
        } else {
            $str_days = '-' . $days . ' days';
            $line_graph_labels .= '"' . date('D', strtotime($str_days)) . '",';
        }
        $days--;
    }

    $dates_string = '-' . $mark_limit . ' days';
    $start_time = strtotime($dates_string);
    // $end_time  = date("Y-m-d");
    $end_time  = strtotime("+1 day");

    // Filters to get the user activity using Learndash function
    $query_args = array(
        "post_types" => array(
            'sfwd-lessons'                          // Only lessons for this dashboard
        ),
        "user_ids" => $user_id,   								// Current User ID to look for
        // "user_ids" => array(17089),            // DEBUG: random non-existant user
        "activity_status" => array(
            "COMPLETED",                            // Activity matching IN_PROGRESS
            // "IN_PROGRESS"
        ),
        "time_start" => $start_time,              // doesn't actually work
        "time_end" => $end_time,                  // doesn't actually work
        "per_page" => 0,                          // return ALL results
        "include_meta" => false,                  // definitely don't need this one here, but is useful
        "xyz_activity_time_start" => $start_time  // additional start time. See filter: xyz_dashboard_quety_time_filter()
    );

    // Use Learndash function to get user activity with the above filters.
    $user_activity_records = learndash_reports_get_activity($query_args, $current_user_id);
    $user_activity_objects = $user_activity_records['results'];
    // Array to hold the graph data for completed and in-progress lessons
    $num = $mark_limit + 1;
    $activity_data['in_progress'] = array_fill(0, $num, 0);
    $activity_data['completed'] = array_fill(0, $num, 0);

    $now = time(); // Will use this for time difference calculation

    // now we need to populate graph_date_data object in the form: data: [4,0,0,0,0,0,1]
    // Go through each activity and calculate hw many have been started and completed
    foreach ($user_activity_objects as $record) {
        $activity_date = strtotime($record->activity_started_formatted);
        // Calculate the difference
        $datediff = $now - $activity_date;
        // Calculate our rounded day offset = how many days in the above time difference
        $day_offset = floor($datediff / (60 * 60 * 24));

        // Track this activity as (Started) or In Progress. Even if it has been completed.
        // Using 6 - offset = will give us a location within the days data array
        // $activity_data['in_progress'][$mark_limit - $day_offset]++;

        // If this activity has been completed, then add to completed tracking
        if ($record->activity_status == "1") {
            $activity_data['completed'][$mark_limit - $day_offset]++;
        }
    }

    // If there's no data to be returned, then populate the graph with dummy data.
    if(empty($user_activity_objects)) {
         $empty_data = true;
         $activity_data['in_progress'] = array('1,3,4,1');
         $activity_data['completed'] = array('4,2,4,1');
         $activity_data['in_progress'] = array();
         $activity_data['completed'] = array(0, 1, 2, 3, 2, 1, 1, 3, 1, 2, 4, 5, 4, 5, 6);

         $activity_completed_color = "rgba(75,192,192,1)";  // Alternative color which also works nicely
         $activity_completed_color = "rgba(198,198,198,0.5)";  // Alternative color which also works nicely
         $activity_inprogress_color = "rgba(198,198,198,0.5)";
    }

    // Build javascript object for In Progress graph
    $in_progress_graph_data .= '{
			label: "Lessons Started",
			backgroundColor: gradient,
			borderColor: "white",
			pointBorderColor: "rgba(255,255,255,1)",
			pointBackgroundColor: "rgba(255,255,255,1)",
			pointBorderWidth: 2,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: "#fff",
			pointHoverBorderColor: "rgba(220,220,220,1)",
			data: [' . implode(",", $activity_data['in_progress']) . '],
		}';

    $max_incompleted = max($activity_data['in_progress']);

    // Build javascript object for Completed graph
    $completed_graph_data .= '{
			label: "Lessons Completed",
			backgroundColor: gradient,
			borderColor: "white",
			pointBorderColor: "rgba(255,255,255,1)",
			pointBackgroundColor: "rgba(255,255,255,1)",
			pointBorderWidth: 2,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: "#fff",
			pointHoverBorderColor: "rgba(220,220,220,1)",
			data: [' . implode(",", $activity_data['completed']) . '],
		}';

    $max_completed = max($activity_data['completed']);
    $max_activity = max(array($max_incompleted,$max_completed));

    if($max_activity > 20){
        //create bigger steps
        $step_height = round( ($max_activity / 6) , 2);
        $fix_step_size = 3;
    }else{
        //create small steps
        $step_height = round( ($max_activity / 3) , 2);
        $fix_step_size = 1;
    }

    return array(
        'in_progress_graph_data' => $in_progress_graph_data,
        'completed_graph_data' => $completed_graph_data,
        'line_graph_labels' => $line_graph_labels,
        'step_height' => $step_height,
        'fix_step_size' => $fix_step_size,
        // 'empty_data' => true
        'empty_data' => $empty_data
    );
}

add_action('custom_func_call_to_update_slots','add_group_user_meta_slots_limit',10,1);
function add_group_user_meta_slots_limit($subscription_title){
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        if ( in_array( 'group_leader', (array) $current_user->roles ) ) {
            $has_slots = get_user_meta($user_id, 'rp_' . $subscription_title . '_slots', true); // stores the value of logged in user's meta data for 'test'.
            if ($has_slots){
                $existing_slots = get_user_meta($user_id, 'rp_' . $subscription_title . '_slots', true);
                update_user_meta( $user_id, 'rp_' . $subscription_title . '_slots', ( 0 + $existing_slots ) , true );
            }else{
                add_user_meta( $user_id, 'rp_' . $subscription_title . '_slots', 0 , true );
            }
        }
}

add_action('get_rp_pagination','rp_pagination',10,2);
function rp_pagination($rp_paged,$posts_per_page){

    global $rp_max_pages,$enrolled_courses,$total_courses;
    echo '<nav class="rp_pagination">';
    echo '<table style="font-weight: normal;color: #777;height: 30px;width: 98%;"><tr>
    <td style="width: 50%;text-align: left;"><span>Total enrolled courses:'.$total_courses.'</span></td>
    <td style="width: 50%;text-align: right;"><span>Showing page '.$rp_paged.' of '.$rp_max_pages.'</span></td>
</tr>
</table>';
    echo '<div class="separator normal" style="background-color: #eaeaea;"></div>';
    echo '<ul class="page-numbers">';
    //show previous pages links
    if( ($rp_paged - 2) >= 1){
        echo '<li><a class="page-numbers" href="1"><i class="fa fa-angle-double-left"></i></a></li>';
    }
    if( ($rp_paged - 1) >= 1){
        echo '<li><a class="page-numbers" href="'.($rp_paged-1).'"><i class="fa fa-angle-left"></i></a></li>';
    }

            for($p=1;$p<= $rp_max_pages;$p++){
                if (1 != $rp_max_pages &&( !($p >= $rp_paged+2+1 || $p <= $rp_paged-2-1) || $rp_max_pages <= 2 )) {
                    if ($p == $rp_paged) {
                        echo '<li><b><span class="page-numbers current">' . $p . '</span></b></li>';
                    } else {
                        echo '<li><a class="page-numbers" href="' . $p . '">' . $p . '</a></li>';
                    }
                }
            }


    //show next pages links
    if( ($rp_paged + 1) <= $rp_max_pages){
        echo '<li><a class="page-numbers" href="'.($rp_paged+1).'"><i class="fa fa-angle-right"></i></a></li>';
    }
    if( ($rp_paged + 2) <= $rp_max_pages){
        echo '<li><a class="page-numbers" href="'.$rp_max_pages.'"><i class="fa fa-angle-double-right"></i></a></li>';
    }
            echo '</ul>';
            echo '</nav>';
    }

