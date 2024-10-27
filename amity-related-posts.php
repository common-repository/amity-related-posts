<?php
/*
Plugin Name: Amity Related Posts 
Plugin URI: http://amitythemes.com
Description: Amity Related Posts Lite is a WordPress Plugin that appears under each post, linking to related posts from your website/blog archive. It helps you to show other articles in the same category.
Version: 1.4
Author: Amity Themess
Author URI: http://amitythemes.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: arp


Copyright 2015  Amity Themes  (email : info@amitytheme.com)
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* 
*	All GLOBAL PATHS
*
* @since      1.0
* @package    Amity Related Posts Lite
* @author     Amity Themes <amitytheme@gmail.com>
*/

// URL or DIR PATHS
define( 'ARP_PLUGIN_PATH'		,	plugin_dir_url(  __FILE__  ) );						// PLUGIN PATH
define( 'ARP_PLUGIN_DIR'		,	dirname( __FILE__ ) . '/' );						// PLUGIN DIRECTORY

//FOLDER PATHS
define( 'ARP_CLASSES_FOLDER'	,	ARP_PLUGIN_DIR		. 'classes/');					// CLASSES FOLDER
define( 'ARP_ADMIN_FOLDER'		,	ARP_PLUGIN_DIR		. 'admin/');					// ADMIN FOLDER
define( 'ARP_INC_FOLDER'		,	ARP_PLUGIN_DIR		. 'inc/');					// ADMIN FOLDER


// Admin Settings
if (is_admin()) {
	require_once(ARP_ADMIN_FOLDER . 'class.settings-api.php');
	require_once(ARP_ADMIN_FOLDER . 'amity-settings-api.php');
}


// Call WordPress jQuery
function amity_related_posts_jquery() {
	wp_enqueue_script('jquery' );
}

add_action('init', 'amity_related_posts_jquery' );

// Call all assets files
function amity_related_posts_style(){
	if (!is_admin()) {
		wp_register_style('amity_related_posts_css', plugins_url('/assets/css/amity-related-posts.css', __FILE__ ), '', '1.0', false);
		wp_enqueue_style('amity_related_posts_css');
	}
}

add_action('init', 'amity_related_posts_style', 20);


//Get the value of a settings field
function arp_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}

/* 
* the_content filter hook 
*
* @since      1.0
* @package    Amity Related Posts Lite
* @author     Amity Themes <amitytheme@gmail.com>
*/

add_filter('the_content', 'amity_related_posts_content');

function amity_related_posts_content($content) {
	//enable and disable mode.
	global $active_mode;

	//if it's not a single post, than ignore
	if(!is_singular('post')) {
		return $content;
	}

	remove_filter('the_content', 'amity_related_posts_content');

	//enable and disable mode.
	$active_mode = 1;

	if (!$active_mode = arp_get_option('arp-active-mode', 'arp_basics', 1)) {
		return $content;
	}

	//Get Post Category from Singular Post.
	$categories = get_the_terms(get_the_ID(), 'category');
	$categoriesIds = array();

	foreach ($categories as $category) {
		$categoriesIds[] = $category->term_id;
	}

	//Query
	$args = array(
			'category_in'      => $categoriesIds,
			'posts_per_page'   => arp_get_option( 'arp-no-posts', 'arp_basics', 3),
			'post__not_in'     => array(get_the_ID()),
			'orderby'          => 'rand'
		);

	$loop = new WP_Query($args);

	if (in_the_loop()) {

		$content .= '<div class="amity-related-posts">';

		$content .= '<h2 class="arp-main-title">'.arp_get_option('arp-main-title', 'arp_basics', '').'</h2>';

		while($loop->have_posts()) { $loop->the_post();

			//Show and Hide Mode
			$show_hide = 1;

			// Get Post Id
			$post_id = get_the_id();

			//Title
			$titleText = get_the_title();
			$titleLenght = arp_get_option('arp-title-length', 'arp_styles', 999);

			// Excerpt Varieable
			$text = get_the_excerpt();
			$textLenght = arp_get_option('arp-excerpt-length', 'arp_styles', 20);

			// Grid/List View
			$grid_list = arp_get_option( 'arp-grid-list', 'arp_basics', 'grid');
			$col = arp_get_option( 'arp-col-style', 'arp_basics', 'col-3');

			// Read More BTN
			$rm_text = arp_get_option('arp-read-more-text', 'arp_styles', 'Read More &#8594;');
			$rm_style = arp_get_option('arp-read-more-style', 'arp_styles', 'p_text');
			
			$content .= '<div class="'.$grid_list.' '.$col.'"> <div class="inner-box">';

			$content .= '<div class="amity-related-posts-thumb"> <a href="'.get_permalink().'">';
			$content .= get_the_post_thumbnail( $post_id, 'full' );
			$content .= '</a></div>';

			$content .= '<div class="arp-content-box"><div class="arp-post-meta">';

		if (!$show_hide = arp_get_option('arp-author', 'arp_basics', 0)) {
			$content .= '<span class="arp-author">'.get_the_author().'</span>';
		}

		if (!$show_hide = arp_get_option('arp-date', 'arp_basics', 0)) {
			$content .= '<span class="arp-date">'.get_the_time('F j, Y', $post_id).'</span>';
		}
			$content .= '</div>';
			$content .= '<h3 class="arp-post-title"><a href="'.get_permalink().'">'.wp_trim_words($titleText, $titleLenght, '').'</a></h3>';

		if (!$show_hide = arp_get_option('arp-excerpt', 'arp_basics', 0))  {

			$content .= '<p class="arp-text-content">';
			$content .= wp_trim_words ($text, $textLenght, ' ...'); // Trme Text
			$content .= '</p>';
		}

		if (!$show_hide = arp_get_option('arp-read-more', 'arp_basics', 0))  {
			$content .= '<p class="read-more"><a class="'.$rm_style.'" href="'.get_permalink().'">'.$rm_text.'</a></p>';
		}
			$content .= '</div></div></div>'; //end box
		}

		$content .='</div>';

	//Reset Query Data
	wp_reset_query();

	}  
		
		add_filter('the_content', 'amity_related_posts_content');

		return $content;
}

/* Style CSS file
*
* @since      1.0
* @package    Amity Related Posts Lite
* @author     Amity Themes <amitytheme@gmail.com>
*/
function arp_options_style() {
	include_once(ARP_INC_FOLDER . 'style-css.php');
}

add_action('wp_head', 'arp_options_style' );
