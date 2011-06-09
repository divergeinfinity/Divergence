<?php
/**
 *	This function.php file is used to initialize everything in the theme.  It controls how the theme is
 *	loaded and sets up the supported features, default actions, and default filters.
 *
 *	This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 *	General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 *	that you can use any other version of the GPL.
 *
 *	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 *	even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *	You should have received a copy of the GNU General Public License along with this program; if not,
 *	write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 *	@package Divergence
 *	@subpackage Functions
 *	@version 1.0
 *	@author Jeff Parsons <jeffrey.allen.parsons@gmail.com>
 *	@copyright Copyright (c) 2011, Jeff Parsons
 *	@link http://diverge.blogdns.com
 *	@license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/**
 *	Global DEBUGGING:
 *	The 'logme' function is useful for displaying messages and variable or array contents
 *	during development.  The messages are only displayed if the WP_DEBUG is set to true.
 */
function logme( $message )
{
    if ( WP_DEBUG === true )  // only spit out messages if WordPress Debugging is enabled
    {
        if ( is_array( $message ) || is_object( $message ) )
        {
            error_log( print_r( $message, true ) );
        }
        else
        {
            error_log( $message );
        }
    }
}
logme( "\n\nBEGINNING of Divergence Functions.php" );
/**
 *	The 'all' hook fires for all actions and filters on every page load.
 *	Good for troubleshooting and identifying the right hook.
 *	Uncomment to use:
 */
//add_action( 'all', create_function( '', 'logme( current_filter() );' ) );


/*---------------------------------------------------------------------------*/
//	TOPF = Theme Options Panel Framework
//	Define our Database Options table entry name REQUIRED by TOPF
//	and then load the Theme Options Panel Framework
/*---------------------------------------------------------------------------*/
// the name of our WordPress options table entry (no spaces) [required by options.php]
define( 'TOPF_OPTIONS', 'theme_divergence_options' );
require_once( get_stylesheet_directory() . '/options/options.php' );
require_once( get_stylesheet_directory() . '/options/options-implement.php' );

// 'smarter nav' was a plugin that forces WordPress to navigate single pages by category
// or tag only when using next or previous post, not every post from all categories
require_once( get_stylesheet_directory() . '/library/smarter-navigation/smarter-nav.php' );

/* we are ready to go */

/*---------------------------------------------------------------------------*/
//  				Custom Child Theme Functions
/*---------------------------------------------------------------------------*/


/*---------------------------------------------------------------------------*/
//  Remove the Options Panel that Thematic comes with by Default
/*---------------------------------------------------------------------------*/
function remove_thematic_panel()
{
	remove_action( 'admin_menu', 'mytheme_add_admin' );
}
add_action( 'init', 'remove_thematic_panel' );


/*---------------------------------------------------------------------------*/
//  Add the latest JQuery and JQuery UI and other JavaScript libs
//
//	We'll hook into 'template_redirect' instead of further up the action chain such as 'init'
//	and then we won't have to check for is_admin().  Changing JQuery in particular can
//	break the visual/html editor on writing in the Admin.
/*---------------------------------------------------------------------------*/
function dti_upgrade_jquery()
{
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js', false, '1.6' );
	wp_enqueue_script( 'jquery' );
}
add_action( 'template_redirect', 'dti_upgrade_jquery' );

/**
 *	Do the same for JQuery UI
 */
function dti_upgrade_jquery_ui()
{
	wp_deregister_script( 'jquery-ui' );
	wp_register_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js', array( 'jquery' ), '1.8.12' );
	wp_enqueue_script( 'jquery-ui' );
}
add_action( 'template_redirect', 'dti_upgrade_jquery_ui' );

/**
 *	Load all other Javascript libraries
 */
