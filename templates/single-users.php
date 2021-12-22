<?php
/**
 * @Name        Single Users
 * @Author      Ali Usman
 * @Description Custom page template to handle the requests comings from users dashboard.Purpose is to
 * to display users dashboard data without creating the new WP pages.
 * @Since       May 1, 2017
 */
?>
<?php
/**
 * @var parent_items
 * @var child_items
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



// DATA

$single_user_id = $endpoints[3];
$current_user_id = get_current_user_id();

// Get group ids that the user is an administrator of
$group_ids = learndash_get_administrators_group_ids( $current_user_id );
$group_id = $group_ids[0];

// Get list of enrolled courses for a group (or an admin)
$enrolled_courses = learndash_group_enrolled_courses( $group_id );

// Returns an array of user_id to report on. - PREFFERED METHOD!
$report_user_ids = learndash_get_report_user_ids( $current_user_id );



$single_user_activity = rp_rp_getSingleUserActivityTotals($single_user_id, $enrolled_courses);

$datatable_courses = array();
foreach ($enrolled_courses as $course_id) {

  if (!array_key_exists($course_id, $single_user_activity['courses'])) {
    $datatable_courses[$course_id]['steps_total'] = learndash_get_course_steps_count( $course_id );
    $datatable_courses[$course_id]['steps_completed'] = 0;
    $datatable_courses[$course_id]['steps_last_id'] = '';
    $datatable_courses[$course_id]['course_name'] = esc_html( get_the_title($course_id) );
    $datatable_courses[$course_id]['last_activity'] = 'none';
    $datatable_courses[$course_id]['status'] = 'not_started';

  } else {
    $datatable_courses[$course_id] = $single_user_activity['courses'][$course_id];
    // $total_lessons_count = $single_user_activity['courses'][$course_id]['steps_total'];
    // $completed_lessons_count = $single_user_activity['courses'][$course_id]['steps_completed'];
  }


}

// echo "<pre>";var_dump($datatable_courses);echo "</pre>";exit();

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
                // echo $child_items;
                ?>
                <div class="separator normal" style="background-color: #eaeaea;"></div>
                <p style="font-weight: 500;"><a href="/enterprise_dashboard/users/" class="backup-btn"><i class="fa fa-angle-left" aria-hidden="true"></i> Back to All Users</a></p>

                <h2>User Details</h2>


                <div class="vc_row wpb_row section vc_row-fluid grid_section" style="text-align:left;margin-top:20px; margin-bottom: 20px;padding-top: 10px; padding-bottom: 10px; background-color: #FFFFFF;box-shadow: 0px 5px 10px 0px rgba(0,0,0,0.15) !important;padding-bottom: 10px;">
                    <div class=" section_inner clearfix">
                        <div class="section_inner_margin clearfix">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner ">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_text_column wpb_content_element ">
                                            <div class="wpb_wrapper">
                                              <div class="sub-title2">
                                                  User statistics
                                              </div>
                                              <div class="data-box table-responsive">
                                                  <table id="single_user_datatable" class="display table" cellspacing="0" width="100%">
                                                      <thead>
                                                      <tr>
                                                          <th class="col-name">Course <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                          <th class="col-total-lessons">Total Lessons <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                          <th class="col-completed-lessons">Completed Lessons <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                          <th class="col-last-activity">Last Activity <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                          <th class="col-status">Status <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                      </tr>
                                                      </thead>
                                                      <tfoot>
                                                      <tr>
                                                        <th class="col-name">Course <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                        <th class="col-total-lessons">Total Lessons <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                        <th class="col-completed-lessons">Completed Lessons <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                        <th class="col-last-activity">Last Activity <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                        <th class="col-status">Status <i class="fa fa-sort" aria-hidden="true"></i></th>
                                                      </tr>
                                                      </tfoot>
                                                      <tbody>
                                                      <?php
                                                      foreach($datatable_courses as $course_id => $learner_data){
                                                          $course_name = $learner_data['course_name'];
                                                          if(strlen($course_name)>30){
                                                              $course_name=substr($course_name,0,30).'...';
                                                          }
                                                          ?>
                                                          <tr class="learner-row" data-course_id="<?php echo $course_id; ?>">
                                                              <td><?php echo $course_name; ?></td>
                                                              <td><?php echo $learner_data['steps_total']; ?></td>
                                                              <td><?php echo $learner_data['steps_completed']; ?></td>
                                                              <td><?php echo $learner_data['last_activity']; ?></td>
                                                              <td><?php echo $learner_data['status']; ?></td>
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
    // jQuery(".users_options").on('change', function () {
    //     var user_id=jQuery(this).val();
    //     window.location.href=site_url+"/learner_details?user_id="+user_id;
    // });

    jQuery('#single_user_datatable').DataTable( {
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
            });
        }
    });
    //
    // jQuery("tfoot>tr>th.col-first-access-date>select").hide();
    // jQuery("tfoot>tr>th.col-completion-date>select").hide();
    // jQuery("tfoot>tr>th.col-progress>select").hide();
    //
    // jQuery("body").on('click','.learner-row', function () {
    //     var course_id=jQuery(this).data("course_id");
    //     window.location.href=site_url+"/course_details?course_id="+course_id;
    // });

});
</script>

<?php
function rp_rp_getSingleUserActivityTotals($user_id, $enrolled_courses){
  $courses_reports = array();

  $running_steps_total = 0;
  $completed_steps_total = 0;
  $completed_courses_total = 0;
  $in_progress_courses_total = 0;
  $not_started_courses_total = 0;


  // run a report for all allowed courses and get all results
  $activity_query_args = array(
    "post_ids" => $enrolled_courses,
    'per_page' =>	0,
  );

  $report = learndash_report_user_courses_progress($user_id, array(), $activity_query_args);


// echo "<pre>";var_dump($report['results']);echo "</pre>";exit();
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

    // record course name
    $courses_reports['courses'][$result->post_id]['course_name'] = $result->post_title;

    // if ($result->activity_type != 'access') {

      // record last activity
      if(intval($result->activity_updated) > 1476343863) {
        $last_activity = $result->activity_updated_formatted;
      } else {
        $last_activity = '';
      }
      $courses_reports['courses'][$result->post_id]['last_activity'] = (!empty($last_activity)) ? $last_activity : 'none';


      // record a course status and running totals
      if ($activity_meta['steps_completed'] >= $activity_meta['steps_total'] && !empty($activity_meta['steps_total'])) {
        $courses_reports['courses'][$result->post_id]['status'] = 'completed';
        $completed_courses_total = $completed_courses_total + 1;
      } elseif ($activity_meta['steps_completed'] < $activity_meta['steps_total'] && $activity_meta['steps_completed'] > 0 && !empty($activity_meta['steps_total'])) {
        $courses_reports['courses'][$result->post_id]['status'] = 'in_progress';
        $in_progress_courses_total = $in_progress_courses_total + 1;
      } else {
        $courses_reports['courses'][$result->post_id]['status'] = 'not_started';
        $not_started_courses_total = $not_started_courses_total + 1;
      }
    // } else {
    //   if (!array_key_exists($result->post_id, $courses_reports['courses'])) {
    //     $courses_reports['courses'][$result->post_id]['status'] = 'not_started';
    //     $not_started_courses_total = $not_started_courses_total + 1;
    //     $courses_reports['courses'][$result->post_id]['last_activity'] = 'none';
    //   }
    // }



  }

  $result = array(
    'courses' => $courses_reports['courses'],
    'running_steps_total' => $running_steps_total,
    'completed_steps_total' => $completed_steps_total,
    'completed_courses_total' => $completed_courses_total,
    'in_progress_courses_total' => $in_progress_courses_total,
    'not_started_courses_total' => $not_started_courses_total,
  );
  // echo "<pre>";var_dump($result);echo "</pre>";exit();
  // return an array of calculated values.
  return $result;


}

 ?>
