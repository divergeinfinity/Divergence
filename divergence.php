<?php
/**
 *	Template Name: Divergence
 *
 *	This is the Page Template for displaying the Divergence Theme page
 */

function add_head_styles()
{
	$handle = 'nivo-slider';
	$stylesheet = get_stylesheet_directory_uri() . '/library/js/nivo-slider/nivo-slider.css';
	wp_enqueue_style( $handle, $stylesheet );
	$handle = 'dti_gallery_nivo-slider';
	$stylesheet = home_url() . '/wp-content/divergence_theme_gallery/dti_nivo-slider_style.css';
	wp_enqueue_style( $handle, $stylesheet );
}
add_action( 'wp_print_styles', 'add_head_styles' );

function add_head_js()
{
    wp_register_script( 'nivo-slider', get_stylesheet_directory_uri() . '/library/js/nivo-slider/jquery.nivo.slider.pack.js', array ( 'jquery' ), '2.5.2' );
    wp_enqueue_script( 'nivo-slider' );
    ?>
	<?php
}
add_action( 'wp_print_scripts', 'add_head_js' );

    // calling the header.php
    get_header();

    // action hook for placing content above #container
    thematic_abovecontainer();

?>

		<div id="container">

			<?php thematic_abovecontent();

			echo apply_filters( 'thematic_open_id_content', '<div id="content">' . "\n" );

				// calling the widget area 'page-top'
	            get_sidebar('page-top');

	            the_post();

	            thematic_abovepost();

	            ?>

				<div id="post-<?php the_ID();
					echo '" ';
					if (!(THEMATIC_COMPATIBLE_POST_CLASS)) {
						post_class();
						echo '>';
					} else {
						echo 'class="';
						thematic_post_class();
						echo '">';
					}

	                // creating the post header
	                //thematic_postheader();

	                ?>

					<div class="entry-content">

						<div id="slider-wrapper">

							<div id="slider" class="nivoSlider">
								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot1.png'; ?>
								<img src="<?php echo $source ?>" alt="" />

								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot2.png'; ?>
								<img src="<?php echo $source ?>" alt="" />

								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot3.png'; ?>
								<img src="<?php echo $source ?>" alt="" />

								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot4.png'; ?>
								<img src="<?php echo $source ?>" alt="" />

								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot5.png'; ?>
								<img src="<?php echo $source ?>" alt="" />

								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot6.png'; ?>
								<img src="<?php echo $source ?>" alt="" />

								<?php $source = home_url().'/wp-content/divergence_theme_gallery/screenshot7.png'; ?>
								<img src="<?php echo $source ?>" alt="" />
							</div>
							<!--div id="htmlcaption" class="nivo-html-caption">
								<strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>.
							</div-->
							<script type="text/javascript">
							/*<![CDATA[*/
							jQuery(window).load(function()
							{
								jQuery('#slider').nivoSlider();
							});
							/*]]>*/
							</script>

						</div>

						<?php

	                    the_content();
	                    wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
	                    edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>

					</div><!-- .entry-content -->
				</div><!-- #post -->

	        <?php

	        thematic_belowpost();

	        // calling the comments template
       		if (THEMATIC_COMPATIBLE_COMMENT_HANDLING) {
				if ( get_post_custom_values('comments') ) {
					// Add a key/value of "comments" to enable comments on pages!
					thematic_comments_template();
				}
			} else {
				thematic_comments_template();
			}

	        // calling the widget area 'page-bottom'
	        get_sidebar('page-bottom');

	        ?>

			</div><!-- #content -->

			<?php thematic_belowcontent(); ?>

		</div><!-- #container -->

<?php

    // action hook for placing content below #container
    thematic_belowcontainer();

    // calling the standard sidebar
    thematic_sidebar();

    // calling footer.php
    get_footer();

?>