function dti_load_other_js()
{
    wp_register_script( 'jquery-tools', 'http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js', array( 'jquery' ), '1.2.5' );
    wp_enqueue_script( 'jquery-tools' );

	wp_enqueue_style( 'qtip-style', get_stylesheet_directory_uri() . '/library/js/qtip/jquery.qtip.min.css' );
    wp_register_script( 'qtip', get_stylesheet_directory_uri() . '/library/js/qtip/jquery.qtip.min.js', array ( 'jquery' ), '2.0' );
    wp_enqueue_script( 'qtip' );
}
add_action( 'template_redirect', 'dti_load_other_js' );


/*---------------------------------------------------------------------------*/
//  Add our own javascript code to the Head
/*---------------------------------------------------------------------------*/
function childtheme_scripts()
{
?>
	<script type="text/javascript" language="javascript">
	/*<![CDATA[*/
		jQuery.noConflict();
		jQuery(document).ready( function($)
		{
			/* qtip */
			$('a[title]').qtip( {
				position: {
					my: 'top center',
					at: 'bottom center'
				},
				style: {
					classes: 'tooltip ui-tooltip-jtools'
				}
            });
            /* jquery tools
            $('a[title]').tooltip( {
            	effect: 'fade',
            	position: 'bottom center'
            });*/
		});
	/*]]>*/
	</script>
<?php
}
add_action( 'wp_head', 'childtheme_scripts' );


/*---------------------------------------------------------------------------*/
//  enable wp_title() for use with Headspace2 and other plugins using
//  this hook. Also can remove meta descriptions and robots.
/*---------------------------------------------------------------------------*/
function child_headspace_doctitle()
{
     $elements = wp_title( '', false );
     return $elements;
}
global $topf_options;
if ( $topf_options['disabledoctitle'] )
	add_filter( 'thematic_doctitle', 'child_headspace_doctitle' );
else
	remove_filter( 'thematic_doctitle', 'child_headspace_doctitle' );

function child_meta_head_cleaning()
{
     $display = FALSE;
     return $display;
}
// thematic master switch uncomment to remove both meta robots and meta description
// comment out the line below and uncomment one of the following lines for a modular removal
// add_filter( 'thematic_seo', 'child_meta_head_cleaning' );

// uncomment to remove thematic meta descrption
//add_filter( 'thematic_show_description', 'child_meta_head_cleaning' );

// uncomment to remove thematic meta robots
//add_filter( 'thematic_show_robots', 'child_meta_head_cleaning' );


/*---------------------------------------------------------------------------*/
//  Add support for WordPress post formats
/*---------------------------------------------------------------------------*/
function childtheme_post_format_class( $classes = array() )
{
	$format = get_post_format();
	if ( '' == $format )
		$format = 'standard';

	$classes[] = 'format-' . $format;

	return $classes;
}
add_filter( 'post_class', 'childtheme_post_format_class' );
add_theme_support( 'post-formats', array( 'status' ) );


/*---------------------------------------------------------------------------*/
//  Only use excerpts on the Home page with Option for Full First Post
/*---------------------------------------------------------------------------*/
function childtheme_content( $content )
{
	global $topf_options, $post, $posts;

	$format = get_post_format();
	if ( false === $format ) $format = 'standard';

	if ( ! is_single() )
	{
		if ( $format == 'standard' )
		{
			if ( $topf_options['displayexcerpts'] && ($post <> $posts[0]) )
			{
				$content = 'excerpt';
			}
			else if ( $post == $posts[0] )
			{
				if ( $topf_options['displayfullfirst'] && (get_query_var('paged') == 0) && is_home() )
					$content = 'full';
				else if ( $topf_options['displayexcerpts'] )
				{
					$content = 'excerpt';
				}
				else
					$content = 'full';
			}
			else if ( $topf_options['displayexcerpts'] )
				$content = 'excerpt';
			else
				$content = 'full';

		}
		else if ( $format == 'status' )
			$content = 'full';
		else
			$content = 'full';
	}
	return $content;
}
add_filter( 'thematic_content', 'childtheme_content' );


