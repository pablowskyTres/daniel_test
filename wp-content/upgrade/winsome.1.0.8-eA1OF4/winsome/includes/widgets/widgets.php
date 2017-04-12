<?php
/**
 * Custom widgets.
 *
 * @package Winsome
 */

if ( ! function_exists( 'winsome_load_widgets' ) ) :

	/**
	 * Load widgets.
	 *
	 * @since 1.0.0
	 */
	function winsome_load_widgets() {

		// Social.
		register_widget( 'Winsome_Social_Widget' );

		// Latest news.
		register_widget( 'Winsome_Latest_News_Widget' );

		// CTA widget.
		register_widget( 'Winsome_CTA_Widget' );

		// Services widget.
		register_widget( 'Winsome_Services_Widget' );

		// Features widget.
		register_widget( 'Winsome_Features_Widget' );

		// Facts widget.
		register_widget( 'Winsome_Facts_Widget' );

		// Contact widget.
		register_widget( 'Winsome_Contact_Widget' );

	}

endif;

add_action( 'widgets_init', 'winsome_load_widgets' );


if ( ! class_exists( 'Winsome_Social_Widget' ) ) :

	/**
	 * Social widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_Social_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'   => 'winsome_widget_social',
				'description' => esc_html__( 'Social Icons Widget', 'winsome' ),
			);
			parent::__construct( 'winsome-social', esc_html__( 'Winsome: Social', 'winsome' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ). $args['after_title'];
			}

			if ( has_nav_menu( 'social' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'social',
					'link_before'    => '<span class="screen-reader-text">',
					'link_after'     => '</span>',
				) );
			}

			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            {@see WP_Widget::form()}.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title'] = sanitize_text_field( $new_instance['title'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 * @return void
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
				'title' => '',
			) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'winsome' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<?php if ( ! has_nav_menu( 'social' ) ) : ?>
	        <p>
				<?php esc_html_e( 'Social menu is not set. Please create menu and assign it to Social Theme Location.', 'winsome' ); ?>
	        </p>
	        <?php endif; ?>
			<?php
		}
	}

endif;


if ( ! class_exists( 'Winsome_Latest_News_Widget' ) ) :

	/**
	 * Latest News widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_Latest_News_Widget extends WP_Widget {

	    function __construct() {
	    	$opts = array(
				'classname'   => 'winsome_widget_latest_news',
				'description' => esc_html__( 'Latest News Widget', 'winsome' ),
    		);

			parent::__construct( 'winsome-latest-news', esc_html__( 'Winsome: Latest News', 'winsome' ), $opts );
	    }


	    function widget( $args, $instance ) {

	    	$section_id 	   = !empty( $instance[ 'section_id' ] ) ? esc_attr( $instance[ 'section_id' ] ) : esc_attr( $this->id );

			$title             = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$post_category     = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
			$post_column       = ! empty( $instance['post_column'] ) ? $instance['post_column'] : 4;
			$post_number       = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 4;

	        echo $args['before_widget']; ?>

	        <div id="<?php echo esc_attr( $section_id ); ?>" class="latest-news-widget latest-news-col-<?php echo esc_attr( $post_column ); ?>">

	        	<div class="container">

			        <?php 

			        if ( $title ) {
			        	echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			        }

			        ?>
			        <?php
			        $query_args = array(
			        	'posts_per_page' => esc_attr( $post_number ),
			        	'no_found_rows'  => true,
			        	);
			        if ( absint( $post_category ) > 0 ) {
			        	$query_args['category'] = absint( $post_category );
			        }

			        $all_posts = get_posts( $query_args );
			        ?>
			        <?php if ( ! empty( $all_posts ) ) : ?>

						<?php global $post; ?>

			            <div class="inner-wrapper">

							<?php foreach ( $all_posts as $key => $post ) : ?>
			                <?php setup_postdata( $post ); ?>

			                <div class="latest-news-item">
				                <div class="latest-news-wrapper">
					                <?php if ( has_post_thumbnail() ) :  ?>
					                  <div class="latest-news-thumb">
					                    <a href="<?php the_permalink(); ?>">
											<?php
					                        $img_attributes = array( 'class' => 'aligncenter' );
					                        the_post_thumbnail( 'winsome-large', $img_attributes );
											?>
					                    </a>
					                  </div><!-- .latest-news-thumb -->
					                <?php endif; ?>
					                <div class="latest-news-text-wrap">
					                  <h3 class="latest-news-title">
					                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					                  </h3><!-- .latest-news-title -->
					                  <div class="latest-news-excerpt">
					                  	<?php $content = winsome_get_the_excerpt( 20, $post );
											echo $content ? wpautop( wp_kses_post( $content ) ) : '';  ?>
					                  </div>
					                 <a href="<?php the_permalink(); ?>" class="button cta-button cta-button-primary"><?php esc_html_e( 'Read More', 'winsome' ); ?></a>
					                    
					                </div><!-- .latest-news-text-wrap -->
				                </div>
			                </div>

							<?php endforeach; ?>

			            </div><!-- .row -->

						<?php wp_reset_postdata(); ?>

			        <?php endif; ?>

	        	</div>

	         </div><!-- .latest-news-widget -->

	        <?php
	        echo $args['after_widget'];

	    }

	    function update( $new_instance, $old_instance ) {
	        $instance = $old_instance;
			$instance[ 'section_id' ] 	= sanitize_key( $new_instance[ 'section_id' ] );
			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['post_category']  = absint( $new_instance['post_category'] );
			$instance['post_number']    = absint( $new_instance['post_number'] );
			$instance['post_column']    = absint( $new_instance['post_column'] );

	        return $instance;
	    }

	    function form( $instance ) {

	        $instance = wp_parse_args( (array) $instance, array(
				'section_id' 	 => '',
				'title'          => '',
				'post_category'  => '',
				'post_column'    => 3,
				'post_number'    => 3,
	        ) );
	        ?>
	        <p>
	        	<label for="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>"><strong><?php esc_html_e( 'Section ID:', 'winsome' ); ?></strong></label>
	        	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['section_id'] ); ?>" />
		        <small>
		        	<?php esc_html_e('Section ID needs to be unique. This id will be used in menu to enable one page scroll.', 'winsome'); ?>	
		        </small>
	        </p>
	        <p>
	          <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'winsome' ); ?></strong></label>
	          <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
	        </p>
	        <p>
	          <label for="<?php echo  esc_attr( $this->get_field_id( 'post_category' ) ); ?>"><strong><?php esc_html_e( 'Select Category:', 'winsome' ); ?></strong></label>
				<?php
	            $cat_args = array(
	                'orderby'         => 'name',
	                'hide_empty'      => 0,
	                'taxonomy'        => 'category',
	                'name'            => $this->get_field_name( 'post_category' ),
	                'id'              => $this->get_field_id( 'post_category' ),
	                'selected'        => absint( $instance['post_category'] ),
	                'show_option_all' => esc_html__( 'All Categories','winsome' ),
	              );
	            wp_dropdown_categories( $cat_args );
				?>
	        </p>
	        <p>
	          <label for="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>"><strong><?php esc_html_e( 'Number of Posts:', 'winsome' ); ?></strong></label>
	          <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>" name="<?php echo  esc_attr( $this->get_field_name( 'post_number' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['post_number'] ); ?>" min="1" />
	        </p>
	        <p>
	          <label for="<?php echo esc_attr( $this->get_field_id( 'post_column' ) ); ?>"><strong><?php esc_html_e( 'Number of Columns:', 'winsome' ); ?></strong></label>
				<?php
	            $this->dropdown_post_columns( array(
					'id'       => $this->get_field_id( 'post_column' ),
					'name'     => $this->get_field_name( 'post_column' ),
					'selected' => absint( $instance['post_column'] ),
					)
	            );
				?>
	        </p>
	        <?php
	    }

	    function dropdown_post_columns( $args ) {
			$defaults = array(
		        'id'       => '',
		        'name'     => '',
		        'selected' => 0,
			);

			$r = wp_parse_args( $args, $defaults );
			$output = '';

			$choices = array(
				'2' => 2,
				'3' => 3,
				'4' => 4,
			);

			if ( ! empty( $choices ) ) {

				$output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
				foreach ( $choices as $key => $choice ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ';
					$output .= selected( $r['selected'], $key, false );
					$output .= '>' . esc_html( $choice ) . '</option>\n';
				}
				$output .= "</select>\n";
			}

			echo $output;
	    }

	}

endif;

if ( ! class_exists( 'Winsome_CTA_Widget' ) ) :

	/**
	 * CTA widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_CTA_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'   => 'winsome_widget_call_to_action',
				'description' => esc_html__( 'Call To Action Widget', 'winsome' ),
			);
			parent::__construct( 'winsome-cta', esc_html__( 'Winsome: CTA', 'winsome' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {
			$section_id  = !empty( $instance[ 'section_id' ] ) ? esc_attr( $instance[ 'section_id' ] ) : esc_attr( $this->id );
			$title       = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$cta_page    = !empty( $instance['cta_page'] ) ? $instance['cta_page'] : ''; 
			$button_text = ! empty( $instance['button_text'] ) ? esc_html( $instance['button_text'] ) : '';
			$button_url  = ! empty( $instance['button_url'] ) ? esc_url( $instance['button_url'] ) : '';
			$bg_pic  	 = ! empty( $instance['bg_pic'] ) ? esc_url( $instance['bg_pic'] ) : '';

			// Add background image.
			if ( ! empty( $bg_pic ) ) {
				$background_style = '';
				$background_style .= ' style="background-image:url(' . esc_url( $bg_pic ) . ');" ';
				$args['before_widget'] = implode( $background_style . ' ' . 'class="bg_enabled ', explode( 'class="', $args['before_widget'], 2 ) );
			}

			echo $args['before_widget']; ?>

			<div id="<?php echo esc_attr( $section_id ); ?>" class="cta-widget">

				<div class="container">

					<?php

					if ( ! empty( $title ) ) {
						echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
					}  

					if ( $cta_page ) { 

						$cta_args = array(
										'posts_per_page' => 1,
										'page_id'	     => absint( $cta_page ),
										'post_type'      => 'page',
										'post_status'  	 => 'publish',
									);

						$cta_query = new WP_Query( $cta_args );	

						if( $cta_query->have_posts()){

							while( $cta_query->have_posts()){

								$cta_query->the_post(); ?>

								<div class="call-to-action-content">
									<?php the_content(); ?>
								</div>

								<?php

							}

							wp_reset_postdata();

						} ?>
						
					<?php } ?>

					<div class="call-to-action-buttons">
						<?php if ( ! empty( $button_text ) ) : ?>
							<a href="<?php echo esc_url( $button_url ); ?>" class="button cta-button cta-button-primary"><?php echo esc_attr( $button_text ); ?></a>
						<?php endif; ?>
					</div><!-- .call-to-action-buttons -->

				</div>

			</div><!-- .cta-widget -->

			<?php
			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            {@see WP_Widget::form()}.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance[ 'section_id' ] 	= sanitize_key( $new_instance[ 'section_id' ] );

			$instance['title'] 			= sanitize_text_field( $new_instance['title'] );

			$instance['cta_page'] 	 	= absint( $new_instance['cta_page'] );


			$instance['button_text'] 	= sanitize_text_field( $new_instance['button_text'] );
			$instance['button_url']  	= esc_url_raw( $new_instance['button_url'] );
			$instance['bg_pic']  	 	= esc_url_raw( $new_instance['bg_pic'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, array(
				'section_id'  => '',
				'title'       => '',
				'cta_page'    => '',
				'button_text' => esc_html__( 'Find More', 'winsome' ),
				'button_url'  => '',
				'bg_pic'      => '',
			) );

			$bg_pic = '';

            if ( ! empty( $instance['bg_pic'] ) ) {

                $bg_pic = $instance['bg_pic'];

            }

            $wrap_style = '';

            if ( empty( $bg_pic ) ) {

                $wrap_style = ' style="display:none;" ';
            }

            $image_status = false;

            if ( ! empty( $bg_pic ) ) {
                $image_status = true;
            }

            $delete_button = 'display:none;';

            if ( true === $image_status ) {
                $delete_button = 'display:inline-block;';
            }
			?>
			<p>
	        	<label for="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>"><strong><?php esc_html_e( 'Section ID:', 'winsome' ); ?></strong></label>
	        	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['section_id'] ); ?>" />
		        <small>
		        	<?php esc_html_e('Section ID needs to be unique. This id will be used in menu to enable one page scroll.', 'winsome'); ?>	
		        </small>
	        </p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'cta_page' ); ?>">
					<strong><?php esc_html_e( 'CTA Page:', 'winsome' ); ?></strong>
				</label>
				<?php
				wp_dropdown_pages( array(
					'id'               => $this->get_field_id( 'cta_page' ),
					'class'            => 'widefat',
					'name'             => $this->get_field_name( 'cta_page' ),
					'selected'         => $instance[ 'cta_page' ],
					'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'winsome' ),
					)
				);
				?>
				<small>
		        	<?php esc_html_e('Content of this page will be used as content of CTA', 'winsome'); ?>	
		        </small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><strong><?php esc_html_e( 'Button Text:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['button_text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>"><strong><?php esc_html_e( 'Button URL:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_url' ) ); ?>" type="text" value="<?php echo esc_url( $instance['button_url'] ); ?>" />
			</p>

			<div class="cover-image">
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>">
                    <strong><?php esc_html_e( 'Background Image:', 'winsome' ); ?></strong>
                </label>
                <input type="text" class="img widefat" name="<?php echo esc_attr( $this->get_field_name( 'bg_pic' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>" value="<?php echo esc_url( $instance['bg_pic'] ); ?>" />
                <div class="rtam-preview-wrap" <?php echo $wrap_style; ?>>
                    <img src="<?php echo esc_url( $bg_pic ); ?>" alt="<?php esc_attr_e( 'Preview', 'winsome' ); ?>" />
                </div><!-- .rtam-preview-wrap -->
                <input type="button" class="select-img button button-primary" value="<?php esc_html_e( 'Upload', 'winsome' ); ?>" data-uploader_title="<?php esc_html_e( 'Select Background Image', 'winsome' ); ?>" data-uploader_button_text="<?php esc_html_e( 'Choose Image', 'winsome' ); ?>" />
                <input type="button" value="<?php echo esc_attr_x( 'X', 'Remove Button', 'winsome' ); ?>" class="button button-secondary btn-image-remove" style="<?php echo esc_attr( $delete_button ); ?>" />
            </div>
		<?php
		} 
	
	}

endif;

if ( ! class_exists( 'Winsome_Services_Widget' ) ) :

	/**
	 * Service widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_Services_Widget extends WP_Widget {

		function __construct() {
			$opts = array(
					'classname'   => 'winsome_widget_services',
					'description' => esc_html__( 'Display services.', 'winsome' ),
			);
			parent::__construct( 'winsome-services', esc_html__( 'Winsome: Services', 'winsome' ), $opts );
		}

		function widget( $args, $instance ) {

			$section_id = !empty( $instance[ 'section_id' ] ) ? esc_attr( $instance[ 'section_id' ] ) : esc_attr( $this->id );

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$services_ids = array();
			$item_number = 6;

			for ( $i = 1; $i <= $item_number; $i++ ) {
				if ( ! empty( $instance["item_id_$i"] ) && absint( $instance["item_id_$i"] ) > 0 ) {
					$id = absint( $instance["item_id_$i"] );
					$services_ids[ $id ]['id']   = $id;
					$services_ids[ $id ]['icon'] = $instance["item_icon_$i"];
				}
			}

			echo $args['before_widget']; ?>

			<div id="<?php echo esc_attr( $section_id ); ?>" class="services-list services-column-3">

				<div class="container">

					<?php

					if ( $title ) {
						echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
					}

					if ( ! empty( $services_ids ) ) {
						$query_args = array(
							'posts_per_page' => count( $services_ids ),
							'post__in'       => wp_list_pluck( $services_ids, 'id' ),
							'orderby'        => 'post__in',
							'post_type'      => 'page',
							'no_found_rows'  => true,
						);
						$all_services = get_posts( $query_args ); ?>

						<?php if ( ! empty( $all_services ) ) : ?>
							<?php global $post; ?>
							
								<div class="inner-wrapper">

									<?php foreach ( $all_services as $post ) : ?>
										<?php setup_postdata( $post ); ?>
										<div class="services-item">
											<div class="service-icon">
												<i class="fa <?php echo esc_attr( $services_ids[ $post->ID ]['icon'] ); ?>"></i>
											</div>
											<h3 class="services-item-title"><?php the_title(); ?></h3>
											<?php $content = winsome_get_the_excerpt( 20, $post );
											echo $content ? wpautop( wp_kses_post( $content ) ) : '';
											?>
										</div><!-- .services-item -->
									<?php endforeach; ?>

								</div><!-- .inner-wrapper -->

							<?php wp_reset_postdata(); ?>

						<?php endif;
					} ?>

				</div>

			</div><!-- .services-list -->

			<?php

			echo $args['after_widget'];

		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance[ 'section_id' ] 	= sanitize_key( $new_instance[ 'section_id' ] );

			$instance['title'] 			= sanitize_text_field( $new_instance['title'] );

			$item_number = 8;

			for ( $i = 1; $i <= $item_number; $i++ ) {
				$instance["item_id_$i"]   = absint( $new_instance["item_id_$i"] );
				$instance["item_icon_$i"] = sanitize_text_field( $new_instance["item_icon_$i"] );
			}

			return $instance;
		}

		function form( $instance ) {

			// Defaults.
			$defaults = array(
							'title' 		=> '',
							'section_id' 	=> '',
						);

			$item_number = 8;

			for ( $i = 1; $i <= $item_number; $i++ ) {
				$defaults["item_id_$i"]   = '';
				$defaults["item_icon_$i"] = 'fa-cog';
			}

			$instance = wp_parse_args( (array) $instance, $defaults );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>"><strong><?php esc_html_e( 'Section ID:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['section_id'] ); ?>" />
				<small>
					<?php esc_html_e('Section ID needs to be unique. This id will be used in menu to enable one page scroll.', 'winsome'); ?>	
				</small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			
			<?php
				for ( $i = 1; $i <= $item_number; $i++ ) {
					?>
					<p>
						<label for="<?php echo $this->get_field_id( "item_id_$i" ); ?>"><strong><?php esc_html_e( 'Page:', 'winsome' ); ?>&nbsp;<?php echo $i; ?></strong></label>
						<?php
						wp_dropdown_pages( array(
							'id'               => $this->get_field_id( "item_id_$i" ),
							'class'            => 'widefat',
							'name'             => $this->get_field_name( "item_id_$i" ),
							'selected'         => $instance["item_id_$i"],
							'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'winsome' ),
							)
						);
						?>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( "item_icon_$i" ) ); ?>"><strong><?php esc_html_e( 'Icon:', 'winsome' ); ?>&nbsp;<?php echo $i; ?></strong></label>
						<input id="<?php echo esc_attr( $this->get_field_id( "item_icon_$i" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "item_icon_$i" ) ); ?>" type="text" value="<?php echo esc_attr( $instance["item_icon_$i"] ); ?>" />
					</p>
					<?php
				}
		}
	}

endif;

if ( ! class_exists( 'Winsome_Features_Widget' ) ) :

	/**
	 * Features widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_Features_Widget extends WP_Widget {

		function __construct() {
			$opts = array(
					'classname'   => 'winsome_widget_features',
					'description' => esc_html__( 'Display features.', 'winsome' ),
			);
			parent::__construct( 'winsome-features', esc_html__( 'Winsome: Features', 'winsome' ), $opts );
		}

		function widget( $args, $instance ) {

			$section_id = !empty( $instance[ 'section_id' ] ) ? esc_attr( $instance[ 'section_id' ] ) : esc_attr( $this->id );

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$bg_pic  	 = ! empty( $instance['bg_pic'] ) ? esc_url( $instance['bg_pic'] ) : '';

			$services_ids = array();
			$item_number = 5;

			for ( $i = 1; $i <= $item_number; $i++ ) {
				if ( ! empty( $instance["item_id_$i"] ) && absint( $instance["item_id_$i"] ) > 0 ) {
					$id = absint( $instance["item_id_$i"] );
					$services_ids[ $id ]['id']   = $id;
					$services_ids[ $id ]['icon'] = $instance["item_icon_$i"];
				}
			}

			echo $args['before_widget']; ?>

			<div id="<?php echo esc_attr( $section_id ); ?>" class="features-wrapper">

				<div class="container">

					<div class="inner-wrapper">

					<?php

					if ( $title ) {
						echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
					} ?>

					<?php if(!empty( $bg_pic ) ){ ?>
						<div class="feature-image">
							<img src="<?php echo esc_url( $bg_pic ); ?>">
						</div>
					<?php } ?>

					<?php 

					if ( ! empty( $services_ids ) ) {
						$query_args = array(
							'posts_per_page' => count( $services_ids ),
							'post__in'       => wp_list_pluck( $services_ids, 'id' ),
							'orderby'        => 'post__in',
							'post_type'      => 'page',
							'no_found_rows'  => true,
						);
						$all_services = get_posts( $query_args ); ?>

						<?php if ( ! empty( $all_services ) ) : ?>
							<?php global $post; ?>
							
								<div class="features-list">

									<?php foreach ( $all_services as $post ) : ?>
										<?php setup_postdata( $post ); ?>
										<div class="features-item">
											<div class="features-icon">
												<i class="fa <?php echo esc_attr( $services_ids[ $post->ID ]['icon'] ); ?>"></i>
											</div>
											<div class="features-detail">
												<h3 class="features-item-title"><?php the_title(); ?></h3>
												<?php $content = winsome_get_the_excerpt( 20, $post );
												echo $content ? wpautop( wp_kses_post( $content ) ) : '';
												?>
											</div>
										</div><!-- .features-item -->
									<?php endforeach; ?>

								</div><!-- .features-list -->

							<?php wp_reset_postdata(); ?>

						<?php endif;
					} ?>

					</div><!-- .inner-wrapper -->

				</div>

			</div><!-- .services-list -->

			<?php

			echo $args['after_widget'];

		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance[ 'section_id' ] 	= sanitize_key( $new_instance[ 'section_id' ] );

			$instance['title'] 			= sanitize_text_field( $new_instance['title'] );

			$instance['bg_pic']  	 	= esc_url_raw( $new_instance['bg_pic'] );


			$item_number = 5;

			for ( $i = 1; $i <= $item_number; $i++ ) {
				$instance["item_id_$i"]   = absint( $new_instance["item_id_$i"] );
				$instance["item_icon_$i"] = esc_attr( $new_instance["item_icon_$i"] );
			}

			return $instance;
		}

		function form( $instance ) {

			// Defaults.
			$defaults = array(
							'title' 		=> '',
							'section_id' 	=> '',
							'bg_pic'      	=> '',
						);

			$item_number = 5;

			for ( $i = 1; $i <= $item_number; $i++ ) {
				$defaults["item_id_$i"]   = '';
				$defaults["item_icon_$i"] = 'fa-cog';
			}

			$instance = wp_parse_args( (array) $instance, $defaults );

			$bg_pic = '';

			if ( ! empty( $instance['bg_pic'] ) ) {

			    $bg_pic = $instance['bg_pic'];

			}

			$wrap_style = '';

			if ( empty( $bg_pic ) ) {

			    $wrap_style = ' style="display:none;" ';
			}

			$image_status = false;

			if ( ! empty( $bg_pic ) ) {
			    $image_status = true;
			}

			$delete_button = 'display:none;';

			if ( true === $image_status ) {
			    $delete_button = 'display:inline-block;';
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>"><strong><?php esc_html_e( 'Section ID:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['section_id'] ); ?>" />
				<small>
					<?php esc_html_e('Section ID needs to be unique. This id will be used in menu to enable one page scroll.', 'winsome'); ?>	
				</small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<div class="cover-image">
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>">
                    <strong><?php esc_html_e( 'Featured Image:', 'winsome' ); ?></strong>
                </label>
                <input type="text" class="img widefat" name="<?php echo esc_attr( $this->get_field_name( 'bg_pic' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>" value="<?php echo esc_url( $instance['bg_pic'] ); ?>" />
                <div class="rtam-preview-wrap" <?php echo $wrap_style; ?>>
                    <img src="<?php echo esc_url( $bg_pic ); ?>" alt="<?php esc_attr_e( 'Preview', 'winsome' ); ?>" />
                </div><!-- .rtam-preview-wrap -->
                <input type="button" class="select-img button button-primary" value="<?php esc_html_e( 'Upload', 'winsome' ); ?>" data-uploader_title="<?php esc_html_e( 'Select Background Image', 'winsome' ); ?>" data-uploader_button_text="<?php esc_html_e( 'Choose Image', 'winsome' ); ?>" />
                <input type="button" value="<?php echo esc_attr_x( 'X', 'Remove Button', 'winsome' ); ?>" class="button button-secondary btn-image-remove" style="<?php echo esc_attr( $delete_button ); ?>" />
            </div>
			
			<?php
				for ( $i = 1; $i <= $item_number; $i++ ) {
					?>
					<p>
						<label for="<?php echo $this->get_field_id( "item_id_$i" ); ?>"><strong><?php esc_html_e( 'Page:', 'winsome' ); ?>&nbsp;<?php echo $i; ?></strong></label>
						<?php
						wp_dropdown_pages( array(
							'id'               => $this->get_field_id( "item_id_$i" ),
							'class'            => 'widefat',
							'name'             => $this->get_field_name( "item_id_$i" ),
							'selected'         => $instance["item_id_$i"],
							'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'winsome' ),
							)
						);
						?>
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( "item_icon_$i" ) ); ?>"><strong><?php esc_html_e( 'Icon:', 'winsome' ); ?>&nbsp;<?php echo $i; ?></strong></label>
						<input id="<?php echo esc_attr( $this->get_field_id( "item_icon_$i" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "item_icon_$i" ) ); ?>" type="text" value="<?php echo esc_attr( $instance["item_icon_$i"] ); ?>" />
					</p>
					<?php
				}
		}
	}

endif;

if ( ! class_exists( 'Winsome_Facts_Widget' ) ) :

	/**
	 * Facts widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_Facts_Widget extends WP_Widget {

		function __construct() {
			$opts = array(
					'classname'   => 'winsome_widget_facts',
					'description' => esc_html__( 'Display Fact counters.', 'winsome' ),
			);
			parent::__construct( 'winsome-facts', esc_html__( 'Winsome: Facts', 'winsome' ), $opts );
		}

		function widget( $args, $instance ) {

			$section_id 		= !empty( $instance[ 'section_id' ] ) ? esc_attr( $instance[ 'section_id' ] ) : esc_attr( $this->id );


			$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$count_one			= !empty( $instance['count_one'] ) ? $instance['count_one'] : '';

			$count_two			= !empty( $instance['count_two'] ) ? $instance['count_two'] : '';

			$count_three 		= !empty( $instance['count_three'] ) ? $instance['count_three'] : '';

			$count_four			= !empty( $instance['count_four'] ) ? $instance['count_four'] : '';

			$bg_pic  	 		= ! empty( $instance['bg_pic'] ) ? esc_url( $instance['bg_pic'] ) : '';

			// Add background image.
			if ( ! empty( $bg_pic ) ) {
				$background_style = '';
				$background_style .= ' style="background-image:url(' . esc_url( $bg_pic ) . ');" ';
				$args['before_widget'] = implode( $background_style . ' ' . 'class="bg_enabled ', explode( 'class="', $args['before_widget'], 2 ) );
			}
		
			echo $args['before_widget']; ?>

			<div id="<?php echo esc_attr( $section_id ); ?>" class="some-facts">

				<div class="container">

					<?php 

					if ( $title ) {
						echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
					} ?>

					<?php 
					$fact_count = winsome_is_count_active( $count_one, $count_two, $count_three, $count_four );

					$fact_class = '';

					if( 2 == $fact_count ){

						$fact_class = 'fact-half';

					} elseif( 3 == $fact_count ){

						$fact_class = 'fact-third';

					} elseif( 4 == $fact_count ){

						$fact_class = 'fact-fourth';

					}else{

						$fact_class = 'fact-full';

					} ?>


					<div class="counter-wrapper <?php echo esc_attr( $fact_class ); ?>">
						<?php 

						$facts = array( 'one', 'two', 'three', 'four'); 

						foreach ($facts as $fact) {

							$counter_item 	= 'counter-'.$fact;
							
							$fact_item 		= !empty( $instance['count_'.$fact] ) ? $instance['count_'.$fact] : '';

							$fact_icon 		= !empty( $instance['icon_'.$fact] ) ? $instance['icon_'.$fact] : '';

							$fact_text 		= !empty( $instance['text_'.$fact] ) ? $instance['text_'.$fact] : '';  

							if( !empty( $fact_item ) ){ ?>

								<div class="counter-item <?php echo esc_attr( $counter_item ); ?>">
									<?php 
									if( !empty( $fact_icon ) ){ ?>
										<span class="count-icon">
											<i class="fa <?php echo esc_html( $fact_icon ); ?>"></i>
										</span>
										<?php 
									} ?>

									<?php 
									if( !empty( $fact_item ) ){ ?>
										<span class="count"><?php echo absint( $fact_item ); ?></span>
										<?php 
									} ?>

									<?php 
									if( !empty( $fact_text ) ){ ?>
										<span class="count-text"><?php echo esc_html( $fact_text ); ?></span>
										<?php 
									} ?>
								</div><!-- .counter-item -->

								<?php 

							}
						} ?>
					</div><!-- .counter-wrapper -->

				</div>

			</div><!-- .some-facts -->

			<?php 

			echo $args['after_widget'];

		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['section_id'] 		= sanitize_key( $new_instance[ 'section_id' ] );
			$instance['title'] 				= sanitize_text_field( $new_instance['title'] );

			$instance['icon_one'] 			= sanitize_text_field( $new_instance['icon_one'] );
			$instance['count_one'] 			= absint( $new_instance['count_one'] );
			$instance['text_one'] 			= sanitize_text_field( $new_instance['text_one'] );

			$instance['icon_two'] 			= sanitize_text_field( $new_instance['icon_two'] );
			$instance['count_two'] 			= absint( $new_instance['count_two'] );
			$instance['text_two'] 			= sanitize_text_field( $new_instance['text_two'] );

			$instance['icon_three'] 		= sanitize_text_field( $new_instance['icon_three'] );
			$instance['count_three'] 		= absint( $new_instance['count_three'] );
			$instance['text_three'] 		= sanitize_text_field( $new_instance['text_three'] );
			
			$instance['icon_four'] 			= sanitize_text_field( $new_instance['icon_four'] );
			$instance['count_four'] 		= absint( $new_instance['count_four'] );
			$instance['text_four'] 			= sanitize_text_field( $new_instance['text_four'] );

			$instance['bg_pic']  	 		= esc_url_raw( $new_instance['bg_pic'] );


			return $instance;
		}

		function form( $instance ) {

			// Defaults.
			$defaults = array(
				'section_id' 		=> '',
				'title' 			=> '',
				'icon_one'      	=> 'fa-folder-open-o',
				'count_one'			=> '',
				'text_one' 			=> '',
				'icon_two'      	=> 'fa-clock-o',
				'count_two'			=> '',
				'text_two' 			=> '',
				'icon_three'    	=> 'fa-users',
				'count_three'		=> '',
				'text_three' 		=> '',
				'icon_four'     	=> 'fa-trophy',
				'count_four'		=> '',
				'text_four' 		=> '',
				'bg_pic'      		=> '',
			);

			$instance = wp_parse_args( (array) $instance, $defaults );


			$icon_one			= !empty( $instance['icon_one'] ) ? $instance['icon_one'] : '';
			$count_one			= !empty( $instance['count_one'] ) ? $instance['count_one'] : '';
			$text_one			= !empty( $instance['text_one'] ) ? $instance['text_one'] : '';

			$icon_two			= !empty( $instance['icon_two'] ) ? $instance['icon_two'] : '';
			$count_two			= !empty( $instance['count_two'] ) ? $instance['count_two'] : '';
			$text_two			= !empty( $instance['text_two'] ) ? $instance['text_two'] : ''; 

			$icon_three 		= !empty( $instance['icon_three'] ) ? $instance['icon_three'] : '';
			$count_three 		= !empty( $instance['count_three'] ) ? $instance['count_three'] : '';
			$text_three 		= !empty( $instance['text_three'] ) ? $instance['text_three'] : ''; 

			$icon_four			= !empty( $instance['icon_four'] ) ? $instance['icon_four'] : '';
			$count_four			= !empty( $instance['count_four'] ) ? $instance['count_four'] : '';
			$text_four			= !empty( $instance['text_four'] ) ? $instance['text_four'] : '';

			$bg_pic = '';

            if ( ! empty( $instance['bg_pic'] ) ) {

                $bg_pic = $instance['bg_pic'];

            }

            $wrap_style = '';

            if ( empty( $bg_pic ) ) {

                $wrap_style = ' style="display:none;" ';
            }

            $image_status = false;

            if ( ! empty( $bg_pic ) ) {
                $image_status = true;
            }

            $delete_button = 'display:none;';

            if ( true === $image_status ) {
                $delete_button = 'display:inline-block;';
            }
			?>
			<p>
	        	<label for="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>"><strong><?php esc_html_e( 'Section ID:', 'winsome' ); ?></strong></label>
	        	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['section_id'] ); ?>" />
		        <small>
		        	<?php esc_html_e('Section ID needs to be unique. This id will be used in menu to enable one page scroll.', 'winsome'); ?>	
		        </small>
	        </p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<hr>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( "icon_one" ) ); ?>"><strong><?php esc_html_e( 'Icon One:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( "icon_one" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "icon_one" ) ); ?>" type="text" value="<?php echo esc_attr( $icon_one ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('count_one') ); ?>">
					<?php esc_html_e('Count One:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('count_one') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count_one') ); ?>" type="number" value="<?php echo absint( $count_one ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('text_one') ); ?>">
					<?php esc_html_e('Text One:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('text_one') ); ?>" name="<?php echo esc_attr( $this->get_field_name('text_one') ); ?>" type="text" value="<?php echo esc_attr( $text_one ); ?>" />		
			</p>

			<hr>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( "icon_two" ) ); ?>"><strong><?php esc_html_e( 'Icon Two:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( "icon_two" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "icon_two" ) ); ?>" type="text" value="<?php echo esc_attr( $icon_two ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('count_two') ); ?>">
					<?php esc_html_e('Count Two:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('count_two') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count_two') ); ?>" type="number" value="<?php echo absint( $count_two ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('text_two') ); ?>">
					<?php esc_html_e('Text Two:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('text_two') ); ?>" name="<?php echo esc_attr( $this->get_field_name('text_two') ); ?>" type="text" value="<?php echo esc_attr( $text_two ); ?>" />		
			</p>

			<hr>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( "icon_three" ) ); ?>"><strong><?php esc_html_e( 'Icon Three:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( "icon_three" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "icon_three" ) ); ?>" type="text" value="<?php echo esc_attr( $icon_three ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('count_three') ); ?>">
					<?php esc_html_e('Count Three:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('count_three') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count_three') ); ?>" type="number" value="<?php echo absint( $count_three ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('text_three') ); ?>">
					<?php esc_html_e('Text Three:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('text_three') ); ?>" name="<?php echo esc_attr( $this->get_field_name('text_three') ); ?>" type="text" value="<?php echo esc_attr( $text_three ); ?>" />		
			</p>

			<hr>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( "icon_four" ) ); ?>"><strong><?php esc_html_e( 'Icon Four:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( "icon_four" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "icon_four" ) ); ?>" type="text" value="<?php echo esc_attr( $icon_four ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('count_four') ); ?>">
					<?php esc_html_e('Count Four:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('count_four') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count_four') ); ?>" type="number" value="<?php echo absint( $count_four ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('text_four') ); ?>">
					<?php esc_html_e('Text Four:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('text_four') ); ?>" name="<?php echo esc_attr( $this->get_field_name('text_four') ); ?>" type="text" value="<?php echo esc_attr( $text_four ); ?>" />		
			</p>

			<div class="cover-image">
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>">
                    <strong><?php esc_html_e( 'Background Image:', 'winsome' ); ?></strong>
                </label>
                <input type="text" class="img widefat" name="<?php echo esc_attr( $this->get_field_name( 'bg_pic' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>" value="<?php echo esc_url( $instance['bg_pic'] ); ?>" />
                <div class="rtam-preview-wrap" <?php echo $wrap_style; ?>>
                    <img src="<?php echo esc_url( $bg_pic ); ?>" alt="<?php esc_attr_e( 'Preview', 'winsome' ); ?>" />
                </div><!-- .rtam-preview-wrap -->
                <input type="button" class="select-img button button-primary" value="<?php esc_html_e( 'Upload', 'winsome' ); ?>" data-uploader_title="<?php esc_html_e( 'Select Background Image', 'winsome' ); ?>" data-uploader_button_text="<?php esc_html_e( 'Choose Image', 'winsome' ); ?>" />
                <input type="button" value="<?php echo esc_attr_x( 'X', 'Remove Button', 'winsome' ); ?>" class="button button-secondary btn-image-remove" style="<?php echo esc_attr( $delete_button ); ?>" />
            </div>
					
		<?php
				
		}
	}

endif;

if ( ! class_exists( 'Winsome_Contact_Widget' ) ) :

	/**
	 * Contact widget class.
	 *
	 * @since 1.0.0
	 */
	class Winsome_Contact_Widget extends WP_Widget {

		function __construct() {
			$opts = array(
					'classname'   => 'winsome_widget_contact',
					'description' => esc_html__( 'Display Contact Us section.', 'winsome' ),
			);
			parent::__construct( 'winsome-contact', esc_html__( 'Winsome: Contact', 'winsome' ), $opts );
		}

		function widget( $args, $instance ) {

			$section_id 	= !empty( $instance[ 'section_id' ] ) ? esc_attr( $instance[ 'section_id' ] ) : esc_attr( $this->id );


			$title 			= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$contact_shortcode = !empty( $instance['contact_shortcode'] ) ? $instance['contact_shortcode'] : ''; 

			$contact_page 	= !empty( $instance['contact_page'] ) ? $instance['contact_page'] : ''; 

			$bg_pic  	 	= ! empty( $instance['bg_pic'] ) ? esc_url( $instance['bg_pic'] ) : '';

			// Add background image.
			if ( ! empty( $bg_pic ) ) {
				$background_style = '';
				$background_style .= ' style="background-image:url(' . esc_url( $bg_pic ) . ');" ';
				$args['before_widget'] = implode( $background_style . ' ' . 'class="bg_enabled ', explode( 'class="', $args['before_widget'], 2 ) );
			}
		
			echo $args['before_widget']; ?>

			<div id="<?php echo esc_attr( $section_id ); ?>" class="sec-contact">

				<div class="container">

					<?php 

					if ( $title ) {
						echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
					} ?>
					
					<div class="contact-wrapper">

						<?php if ( $contact_shortcode ) { ?>

							<div class="form-part contact-left">

								<?php echo do_shortcode( wp_kses_post( $contact_shortcode ) ); ?>

							</div>

						<?php } ?>

						<?php if ( $contact_page ) { 

							$contact_args = array(
											'posts_per_page' => 1,
											'page_id'	     => absint( $contact_page ),
											'post_type'      => 'page',
											'post_status'  	 => 'publish',
										);

							$contact_query = new WP_Query( $contact_args );	

							if( $contact_query->have_posts()){

								while( $contact_query->have_posts()){

									$contact_query->the_post(); ?>

									<div class="info-part contact-right">
										<?php the_content(); ?>
									</div>

									<?php

								}

								wp_reset_postdata();

							} ?>
							
						<?php } ?>

					</div><!-- .contact-wrapper -->

				</div>

			</div><!-- .contact -->

			<?php 

			echo $args['after_widget'];

		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['section_id'] 	= sanitize_key( $new_instance[ 'section_id' ] );
			$instance['title'] 			= sanitize_text_field( $new_instance['title'] );

			$instance['contact_shortcode'] = sanitize_text_field( $new_instance['contact_shortcode'] );
			$instance['contact_page'] 	= absint( $new_instance['contact_page'] );

			$instance['bg_pic']  	 	= esc_url_raw( $new_instance['bg_pic'] );


			return $instance;
		}

		function form( $instance ) {

			// Defaults.
			$defaults = array(
				'section_id' 		=> '',
				'title' 			=> '',
				'contact_shortcode' => '',
				'contact_page' 		=> '',
				'bg_pic'      		=> '',
			);

			$instance = wp_parse_args( (array) $instance, $defaults );


			$contact_shortcode = !empty( $instance['contact_shortcode'] ) ? $instance['contact_shortcode'] : '';

			$bg_pic = '';

			if ( ! empty( $instance['bg_pic'] ) ) {

			    $bg_pic = $instance['bg_pic'];

			}

			$wrap_style = '';

			if ( empty( $bg_pic ) ) {

			    $wrap_style = ' style="display:none;" ';
			}

			$image_status = false;

			if ( ! empty( $bg_pic ) ) {
			    $image_status = true;
			}

			$delete_button = 'display:none;';

			if ( true === $image_status ) {
			    $delete_button = 'display:inline-block;';
			}

			?>
			<p>
	        	<label for="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>"><strong><?php esc_html_e( 'Section ID:', 'winsome' ); ?></strong></label>
	        	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['section_id'] ); ?>" />
		        <small>
		        	<?php esc_html_e('Section ID needs to be unique. This id will be used in menu to enable one page scroll.', 'winsome'); ?>	
		        </small>
	        </p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'winsome' ); ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<hr>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_name('contact_shortcode') ); ?>">
					<?php esc_html_e('Form Shortcode:', 'winsome'); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('contact_shortcode') ); ?>" name="<?php echo esc_attr( $this->get_field_name('contact_shortcode') ); ?>" type="text" value="<?php echo esc_attr( $contact_shortcode ); ?>" />	
				<small>
		        	<?php esc_html_e('Shortcode of Contact Form 7, Gravity From, Wufoo Form and other form is supproted.', 'winsome'); ?>	
		        </small>	
			</p>

			<hr></hr>

			<p>
				<label for="<?php echo $this->get_field_id( 'contact_page' ); ?>">
					<strong><?php esc_html_e( 'Contact Page:', 'winsome' ); ?></strong>
				</label>
				<?php
				wp_dropdown_pages( array(
					'id'               => $this->get_field_id( 'contact_page' ),
					'class'            => 'widefat',
					'name'             => $this->get_field_name( 'contact_page' ),
					'selected'         => $instance[ 'contact_page' ],
					'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'winsome' ),
					)
				);
				?>
				<small>
		        	<?php esc_html_e('Content of this page will be used in right side of form', 'winsome'); ?>	
		        </small>
			</p>

			<hr>

			<div class="cover-image">
                <label for="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>">
                    <strong><?php esc_html_e( 'Background Image:', 'winsome' ); ?></strong>
                </label>
                <input type="text" class="img widefat" name="<?php echo esc_attr( $this->get_field_name( 'bg_pic' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'bg_pic' ) ); ?>" value="<?php echo esc_url( $instance['bg_pic'] ); ?>" />
                <div class="rtam-preview-wrap" <?php echo $wrap_style; ?>>
                    <img src="<?php echo esc_url( $bg_pic ); ?>" alt="<?php esc_attr_e( 'Preview', 'winsome' ); ?>" />
                </div><!-- .rtam-preview-wrap -->
                <input type="button" class="select-img button button-primary" value="<?php esc_html_e( 'Upload', 'winsome' ); ?>" data-uploader_title="<?php esc_html_e( 'Select Background Image', 'winsome' ); ?>" data-uploader_button_text="<?php esc_html_e( 'Choose Image', 'winsome' ); ?>" />
                <input type="button" value="<?php echo esc_attr_x( 'X', 'Remove Button', 'winsome' ); ?>" class="button button-secondary btn-image-remove" style="<?php echo esc_attr( $delete_button ); ?>" />
            </div>
					
		<?php
				
		}
	}

endif;


if ( ! function_exists( 'winsome_is_count_active' ) ) :

/**
 * Check if fact counter count is active.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 *
 * @return bool Whether the control is active to the current preview.
 */
function winsome_is_count_active( $count_one, $count_two, $count_three, $count_four ) {

	$total_count = 0; 

	if ( 0 < $count_one ) {

		$total_count += 1; 

	} 

	if( 0 < $count_two ) {

		$total_count += 1; 

	}

	if( 0 < $count_three ) {

		$total_count += 1; 

	}

	if( 0 < $count_four ) {

		$total_count += 1; 

	}

	return $total_count;

}

endif;