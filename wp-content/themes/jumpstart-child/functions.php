<?php
/*-------------------------------------------------------*/
/* Run Theme Blvd framework (required)
/*-------------------------------------------------------*/

require_once( get_template_directory() . '/framework/themeblvd.php' );

/*-------------------------------------------------------*/
/* Start Child Theme
/*-------------------------------------------------------*/

/**
 * Maintain options ID for saved options from parent
 * theme. (optional)
 *
 * This allows you to switch between parent and child theme,
 * with your theme options remaining saved to the same value
 * in your WordPress database.
 */
function jumpstart_option_id( $id ) {
    return 'jumpstart';
}
add_filter('themeblvd_option_id', 'jumpstart_option_id');


/* Remove Scroll Effects */

function my_global_config( $setup ) {
    $setup['display']['scroll_effects'] = false;
    return $setup;
}
add_filter('themeblvd_global_config', 'my_global_config');


/*** Remove Rev Slider Metabox */
if ( is_admin() ) {

	function remove_revolution_slider_meta_boxes() {
		remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'slide', 'normal' );
                remove_meta_box( 'mymetabox_revslider_0', 'lscontentblock', 'normal' );
                remove_meta_box( 'mymetabox_revslider_0', 'product', 'normal' );                
	}

	add_action( 'do_meta_boxes', 'remove_revolution_slider_meta_boxes' );
	
}

/*** Remove text editor for products */

function remove_product_editor() {
  remove_post_type_support( 'product', 'editor' );
  remove_post_type_support( 'product', 'permalink' );
}
add_action( 'init', 'remove_product_editor' );