/*---------------------------------------------------------------------------*/
//  Provide the excerpt and Read More text
/*---------------------------------------------------------------------------*/
function childtheme_excerpt( $excerpt )
{
	return get_the_excerpt() . '<span class="readmore"><a href="' . get_permalink() . '" title="Permalink to ' . get_the_title() . '">Read More &raquo;</a></span>';
}
add_filter( 'the_excerpt', 'childtheme_excerpt' );


/*---------------------------------------------------------------------------*/
//  Add a class to first Home page post
//  (Another Way)
//  1. If you have enabled Thematic post class in functions: i.e. unchecking the line
//  define('THEMATIC_COMPATIBLE_POST_CLASS', true), then each post will have a class added
//  .p1, .p2, etc. That then would be a simple task of styling the post in stylesheet with
//  .p1 {background....}.
/*---------------------------------------------------------------------------*/
function add_firstpost_class( $class )
{
	global $post, $posts;

	if ( is_home() && (get_query_var('paged') == 0) && ($post == $posts[0]) )
		$class[] = 'firstindexpost';
	return $class;
}
add_filter( 'post_class', 'add_firstpost_class' );


/*---------------------------------------------------------------------------*/
//  Override Post Header meta to only show author and post date when it
//	is selected in the Theme Options Panel Framework
/*---------------------------------------------------------------------------*/
function childtheme_override_postheader_meta( $meta )
{
	global $topf_options;

	$ourmeta = "";
	$format = get_post_format();
	if ( false === $format ) $format = 'standard';

	if ( $format == 'standard' && (($topf_options['displaymeta'] <> false) || is_single()) )
	{
	    $ourmeta = '<div class="entry-meta">';
	    $ourmeta .= thematic_postmeta_authorlink();
	    $ourmeta .= '<span class="meta-sep meta-sep-entry-date"> | </span>';
	    $ourmeta .= thematic_postmeta_entrydate();
	    $ourmeta .= thematic_postmeta_editlink();
	    $ourmeta .= "</div><!-- .entry-meta -->\n";
	}
	return $ourmeta;
}
add_filter( 'thematic_postheader_postmeta', 'childtheme_override_postheader_meta' );


/*---------------------------------------------------------------------------*/
//  Override Post Title to insert our Calendar except on single pages and
//	when enabled in the Theme Options Panel Framework
/*---------------------------------------------------------------------------*/
function childtheme_postheader_posttitle()
{
	global $topf_options;

	$posttitle = "";
	$format = get_post_format();

	if ( false === $format ) $format = 'standard';

	if ( $format == 'standard' )
	{
		if ( is_page() )
		{
			$posttitle .= '<h1 class="entry-title">' . get_the_title() . "</h1>\n";
		}
		elseif ( is_404() )
		{
			$posttitle .= '<h1 class="entry-title">' . __('Not Found', 'thematic') . "</h1>\n";
		}
		else
		{
			if ( $topf_options['displaycalendar'] && ! is_single() )
				$posttitle .= '<div class="calendar">
					<span class="month">' . get_the_time('M') . '</span>
					<span class="day">' . get_the_time('j') . '</span></div>'."\n";
			$posttitle .= '<h1 class="entry-title">';
			/* don't show links for the Title on single pages */
			if ( ! is_single() )
			{
				$posttitle .= '<a href="';
				$posttitle .= apply_filters( 'the_permalink', get_permalink() );
				$posttitle .= '" title="';
				$posttitle .= __('Permalink to ', 'thematic') . the_title_attribute('echo=0');
				$posttitle .= '" rel="bookmark">';
			}
			$posttitle .= get_the_title();
			if ( !is_single() )
			{
				$posttitle .= "</a>";
			}
			$posttitle .= "</h1>\n";
		}
	}
	elseif ( $format == 'status' )
	{
		if ( $topf_options['displaycalendar'] )
			$posttitle .= '<div class="calendar">
				<span class="month">' . get_the_time('M') . '</span>
				<span class="day">' . get_the_time('j') . '</span></div>'."\n";
	}
	return $posttitle;
}
add_filter( 'thematic_postheader_posttitle', 'childtheme_postheader_posttitle' );


