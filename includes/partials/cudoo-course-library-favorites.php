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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<!-- <h1> My list </h1> -->

<!-- <div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;">
	<div class=" full_section_inner clearfix">
		<div class="wpb_column vc_column_container vc_col-sm-12">
			<div class="vc_column-inner ">
				<div class="wpb_wrapper">
					<div class="wpb_text_column wpb_content_element ">
						<div class="wpb_wrapper">
							<p>very top row</p>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->
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
                  <p class="dash-library-top-nav-main" style="font-weight: bold;color: inherit;"><a href="/library/favorites/"><i class="fa fa-bookmark" aria-hidden="true"></i> Favorites</a></p>
                </div>

                <div class="menu-item">
                  <p class="dash-library-top-nav-main"><a href="/library/archive/"><i class="fa fa-archive" aria-hidden="true"></i> Archive</a></p>
                </div>

                <div class="menu-item">
                  <p class="dash-library-top-nav-main"><a href="/my-account/"><i class="fa fa-user" aria-hidden="true"></i> My Account</a></p>
                </div>
              </nav>

              <div class="separator  normal" style="margin-top: 20px;margin-bottom: 10px;"></div>

              <div class="dash-library-section" style="">
                <p>Categories</p>
              </div>

          		<?php $this->generate_sidebar_navigation('favorites'); ?>

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

					<div class="vc_row wpb_row section vc_row-fluid notice-container" >
						<div class="notice-close">
							<p><a href="#" id="notice-close-link"><i class="fa fa-times" aria-hidden="true"></i></a></p>
						</div>

					    <div class=" full_section_inner clearfix">
					        <div class="wpb_column vc_column_container vc_col-sm-12" style="">
					            <div class="vc_column-inner " style="">
					                <div class="wpb_wrapper">
					                    <div class="wpb_raw_code wpb_content_element wpb_raw_html">
					                        <div class="wpb_wrapper">

				                            <!-- BEGINNING OF THE NOTICE -->
		                                <div class="notice">
		                                    <div>
																					<p>This is your Favorites dashboard! You can bookmark courses by adding to to Favorites and they will appear here. <br></p>
																				</div>
																				<div class="notice-img">
				                                    <img src="/wp-content/uploads/2017/04/library-favourites.png" />
				                                </div>
		                                </div>
				                            <!-- END OF THE NOTICE -->
					                        </div>
					                    </div>
					                </div>
					            </div>
					        </div>
					    </div>
					</div>

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

                        <h3 style="text-align: center;font-size: 1.8em;color: #777;text-transform: none;">Favorites</h3>

                        <div class="cudoo-library">

													<?php if (!empty($user_courses)) : ?>

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
																		<p style="margin: 0;padding: 0;font-size: 14px;color: #868686;">
																			<?php if (!empty($course['certificat	e_url'])) : ?>
																			<a href="<?php echo $course['certificate_url']; ?>" title="Print this Certificate" target="_blank"><i class="fa fa-certificate" style="color:orangered" aria-hidden="true"></i></a> |
																		<?php endif; ?>
																		<?php
																		$nonce = wp_create_nonce("library_add_bookmark_nonce");
																		$user_id = $current_user->ID;
																		$bookmark_set = ($course['bookmark_set']) ? 'set' : 'unset';
																		$bookmark_set_class = ($course['bookmark_set']) ? 'fa-bookmark' : 'fa-bookmark-o';
																		$favorites_tooltip_text = ($course['bookmark_set']) ? 'Remove from Favorites' : 'Add to Favorites';
																		?>
																			<a href="#" class="add_to_bookmarks hint-bottom-middle hint-anim" data-bookmark_status="<?php echo $bookmark_set; ?>" data-post_id="<?php echo $course['course_id']; ?>" data-user_id="<?php echo $user_id; ?>" data-nonce="<?php echo $nonce; ?>" data-hint="<?php echo $favorites_tooltip_text; ?>" ><i class="fa <?php echo $bookmark_set_class; ?>" aria-hidden="true"></i></a>
																		</p>
																	</div>
																</div>
                          		</li>
                              <?php
                            }
                             ?>
                        	</ul>

                        <?php $this->get_my_list_pagination($loop->max_num_pages); ?>

												<?php else: ?>
												<div style="max-width: 400px;margin:10px auto;">
													<div class="separator  normal" style="margin-top: 20px;margin-bottom: 20px;"></div>
													<p style="font-family: 'Helvetica';text-align: center;">You don't have any courses in your Fovorites list. </p>
													<p style="text-align: center;font-size: 4em;font-weight: 300;"><a href="/library/my-list/">+</a></p>
													<p style="font-family: 'Helvetica';text-align: center;">Go to My List</p>
													<div class="separator  normal" style="margin-top: 20px;margin-bottom: 20px;"></div>
												</div>
											<?php endif; ?>

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
