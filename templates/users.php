<?php
/**
 * @Name        Users
 * @Author      Ali Usman
 * @Description Template part to display the users data.
 * @Since       May 1, 2017
 */
?>
<?php
/**
 * @var parent_items
 * @var child_items
 *
 */
//get Dashboard Menu
$dashboard_menu_items = wp_get_nav_menu_items('Dashboard Pages');
//var_dump($endpoints);
$parent_items = '<ul>';
$child_items = '<ul>';
$active_parent = 'none';

foreach ($dashboard_menu_items as $menu_item) {
    //get parents array
    if ($menu_item->menu_item_parent == 0) {
        //<ul>
        //<i class="fa fa-tachometer" aria-hidden="true"></i>
        $url_parts = explode('/', $menu_item->url);
        //echo 'part 2:'.$url_parts[4];
        $class_active = '';
        if ($endpoints[2] === $url_parts[4]) {
            //mark active
            $class_active = 'class="active"';
            $active_parent = $menu_item->ID;
        }
        $parent_items .= '<li ' . $class_active . '>';
        $parent_items .= '<a href="' . $menu_item->url . '" title="' . $menu_item->post_title . '">';
        if (!empty($menu_item->icon)) {
            $parent_items .= '<i class="fa ' . $menu_item->icon . '" aria-hidden="true"></i>';
        }
        $parent_items .= $menu_item->post_title . '</a></li>';
    }
    //var_dump($child_items);
}

//now find active parent child items
foreach ($dashboard_menu_items as $menu_item) {
    //get child array
    if ($menu_item->menu_item_parent != 0) {
        if ($menu_item->menu_item_parent == $active_parent) {
            $child_items .= '<li ' . $class_active . '>';
            $child_items .= '<a href="' . $menu_item->url . '" title="' . $menu_item->post_title . '">';
            if (!empty($menu_item->icon)) {
                $child_items .= '<i class="fa ' . $menu_item->icon . '" aria-hidden="true"></i>';
            }
            $child_items .= $menu_item->post_title . '</a></li>';
        }
    }
}
$parent_items .= '</ul>';
$child_items .= '</ul>';
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
                if(!empty($ep_names[0])){
                    $user_id = $ep_names[1];
                    $paged = $ep_names[2];
                    // echo 'paged:'.$ep_names[2];
                    if(is_numeric($paged)){
                        // echo 'load single.';
                        //load template content
                        include('single-users.php');
                    }else{
                        // echo 'load users content.';
                    }
                }
                ?>
<?php
// GET ALL USERS DATA HERE

$current_user_id = get_current_user_id();

// Get group ids that the user is an administrator of
$group_ids = learndash_get_administrators_group_ids( $current_user_id );
$group_id = $group_ids[0];

// Get list of enrolled courses for a group (or an admin)
$enrolled_courses = learndash_group_enrolled_courses( $group_id );

// Returns an array of user_id to report on. - PREFFERED METHOD!
$report_user_ids = learndash_get_report_user_ids( $current_user_id );



$users_activity = rp_rp_getUserActivityTotals($report_user_ids, $enrolled_courses);


// Get user activity totals for "Lessons Completed", "Courses Started" and "Courses Completed"
// $user_activity_totals = $this->getUserActivityTotals($user_id, $courrr);


// Overall learners table data
$user_table_data = array();
foreach ($users_activity as $user_id => $user_totals) {
  $user_data = get_user_by( 'id', $user_id );

  $user_totals['id'] = $user_id;
  $user_totals['name'] = rp_rp_get_user_row_name_or_email($user_data);

  $user_last_login = get_user_meta( $user_id, 'learndash-last-login', true );
  if ($user_last_login != '') {
    $user_totals['last_login'] = date("d F Y", $user_last_login);
  } else {
    $user_totals['last_login'] = 'none';
  }

  // $percent = $user_totals['running_steps_total'] / $corses_total_steps_count;
  // $percent_friendly = number_format( $percent * 100, 2 ) . '%';
  // $user_row['total_progress'] = $percent_friendly;

  $user_table_data[$user_id] = $user_totals;
}

// echo "<pre>";var_dump($user_table_data);echo "</pre>";exit();

?>

    <h2>All User Progress</h2>

    <div class="vc_row wpb_row section vc_row-fluid shadow" style="text-align:left;margin:0;box-shadow: 0px 5px 10px 0px rgba(0,0,0,0.15);background-color: #FFFFFF;">
    	<div class=" full_section_inner clearfix">
    		<div class="wpb_column vc_column_container vc_col-sm-12">
    			<div class="vc_column-inner ">
    				<div class="wpb_wrapper">
    					<div class="vc_row wpb_row section vc_inner vc_row-fluid grid_section" style=" text-align:left;">
    						<div class=" ">
    							<div class="section_inner_margin clearfix">
    								<div class="wpb_column vc_column_container vc_col-sm-12" style="padding: 25px;">
    									<div class="vc_column-inner ">
    										<div class="wpb_wrapper">
    											<div class="wpb_text_column wpb_content_element ">
    												<div class="wpb_wrapper">

                              <!-- <p style="font-size: 24px;text-align: center;">User statistics</p> -->
                              <div class="data-box table-responsive">
                                  <table id="users_datatable" class="display table" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Last login date <i class="fa fa-sort" aria-hidden="true"></i></th>
                                            <th>Courses in progress <i class="fa fa-sort" aria-hidden="true"></i></th>
                                            <th>Courses completed <i class="fa fa-sort" aria-hidden="true"></i></th>
                                            <th>Lessons completed <i class="fa fa-sort" aria-hidden="true"></i></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?php
                                      foreach($user_table_data as $user_id => $user_data){
                                          ?>
                                          <tr>
                                              <td><a href="<?php echo get_site_url(); ?>/enterprise_dashboard/users/<?php echo $user_data['id']; ?>"><?php echo $user_data['name']; ?></a></td>
                                              <td><?php echo $user_data['last_login']; ?></td>
                                              <td><?php echo $user_data['in_progress_courses_total']; ?></td>
                                              <td><?php echo $user_data['completed_courses_total']; ?></td>
                                              <td><?php echo $user_data['completed_steps_total']; ?></td>
                                          </tr>
                                      <?php
                                      }
                                      ?>
                                      </tbody>
                                  </table>
                              </div>

    												</div>
    											</div>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>



                <!-- IVAN Course Data -->
                <!-- IVAN Course Data -->
                <!-- IVAN Course Data -->
                <!-- IVAN Course Data -->
                <!-- IVAN Course Data -->