/*---------------------------------------------------------------------------*/
//  Remove the default loop for index and replace
//  with our custom version - (thematic/library/extensions/content_extensions.php)
//	We do this to count posts and insert a widget based on the Options Framework
//	Admin panel setting
/*---------------------------------------------------------------------------*/
function remove_thematic_index_loop()
{
      remove_action( 'thematic_indexloop', 'thematic_index_loop' );
}
add_action( 'init', 'remove_thematic_index_loop' );

function childtheme_index__loop()
{
	global $topf_options;

	/* Count the number of posts so we can insert a widgetized area */
	$count = 1;

	while ( have_posts() ) : the_post();

		thematic_abovepost();
		?>

		<div id="post-<?php the_ID();
			echo '" ';
			if ( ! (THEMATIC_COMPATIBLE_POST_CLASS) )
			{
				post_class();
				echo '>';
			}
			else
			{
				echo 'class="';
				thematic_post_class();
				echo '">';
			}
			thematic_postheader(); ?>
			<div class="entry-content">
				<?php thematic_content(); ?>
				<?php wp_link_pages('before=<div class="page-link">' .__('Pages:', 'thematic') . '&after=</div>') ?>
			</div><!-- .entry-content -->
			<?php thematic_postfooter(); ?>
		</div><!-- #post -->

		<?php

		thematic_belowpost();

		comments_template();

		if ( $count == $topf_options['home_aside_insert'] )
		{
			get_sidebar( 'index-insert' );
		}
		$count++;
	endwhile;

}
add_action( 'thematic_indexloop', 'childtheme_index__loop' );


/*---------------------------------------------------------------------------*/
//  Adds a graphic post divider to the post footer
/*---------------------------------------------------------------------------*/
function childtheme_postfooter( $footer )
{
	global $topf_options;
	global $posts;
	global $post;
	$post_footer = "";

	if ( is_single() )
	{
		if ( $topf_options['postdivider']['style'] <> 'none' )
		{
			$postfooter = '<div class="post-divider" style="border: '.$topf_options['postdivider']['width'].'px '.$topf_options['postdivider']['style'].' #'.$topf_options['postdivider']['color'].';">&nbsp;</div>';
		}
		else
			$postfooter = '<div style="padding-bottom:36px;">&nbsp;</div>';
	}
	else
	{
		if ( (get_query_var('paged') == 0) && ($post == $posts[0]) && $topf_options['hilitefirst'] )
			$postfooter = '<div style="padding-bottom: 9px;">&nbsp;</div>';
		else if ( $topf_options['postdivider']['style'] <> 'none' )
			$postfooter = '<div class="post-divider" style="border: '.$topf_options['postdivider']['width'].'px '.$topf_options['postdivider']['style'].' #'.$topf_options['postdivider']['color'].';">&nbsp;</div>';
		else
			$postfooter = '<div style="padding-bottom:36px;">&nbsp;</div>';
	}

	return $postfooter;
}
add_filter( 'thematic_postfooter', 'childtheme_postfooter' );


/*---------------------------------------------------------------------------*/
//	Add Permalink shortcode for posts and pages
//	Usage: [permalink id="post or page ID" text="usage text for link"]
/*---------------------------------------------------------------------------*/
function do_permalink( $atts )
{
    extract( shortcode_atts( array(
        'id'	=> 1,
        'text'	=> ""  // default value if none supplied
    ), $atts ));

    if ( $text )
    {
        $url = get_permalink( $id );
        $title = get_the_title( $id );
        return "<a href='$url' title='Permalink to $title'>$text</a>";
    }
    else
    {
        $url = get_permalink( $id );
        $title = get_the_title( $id );
        return "<a href='$url' title='Permalink to $title'>$title</a>";
    }
}
add_shortcode( 'permalink', 'do_permalink' );


