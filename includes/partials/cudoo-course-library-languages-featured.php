<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * Must have $activity_chart_data
 *
 * @link       http://cudoo.com
 * @since      1.0.0
 *
 * @package    Cudoo_Course_Library
 * @subpackage Cudoo_Course_Library/public/partials
 */
?>

<div class="vc_row wpb_row section vc_row-fluid disable_negative_margin my-list-dashboard" style=" text-align:left;">
	<div class=" full_section_inner clearfix">
		<div class="wpb_column vc_column_container vc_col-sm-2" style="padding: 0 10px;">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">
					<div class="wpb_text_column wpb_content_element ">
						<div class="wpb_wrapper">


              <nav class="dash-library-top-nav" style="margin-top: 30px;">
                <div class="menu-item alpha">
                  <p class="dash-library-top-nav-main" ><a href="/library/my-list/"><i class="fa fa-book" aria-hidden="true"></i> My List</a></p>
                </div>
                <div class="menu-item">
                  <p class="dash-library-top-nav-main"><a href="/library/favorites/"><i class="fa fa-bookmark" aria-hidden="true"></i> Favorites</a></p>
                </div>

                <div class="menu-item">
                  <p class="dash-library-top-nav-main"><a href="/library/archive/"><i class="fa fa-archive" aria-hidden="true"></i> Archive</a></p>
                </div>

                <div class="menu-item">
                  <p class="dash-library-top-nav-main"><a href="/my-account/"><i class="fa fa-user" aria-hidden="true"></i> My Account</a></p>
                </div>
              </nav>

              <div class="separator normal" style="margin-top: 20px;margin-bottom: 10px;"></div>

              <div class="dash-library-section" style="">
                <p>Categories</p>
              </div>

              <?php $this->generate_sidebar_navigation('main'); ?>

              <div class="separator  normal" style="margin-top: 20px;margin-bottom: 10px;"></div>

              <!-- <div class="dash-library-section" style="">
                <p>Level</p>
              </div> -->

              <!-- <div class="dash-levels-nav">
                <div>
                  <input type="checkbox" value="beginner" name="beginner">Beginner<br>
                  <input type="checkbox" value="intermediate" name="intermediate" >Intermediate<br>
                  <input type="checkbox" value="advanced" name="advanced" >Advanced<br>
                </div>
              </div> -->

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="wpb_column vc_column_container vc_col-sm-10">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">

					<div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;">
				      <div class=" full_section_inner clearfix">
				          <div class="wpb_column vc_column_container vc_col-sm-12">
				              <div class="vc_column-inner ">
				                  <div class="wpb_wrapper">
				                      <div class="wpb_raw_code wpb_content_element wpb_raw_html">
				                          <div class="wpb_wrapper">
				                            <div id="user_name">
				                                <?php if ((!empty($current_user->user_lastname)) || (!empty($current_user->user_firstname))): ?>
				                                    <h1 style="color: #777;text-align: center;margin: 2px 0px;"><?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?></h1>
				                                <?php endif; ?>
				                                <!-- Get user meta {tour_completed} if 1 then dont show tour else Autoplay it. -->
				                                <?php
				                                if($user_id !== 0 && get_user_meta($user_id,'tour_completed',true)==1){
				                                    $tour_completed= true;
				                                }else{
				                                    $tour_completed= false;
				                                }
				                                ?>
				                                <p style="color: #777;text-align: center;font-size: 13px;margin-top: 2px;"><a class="clearall" href="/my-account/">My Account</a> | <a href="/my-account/edit-account/">Profile Settings</a><?php if($tour_completed===true){ echo ' | <a id="take-a-tour" class="button button-primary" style="color:orangered;font-weight:bold;" href="javascript:void(0);" onclick="takeTheTour();">Take a tour?</a>';}?></p>
				                            </div>
				                          </div>
				                      </div>
				                  </div>
				              </div>
				          </div>
				      </div>
				  </div>

					<div class="vc_row wpb_row section vc_inner vc_row-fluid" style=" text-align:left;margin-top: 20px;">
						<div class=" full_section_inner clearfix">
							<div class="wpb_column vc_column_container vc_col-sm-12">
								<div class="vc_column-inner ">
									<div class="wpb_wrapper">
										<div class="wpb_text_column wpb_content_element ">
											<div class="wpb_wrapper">

                        <h3 style="text-align: center;font-size: 1.8em;color: #777;text-transform: none;"><?php echo $page_name; ?></h3>

                        <div class="cudoo-library">

                        	<ul class="cudoo-library-courses clearfix">
                            <?php
                            foreach ($user_courses as $course) {
                              ?>
                              <li class="library-course">
                                <a href="<?php echo $course['url']; ?>" class="library-course-link">
																	<div class="lib-course-container">
                                  	<img width="538" height="240" src="<?php echo $course['image']; ?>" class="wp-post-image" alt="" title="">
																		<span class="fa fa-play fa-5x"></span>
																	</div>
                                  <h3><?php echo $course['course_name']; ?></h3>
                                </a>
                                <div class="library-course-desc">
                          				<div itemprop="description"><?php echo $course['course_excerpt']; ?></div>
                          			</div>
																<div class="details-row">
																	<div style="float: left;">
																		<p style="margin: 0;padding: 0;font-size: 14px;color: #868686;"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo $course['progress']['completed']; ?> / <?php echo $course['progress']['total']; ?></p>
																	</div>

																	<div style="float: right;">
                                    <?php if (!empty($course['enroll_url']) && $course['status'] != 'enrolled' && $course['status'] != 'upgrade') : ?>
                                    <p style="margin: 0;padding: 0;font-size: 14px;color: #868686;">
                                      <a href="<?php echo $course['enroll_url']; ?>" class="library_enroll_button hint-bottom-middle hint-anim" data-hint="Enroll to this course"><span>Enroll Now</span></a>
                                    </p>
                                    <?php endif; ?>

                                    <?php if (!empty($course['enroll_url']) && $course['status'] == 'upgrade') : ?>
                                    <p style="margin: 0;padding: 0;font-size: 14px;color: #868686;">
                                      <a href="<?php echo $course['enroll_url']; ?>" class="library_enroll_button hint-bottom-middle hint-anim" data-hint="View Pricing"><span>Pricing</span></a>
                                    </p>
                                    <?php endif; ?>

                                    <!-- <p style="margin: 0;padding: 0;font-size: 14px;color: #868686;">
																			<?php if (!empty($course['certificate_url'])) : ?>
																			<a href="<?php echo $course['certificate_url']; ?>" title="Print this Certificate" class="tooltips" target="_blank"><i class="fa fa-certificate" style="color:orangered" aria-hidden="true"></i><span class="tt">Print this Certificate</span></a> |
																		<?php endif; ?>
																		<?php
																		// $nonce = wp_create_nonce("library_add_bookmark_nonce");
																		// $user_id = $current_user->ID;
																		// $bookmark_set = ($course['bookmark_set']) ? 'set' : 'unset';
																		// $bookmark_set_class = ($course['bookmark_set']) ? 'fa-bookmark' : 'fa-bookmark-o';
																		// $favorites_tooltip_text = ($course['bookmark_set']) ? 'Remove from Favorites' : 'Add to Favorites';
																		?>
																			<a href="#" class="add_to_bookmarks tooltips" data-bookmark_status="<?php echo $bookmark_set; ?>" data-post_id="<?php echo $course['course_id']; ?>" data-user_id="<?php echo $user_id; ?>" data-nonce="<?php echo $nonce; ?>" ><i class="fa <?php echo $bookmark_set_class; ?>" aria-hidden="true"></i><span class="tt"><?php echo $favorites_tooltip_text; ?></span></a>
																		</p> -->
																	</div>
																</div>
                          		</li>
                              <?php
                            }
                             ?>
                        	</ul>

                        <?php
                        // next_posts_link( 'Older Entries', $loop->max_num_pages );
                        // previous_posts_link( 'Newer Entries' );

                        $this->get_my_list_pagination($loop->max_num_pages);
                          // echo "<pre>";var_dump($pagination);echo "</pre>";exit();
                        ?>

                        </div>

                        <?php next_posts_link(); ?>
												<?php

												// echo "<pre>";var_dump($user_courses);echo "</pre>";//exit();
												?>
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