</div>
</div>
</div>
</div>

<script>
jQuery(function() {
    jQuery('#users_datatable').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = jQuery('<select><option value=""></option></select>')
                    .appendTo( jQuery(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = jQuery.fn.dataTable.util.escapeRegex(
                            jQuery(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });
});
</script>

<?php

function rp_rp_getUserActivityTotals($user_ids, $course_ids) {

  $courses_reports = array();
  $running_steps_total = array();
  $completed_steps_total = array();
  $completed_courses_total = array();
  $in_progress_courses_total = array();
  $not_started_courses_total = array();

  // Filters to get the user activity using Learndash function
  $query_args = array(
    "post_types" => array(
      'sfwd-courses'
    ),
    'post_ids' => $course_ids,
    "user_ids" => $user_ids,
    "activity_status" => array(
      "COMPLETED",
      // "IN_PROGRESS"
    ),
    "per_page" => 0,
    "include_meta" => true,
  );

  // Use Learndash function to get user activity with the above filters.
  $user_activity_records = learndash_reports_get_activity($query_args, $current_user_id);
  $user_activity_objects = $user_activity_records['results'];

  // fill users array with IDs
  $users = array_fill_keys($user_ids, array());

  // fill with placeholders
  foreach ($users as $key => $user) {
    $users[$key]['courses'] = array();
    $users[$key]['running_steps_total'] = 0;
    $users[$key]['completed_steps_total'] = 0;
    $users[$key]['completed_courses_total'] = 0;
    $users[$key]['in_progress_courses_total'] = 0;
    $users[$key]['not_started_courses_total'] = 0;
  }

  // Process all returned activity and record completion stats for each course. Also record totals for each user.
  foreach ($user_activity_objects as $result) {
    $activity_meta = $result->activity_meta;
    $users[$result->user_id]['courses'][$result->post_id]['steps_total'] = $activity_meta['steps_total'];
    $users[$result->user_id]['courses'][$result->post_id]['steps_completed'] = $activity_meta['steps_completed'];
    $users[$result->user_id]['courses'][$result->post_id]['steps_last_id'] = '';

    // support older activity ID.
    if (!empty($activity_meta['last_id'])) {
      $users[$result->user_id]['courses'][$result->post_id]['steps_last_id'] = $activity_meta['last_id'];
    }

    // replace with a new one if both are present
    if (!empty($activity_meta['steps_last_id'])) {
      $users[$result->user_id]['courses'][$result->post_id]['steps_last_id'] = $activity_meta['steps_last_id'];
    }

    // add to running total
    $users[$result->user_id]['running_steps_total'] = $users[$result->user_id]['running_steps_total'][$result->user_id] + $activity_meta['steps_total'];

    // add to completed total
    $users[$result->user_id]['completed_steps_total'] = $users[$result->user_id]['completed_steps_total'] + $activity_meta['steps_completed'];

    // record a course status and running totals
    if ($activity_meta['steps_completed'] >= $activity_meta['steps_total']) {
      $users[$result->user_id]['courses'][$result->post_id]['status'] = 'completed';
      $users[$result->user_id]['completed_courses_total'] = $users[$result->user_id]['completed_courses_total'] + 1;
    } elseif ($activity_meta['steps_completed'] < $activity_meta['steps_total'] && $activity_meta['steps_completed'] > 0) {
      $users[$result->user_id]['courses'][$result->post_id]['status'] = 'in_progress';
      $users[$result->user_id]['in_progress_courses_total'] = $users[$result->user_id]['in_progress_courses_total'] + 1;
    } else {
      $users[$result->user_id]['courses'][$result->post_id]['status'] = 'not_started';
      $users[$result->user_id]['not_started_courses_total'] = $users[$result->user_id]['not_started_courses_total'] + 1;
    }

  }

  // $users[$result->user_id] = $result_total;
  // echo "<pre>";var_dump($users);echo "</pre>";exit();

  // return an array of calculated values.
  return $users;
}

function rp_rp_get_user_row_name_or_email($user_data) {
  $user_display_name = $user_data->first_name . ' ' . $user_data->last_name;

  if($user_data->first_name != '') {
    $user_row_name_or_email = $user_display_name;
  } else {
    // var_dump($user_data);exit();
    $user_row_name_or_email = $user_data->user_email;
  }


  return $user_row_name_or_email;
}

?>