/*---------------------------------------------------------------------------*/
//	Add bookmark shortcode for links stored in the Link Manager
//	Usage: [bookmark id="link ID" text="usage text for link"]
/*---------------------------------------------------------------------------*/
function do_bookmark( $atts )
{
    extract( shortcode_atts( array(
        'id'	=> 1,
        'text'	=> ""  // default value if none supplied
    ), $atts ));

    if ( $text )
    {
		$bm = get_bookmark( (int)$id );
		return "<a href='$bm->link_url' title='External link to: $bm->link_name -- $bm->link_description'>$text</a>";
    }
    else	// if no text supplied, use the link name from the Link Manager
    {
		$bm = get_bookmark( (int)$id );
		return "<a href='$bm->link_url' title='External link to: $bm->link_name -- $bm->link_description'>$bm->link_name</a>";
    }
}
add_shortcode( 'bookmark', 'do_bookmark' );


/*---------------------------------------------------------------------------*/
//  Adds a Widget area above the Header (thematic_aboveheader())
/*---------------------------------------------------------------------------*/
function add_above_header_aside( $content )
{
	$content['Above Header Aside'] = array(
		'admin_menu_order' => 1,
		'args' => array (
			'name'			=> 'Above Header Aside',
			'id'			=> 'above-header-aside',
			'description' => __( 'A widget area above the Header.' ),
			'before_widget' => thematic_before_widget(),
			'after_widget'  => thematic_after_widget(),
			'before_title'  => thematic_before_title(),
			'after_title'   => thematic_after_title(),
		),
		'action_hook'   => 'thematic_aboveheader',
		'function'      => 'thematic_above_header_aside',
		'priority'      => 10,
	);
	return $content;
}
add_filter( 'thematic_widgetized_areas', 'add_above_header_aside' );

// And this is our new function that displays the widgetized area
function thematic_above_header_aside()
{
	if ( is_active_sidebar( 'above-header-aside' ) )
	{
		echo thematic_before_widget_area( 'above-header-aside' );
		dynamic_sidebar( 'above-header-aside' );
		echo thematic_after_widget_area( 'above-header-aside' );
	}
}


/*---------------------------------------------------------------------------*/
//  Adds a Widget area in the Header (thematic_header())
//	** Altered to overide childtheme_override_brandingclose()
/*---------------------------------------------------------------------------*/
function add_header_aside( $content )
{
	$content['Header Aside'] = array(
		'admin_menu_order' => 2,
		'args' => array (
			'name'			=> 'Header Aside',
			'id'			=> 'header-aside',
			'description' => __( 'A widget area within the Header.' ),
			'before_widget' => thematic_before_widget(),
			'after_widget'  => thematic_after_widget(),
			'before_title'  => thematic_before_title(),
			'after_title'   => thematic_after_title(),
		),
		'action_hook'   => '',/* don't hook in thematic-header */
		'function'      => 'childtheme_override_brandingclose',
		'priority'      => 10,
	);
	return $content;
}
add_filter( 'thematic_widgetized_areas', 'add_header_aside' );

// And this is our new function that displays the widgetized area
//** altered from - function thematic_header_aside()
function childtheme_override_brandingclose()
{
	if ( is_active_sidebar( 'header-aside' ) )
	{
		echo thematic_before_widget_area( 'header-aside' );
		dynamic_sidebar( 'header-aside' );
		echo thematic_after_widget_area( 'header-aside' );
	}
	echo "\t\t</div><!--  #branding -->\n";
}


/*---------------------------------------------------------------------------*/
//  Adds a Widget area in the Above Container area ( thematic_abovecontainer() )
/*---------------------------------------------------------------------------*/
function add_above_container_aside( $content )
{
	$content['Above Container Aside'] = array(
		'admin_menu_order' => 3,
		'args' => array (
			'name'			=> 'Above Container Aside',
			'id'			=> 'above-container-aside',
			'description' => __( 'A widget area just below the Main Menu, above everything else.  Suitable for adds or whatever.' ),
			'before_widget' => thematic_before_widget(),
			'after_widget'  => thematic_after_widget(),
			'before_title'  => thematic_before_title(),
			'after_title'   => thematic_after_title(),
		),
		'action_hook'   => 'thematic_abovecontainer',
		'function'      => 'thematic_above_container_aside',
		'priority'      => 1,
	);
	return $content;
}
add_filter( 'thematic_widgetized_areas', 'add_above_container_aside' );

// And this is our new function that displays the widgetized area
function thematic_above_container_aside()
{
	if ( is_active_sidebar( 'above-container-aside' ))
	{
		echo thematic_before_widget_area( 'above-container-aside' );
		dynamic_sidebar( 'above-container-aside' );
		echo thematic_after_widget_area( 'above-container-aside' );
	}
}


/*---------------------------------------------------------------------------*/
//  Adds a Widget area in the Above Content area ( thematic_abovecontent() )
/*---------------------------------------------------------------------------*/
function add_above_content_aside( $content )
{
	$content['Above Content Aside'] = array(
		'admin_menu_order' => 4,
		'args' => array (
			'name'			=> 'Above Content Aside',
			'id'			=> 'above-content-aside',
			'description' => __( 'A widget area above the main content area.  Suitable for Breadcrumb Navigation etc.' ),
			'before_widget' => thematic_before_widget(),
			'after_widget'  => thematic_after_widget(),
			'before_title'  => thematic_before_title(),
			'after_title'   => thematic_after_title(),
		),
		'action_hook'   => 'thematic_abovecontent',
		'function'      => 'thematic_above_content_aside',
		'priority'      => 1,
	);
	return $content;
}
add_filter( 'thematic_widgetized_areas', 'add_above_content_aside' );

// And this is our new function that displays the widgetized area
function thematic_above_content_aside()
{
	if ( is_active_sidebar( 'above-content-aside' ))
	{
		echo thematic_before_widget_area( 'above-content-aside' );
		dynamic_sidebar( 'above-content-aside' );
		echo thematic_after_widget_area( 'above-content-aside' );
	}
}


/*---------------------------------------------------------------------------*/
//  Adds opening and closing <div> tags to wrap #header
/*---------------------------------------------------------------------------*/
function childtheme_above_header()
{
	echo( '<div id="headerwrap">' );
}
add_action( 'thematic_aboveheader', 'childtheme_above_header' );

function childtheme_below_header()
{
	echo( '</div><!-- #headerwrap -->' );
}
add_action( 'thematic_belowheader', 'childtheme_below_header' );


/*---------------------------------------------------------------------------*/
//  Override Thematic next and previous post links with Smarter Navigation plugin
/*---------------------------------------------------------------------------*/
function childtheme_override_next_post_link()
{
	if ( function_exists( 'next_post_smart' ) )
		next_post_smart();
}
add_action( 'thematic_next_post_link', 'childtheme_override_next_post_link' );

function childtheme_override_previous_post_link()
{
	if ( function_exists( 'previous_post_smart' ) )
		previous_post_smart();
}
add_action( 'thematic_previous_post_link', 'childtheme_override_previous_post_link' );


/*---------------------------------------------------------------------------*/
//  Unleash the power of Thematic's dynamic classes
/*---------------------------------------------------------------------------*/

define( 'THEMATIC_COMPATIBLE_BODY_CLASS', true );
define( 'THEMATIC_COMPATIBLE_POST_CLASS', true );

// Unleash the power of Thematic's comment form

define( 'THEMATIC_COMPATIBLE_COMMENT_HANDLING', true);
define( 'THEMATIC_COMPATIBLE_COMMENT_FORM', true );

// Unleash the power of Thematic's feed link functions

define( 'THEMATIC_COMPATIBLE_FEEDLINKS', true );


?>