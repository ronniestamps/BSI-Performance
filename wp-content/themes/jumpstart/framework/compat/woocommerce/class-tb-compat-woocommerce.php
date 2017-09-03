<?php
/**
 * Add extended WooCommerce compatibility
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Compat_WooCommerce {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_Compat_bbPress A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		/**
		 * Add any WooCommerce theme support features.
		 */

		add_action( 'init', array($this, 'add_theme_support'), 5 );

		/**
		 * Assets
		 */

		// Remove all WooCommerce stylsheets
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

		// Add framework WooCommerce stylesheets/scripts
		add_action( 'wp_enqueue_scripts', array($this, 'assets'), 15 );
		add_filter( 'themeblvd_framework_stylesheets', array($this, 'add_style') );

		// Add any body classes, needed for styling
		add_filter( 'body_class', array($this, 'body_class') );

		/**
		 * Templates
		 */
		add_filter( 'wc_get_template_part', array($this, 'product_template'), 10, 3 );
		add_filter( 'woocommerce_locate_template', array($this, 'templates'), 10, 3 );

		/**
		 * Thumbnails
		 */

		// Thumb sizes
		if ( apply_filters('themeblvd_woocommerce_images', true) ) {

			add_image_size( 'shop_thumbnail', '200', '200', 1 );
			add_image_size( 'shop_catalog', '800', '800', 1 );
			add_image_size( 'shop_single', '800', '800', 1 );

			// Image Size filtering prior to WC v3.0 (@deprecated)
			add_filter( 'single_product_large_thumbnail_size', array($this, 'single_thumb') ); // @deprecated as of WC v3.0
			add_filter( 'single_product_small_thumbnail_size', array($this, 'catalog_thumb') ); // @deprecated as of WC v3.0

		}

		// Add "thumbnail" classes, if enabled for theme
		add_filter( 'post_thumbnail_html', array($this, 'thumb_class'), 10, 2 );
		add_filter( 'woocommerce_cart_item_thumbnail', array($this, 'thumb_class'), 10, 2 );
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array($this, 'thumb_class') );

		// Epic thumbnails
		add_action( 'wp', array($this, 'thumb_epic'), 11 ); // after frameowrk's set_atts()

		/**
		 * Modify WooCommerce settings page
		 */

		// add_filter('woocommerce_general_settings', array($this, 'remove_options') );
		add_filter('woocommerce_product_settings', array($this, 'remove_options') );

		/**
		 * Single product display
		 */

		// Wrap main portion of product display
		add_action( 'woocommerce_before_single_product_summary', array($this, 'product_open'), 5 );
		add_action( 'woocommerce_after_single_product_summary', array($this, 'product_close'), 1 );

		// Add Bootstrap contextual classes
		add_filter( 'woocommerce_sale_flash', array($this, 'sale_flash') );
		add_filter( 'woocommerce_sale_price_html', array($this, 'sale_price') );
		add_filter( 'woocommerce_get_availability', array($this, 'availability') );

		// Comments
		add_filter( 'comment_class', array($this, 'comment_class'), 10, 3 );

		/**
		 * Shop
		 */

		// Set framework config of post ID to original shop page
		// ID, so theme settings for the page can get applied.
		add_filter( 'themeblvd_frontend_config_post_id', array($this, 'set_shop_id') );

		// Set global shop attribues
		add_action( 'wp', array( $this, 'set_atts' ) );

		// Wrap shop loop
		add_action( 'woocommerce_before_shop_loop', array($this, 'loop_open') );
		add_action( 'woocommerce_before_shop_loop', array($this, 'loop_header_start'), 15 );
		add_action( 'woocommerce_before_shop_loop', array($this, 'loop_header_end'), 40 );
		add_action( 'woocommerce_after_shop_loop', array($this, 'loop_close') );

		// Move page title to within our content wrapper
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Wrap products
		add_filter( 'woocommerce_before_shop_loop_item', array($this, 'loop_product_open'), 5 );
		add_filter( 'woocommerce_after_shop_loop_item', array($this, 'loop_product_close') );

		// Product thumbnail crossfade and thumb class
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'show_product_image'), 20 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

		// Breadcrumbs
		add_filter( 'themeblvd_pre_breadcrumb_parts', array($this, 'add_breadcrumb'), 10, 2 );

		// Products per page
		add_filter( 'loop_shop_per_page', array($this, 'per_page') );

		/**
		 * Secondary Loops
		 */

		// Up sells
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		add_action( 'woocommerce_after_single_product_summary', array($this, 'up_sell'), 15 );

		// Cross sells
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'themeblvd_content_bottom', array($this, 'cross_sell') );

		/**
		 * Shortcodes
		 */

		// Allow product displaying shortcodes to have the correct number of
		// columns and accept "view" attribute of list, grid or catalog - which
		// is specifc to the theme.
		add_filter( 'shortcode_atts_recent_products', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_featured_products', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_products', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_product_category', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_sale_products', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_best_selling_products', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_related_products', array($this, 'shortcode_set_view') );
		add_filter( 'shortcode_atts_top_rated_products', array($this, 'shortcode_set_view') );

		// Allow category thumbnail display to have correct number of columns
		// inputted by the user.
		add_filter( 'shortcode_atts_product_categories', array($this, 'shortcode_categories_set_view'), 10, 3 );

		/**
		 * Widgets
		 */

		// Search Form
		add_filter( 'get_product_search_form', array($this, 'search_form') );

		/**
		 * Notices
		 */
		add_filter( 'woocommerce_add_message', array($this, 'notice_message') );

		/**
		 * Implement theme options
		 */
		add_filter( 'themeblvd_sidebar_layout', array($this, 'sidebar_layout') );
		add_filter( 'loop_shop_columns', array($this, 'loop_columns') );

		/**
		 * Cart
		 */

		// Floating shopping cart
		add_filter( 'themeblvd_do_cart', array($this, 'do_cart') );
		add_action( 'themeblvd_floating_cart', array($this, 'cart') );
		add_action( 'themeblvd_after', array($this, 'add_cart') );

		// Update for number of items in floating cart
		if ( defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=') ) {
			add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'cart_link_fragment') );
		} else {
			add_filter( 'add_to_cart_fragments', array($this, 'cart_link_fragment') );
		}

	}

	/**
	 * Get current WooCommerce version
	 *
	 * @since 2.5.0
	 */
	public function get_version() {
		if ( defined('WC_VERSION') ) {
			return WC_VERSION;
		}
		return 0;
	}

	/**
	 * Add any WooCommerce theme support.
	 *
	 * @since 2.6.5
	 */
	public function add_theme_support() {

		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		if ( 'no' !== themeblvd_get_option( 'woo_product_zoom' ) ) {

			add_theme_support( 'wc-product-gallery-zoom' );

		}

	}

	/**
	 * Add CSS
	 *
	 * @since 2.5.0
	 */
	public function assets( $type ) {

		$handler = Theme_Blvd_Stylesheet_Handler::get_instance();
		$deps = $handler->get_framework_deps();

		// Style all of WooCommerce
		wp_enqueue_style( 'themeblvd-wc', esc_url( TB_FRAMEWORK_URI . '/compat/woocommerce/woocommerce.min.css' ), $deps, TB_FRAMEWORK_VERSION );

		// Remove increment button styling, if using WooCommerce Quantity Increment plugin.
		wp_dequeue_style( 'wcqi-css' );

		// Make sure WooCommerce doesn't load prettyPhoto
		if ( apply_filters('themeblvd_remove_prettyphoto', true) ) {
			wp_dequeue_script('prettyPhoto');
			wp_dequeue_script('prettyPhoto-init');
			wp_dequeue_style('woocommerce_prettyPhoto_css');
		}

	}

	/**
	 * Add our stylesheet to framework $deps. This will make
	 * sure our wpml.css file comes between framework
	 * styles and child theme's style.css
	 *
	 * @since 2.5.0
	 */
	public function add_style( $deps ) {
		$deps[] = 'woocommerce';
		return $deps;
	}

	/**
	 * Add any <body> classes needed for styling.
	 *
	 * @since 2.5.0
	 */
	public function body_class( $class ) {

		if ( version_compare( $this->get_version(), '2.3.0', '<' ) ) {
			$class[] = 'wc-legacy';
		}

		return $class;
	}

	/**
	 * An override for content-product.php, used only when
	 * viewing a set of products in "catalog" or "list" view.
	 * The default grid view will use content-product.php in
	 * standard WooCommerce template system.
	 *
	 * @since 2.5.0
	 */
	public function product_template( $template, $slug, $name ) {

		if ( $slug == 'content' && $name == 'product' ) {

			$view = $this->loop_view();

			if ( in_array( $view, array('list', 'catalog') ) ) {
				$template = apply_filters('themeblvd_woocommerce_'.$view.'_template', trailingslashit(TB_FRAMEWORK_DIRECTORY) . 'compat/woocommerce/templates/' . 'content-product-'.$view.'.php');
			}
		}

		return $template;
	}

	/**
	 * Override template parts.
	 *
	 * Note: Any template files we override here can't be
	 * overriden in the typical way of being copied to a child
	 * theme. So, we're doing this ONLY to very select template
	 * files.
	 *
	 * Doing it this way is less user-friendly, but requires less
	 * server resources, as we're not adding an additional directory
	 * check for EVERY template file of WooCommerce, and only taking
	 * over the few we need to override.
	 *
	 * If any of our overrides need to be modified, you can use the
	 * filter "themeblvd_woocommerce_template_overrides".
	 *
	 * @since 2.5.0
	 */
	public function templates( $template, $template_name, $template_path ) {

		// If you need to put any templates we've overriden
		// back into the standard system, you can use this filter,
		// and unset() them.
		$override = apply_filters('themeblvd_woocommerce_template_overrides', array(
			'loop/pagination.php'	=> 'loop/pagination.php',
			'loop/loop-start.php'	=> 'loop/loop-start.php',
			'loop/loop-end.php'		=> 'loop/loop-end.php',
			'loop/orderby.php'		=> 'loop/orderby.php'
		));

		if ( in_array($template_name, $override) ) {
			$file = explode('/', $template_name);
			$template = trailingslashit(TB_FRAMEWORK_DIRECTORY) . 'compat/woocommerce/templates/' . end($file);
		}

		return $template;
	}

	/**
	 * Filter "single_product_large_thumbnail_size"
	 *
	 * @since 2.5.0
	 * @deprecated
	 */
	public function single_thumb( $size ) {

		return apply_filters('themeblvd_woocommerce_single_product_small_thumb', 'tb_square_small');

	}

	/**
	 * Filter catalog thumbnail
	 *
	 * @since 2.5.0
	 * @deprecated
	 */
	public function catalog_thumb( $size ) {

		if ( $size == 'shop_catalog' ) { // @deprecated as of WC v3.0

			return apply_filters('themeblvd_woocommerce_catalog_thumb', 'tb_square_medium');

		}

		return $size;
	}

	/**
	 * Modify markup for thumbnails to link to our lightbox
	 *
	 * @since 2.5.0
	 */
	public function lightbox( $html ) {

		$class = 'tb-thumb-link image themeblvd-lightbox mfp-image';

		// Main image
		if ( apply_filters('themeblvd_featured_thumb_frame', false) ) {
			$html = str_replace( 'class="woocommerce-main-image', 'class="woocommerce-main-image thumbnail '.$class, $html);
		} else {
			$html = str_replace( 'class="woocommerce-main-image', 'class="woocommerce-main-image '.$class, $html);
		}

		// Small thumbnails
		$html = str_replace( 'class="zoom', 'class="zoom '.$class, $html);

		return $html;
	}

	/**
	 * Add "thumbnail" class, if enabled
	 *
	 * @since 2.5.0
	 */
	public function thumb_class( $html, $post_id = null ) {

		if ( apply_filters('themeblvd_featured_thumb_frame', false) && ( $post_id === null || get_post_type($post_id) == 'product' ) ) {

			if ( strpos($html, 'zoom') !== false ) {
				$html = str_replace('zoom', 'zoom thumbnail', $html);
			} else {
				$html = str_replace('attachment-shop_thumbnail', 'attachment-shop_thumbnail thumbnail', $html);
			}

		}

		return $html;
	}

	/**
	 * Add epic thumbnail to shop pages, if applied
	 *
	 * @since 2.6.0
	 */
	public function thumb_epic() {

		if ( is_shop() && function_exists('themeblvd_set_att') ) {

			$shop_id = get_option('woocommerce_shop_page_id');

			if ( ! has_post_thumbnail($shop_id) ) {
				return;
			}

			$val = get_post_meta($shop_id, '_tb_thumb', true);

			if ( ! $val || $val == 'default' ) {
				$val = themeblvd_get_option( 'single_thumbs', null, 'fw' );
			}

			if ( $val == 'fs' || $val == 'fw' ) {
				themeblvd_set_att('thumbs', $val);
				themeblvd_set_att('epic_thumb', true);
			}

		} else if ( is_product_category() ) {

			// ... @TODO

		}
	}

	/**
	 * Remove options from WooCommerce settings page
	 *
	 * @since 2.5.0
	 */
	public function remove_options( $options ) {

		$remove = array();

		if ( apply_filters( 'themeblvd_woocommerce_images', true ) ) {
			$remove[] = 'image_options';
			$remove[] = 'shop_catalog_image_size';
			$remove[] = 'shop_single_image_size';
			$remove[] = 'shop_thumbnail_image_size';
		}

		$remove = apply_filters('themeblvd_woocommerce_remove_options', $remove);

		if ( $remove ) {
			foreach ( $options as $key => $value ) {
				if ( ! empty($value['id']) && in_array($value['id'], $remove) ) {
					unset($options[$key]);
				}
			}
		}

		return $options;
	}

	/**
	 * Start wrap for product display
	 *
	 * @since 2.5.0
	 */
	public function product_open() {
		echo '<div class="tb-product-wrap themeblvd-gallery bg-content">';
		echo '<div class="product-wrap-inner">';
	}

	/**
	 * End wrap for product display
	 *
	 * @since 2.5.0
	 */
	public function product_close() {
		echo '</div><!-- .product-wrap-inner (end) -->';
		echo '</div><!-- .tb-product-wrap (end) -->';
	}

	/**
	 * Edit on-sale notice
	 *
	 * @since 2.5.0
	 */
	public function sale_flash( $html ) {
		return str_replace('onsale', 'onsale bg-success', $html);
	}

	/**
	 * Edit sale price display
	 *
	 * @since 2.5.0
	 */
	public function sale_price( $html ) {
		return str_replace('<del><span class="amount">', '<del><span class="amount text-muted">', $html);
	}

	/**
	 * Edit availability
	 *
	 * @since 2.5.0
	 */
	public function availability( $args ) {

		if ( $args['class'] == 'in-stock' ) {
			$args['class'] .= ' text-success';
		} else if ( $args['class'] == 'out-of-stock' ) {
			$args['class'] .= ' text-danger';
		} else {
			$args['class'] .= ' text-muted';
		}

		return $args;
	}

	/**
	 * Add clearfix to comments
	 *
	 * @since 2.5.0
	 */
	public function comment_class( $classes, $class, $comment_id ) {

		if ( get_post_type( $comment_id ) == 'product' ) {
			$classes[] = 'clearfix';
		}

		return $classes;
	}

	/**
	 * Set framework config of post ID to original shop page
	 * ID, so theme settings for the page can get applied.
	 *
	 * @since 2.5.1
	 */
	public function set_shop_id( $id ) {

		if ( is_shop() ) {
			return get_option('woocommerce_shop_page_id');
		}

		return $id;
	}

	/**
	 * Set global attributes we can use in the shop.
	 *
	 * @since 2.5.0
	 */
	public function set_atts() {

		global $_GET;

		if ( is_admin() ) {
			return;
		}

		// Product display view

		$view = 'grid';

		if ( ! empty($_GET['view']) && in_array($_GET['view'], array('grid', 'list', 'catalog')) ) {
			$view = $_GET['view'];
		} else if ( is_product_category() || is_product_tag() || ( is_woocommerce() && is_search() ) ) {
			$view = themeblvd_get_option('woo_archive_view', null, 'grid');
		} else if ( is_shop() ) {
			$view = themeblvd_get_option('woo_shop_view', null, 'grid');
		}

		themeblvd_set_att( 'woo_product_view', $view );

	}

	/**
	 * Start product loop
	 *
	 * @since 2.5.0
	 */
	public function loop_open() {
		printf( '<div class="tb-product-loop-wrap shop-columns-%s %s-view bg-content clearfix">', $this->loop_columns(), $this->loop_view() );
	}

	/**
	 * Start loop's header
	 *
	 * @since 2.5.0
	 */
	public function loop_header_start() {
		printf( '<header class="entry-header"><h1 class="entry-title">%s</h1>', woocommerce_page_title(false));
	}

	/**
	 * End loop's header
	 *
	 * @since 2.5.0
	 */
	public function loop_header_end() {
		echo '</header><!-- .entry-header -->';
	}

	/**
	 * End product loop
	 *
	 * @since 2.5.0
	 */
	public function loop_close() {
		echo '</div><!-- .tb-product-loop-wrap (end) -->';
	}

	/**
	 * Wrap product start
	 *
	 * @since 2.5.0
	 */
	public function loop_product_open() {
		echo '<div class="tb-product">';
	}

	/**
	 * Wrap product end
	 *
	 * @since 2.5.0
	 */
	public function loop_product_close() {
		echo '</div><!-- .tb-product (end) -->';
	}

	/**
	 * Show product image in archive
	 *
	 * @since 2.5.0
	 */
	public function show_product_image() {

		global $product;

		$post_id = get_the_ID();

		$size = 'shop_catalog';

		if ( apply_filters('themeblvd_woocommerce_images', true) ) {
			$size = apply_filters('themeblvd_woocommerce_thumb_product', 'tb_square_medium');
		}

		$class = "attachment-$size";

		if ( apply_filters('themeblvd_featured_thumb_frame', false) ) {
			$class .= ' thumbnail';
		}

		echo '<span class="product-thumb">';

		// We'll float the rating inside for when hovered on
		if ( function_exists( 'wc_get_rating_html' ) ) {

			$rating = wc_get_rating_html( $product->get_average_rating() ); // WC 3.0+

		} else {

			$rating = $product->get_rating_html(); // @deprecated as of WC v3.0

		}

		if ( $rating ) {

			$rating = str_replace( 'div', 'span', $rating );

			printf( '<span class="rating-wrap">%s</span>', $rating );

		}

		// Output first image from product gallery, to show on hover
		$gallery = get_post_meta( $post_id, '_product_image_gallery', true );

		if ( $gallery ) {

			$gallery = explode( ',',$gallery );
			$image_id = $gallery[0];

			echo wp_get_attachment_image( $image_id, $size, false, array('class' => "$class hover") );
		}

		// Output standard product image
		the_post_thumbnail( $size, array('class' => $class) );

		echo '</span><!-- .product-thumb (end) -->';
	}

	/**
	 * Add breadcrumbs
	 *
	 * @since 2.5.0
	 * @see themeblvd_get_breadcrumb_parts()
	 */
	public function add_breadcrumb( $parts, $atts ) {

		global $wp_query;

		if ( is_woocommerce() ) {

			$parts = array();

			if ( is_search() ) {

				$parts[] = array(
					'link' 	=> '',
					'text' 	=> themeblvd_get_local('crumb_search').' "'.get_search_query().'"',
					'type'	=> 'search'
				);

			} else if ( is_shop() ) { // also true when is_search()

				$parts[] = array(
					'link'  => '',
					'text'  => get_the_title( wc_get_page_id('shop') ),
					'type'  => 'page'
				);

			} else if ( is_product_category() ) {

				// Parent categories
				$cat_obj = $wp_query->get_queried_object();
				$current_cat = $cat_obj->term_id;
				$current_cat = get_term( $current_cat, 'product_cat' );

				if ( $current_cat->parent && ( $current_cat->parent != $current_cat->term_id ) ) {
					$parents = themeblvd_get_term_parents( $current_cat->parent, 'product_cat' );
					$parts = array_merge( $parts, $parents );
				}

				// Add current category
				$parts[] = array(
					'link'  => '',
					'text'  => $current_cat->name,
					'type'  => 'category'
				);

			} else if ( is_product_tag() ) {

				$tag_obj = $wp_query->get_queried_object();

				$parts[] = array(
					'link' 	=> '',
					'text' 	=> sprintf('%s "%s"', themeblvd_get_local('crumb_tag_products'), $tag_obj->name),
					'type'	=> 'tag'
				);

			} else if ( is_product() ) {

				// Product category tax tree
				$cat = get_the_terms( get_the_id(), 'product_cat' );

				if ( $cat ) {
					$cat = reset( $cat );
					$parents = themeblvd_get_term_parents( $cat->term_id, 'product_cat' );
					$parts = array_merge( $parts, $parents );
				}

				$parts[] = array(
					'link' 	=> '',
					'text' 	=> get_the_title(),
					'type'	=> 'single'
				);

			}

			// Add shop page from WooCommerce settings to breadcrumbs trail
			if ( ! is_shop() || ( is_shop() && is_search() ) && apply_filters('themeblvd_woocommerce_add_shop_to_breadcrumbs', true) ) {

				$shop_id = wc_get_page_id('shop');

				if ( $shop_id && $shop_id != -1 ) {

					$add = array(
						array(
							'link'  => get_permalink( wc_get_page_id('shop') ),
							'text'  => get_the_title( wc_get_page_id('shop') ),
							'type'  => 'page'
						)
					);

					$parts = array_merge($add, $parts);

				}

			}

		}

		return $parts;
	}

	/**
	 * Products per page
	 *
	 * @since 2.5.0
	 */
	public function per_page( $per ) {

		if ( ! empty($_GET['per_page']) ) {
			$per = $_GET['per_page'];
		} else if ( is_product_category() || is_product_tag() || is_search() ) {
			$per = themeblvd_get_option('woo_archive_per_page', null, '12');
		} else if ( is_shop() ) {
			$per = themeblvd_get_option('woo_shop_per_page', null, '12');
		}

		return intval($per);
	}

	/**
	 * Up sell product display. Override Woo's woocommerce_upsell_display().
	 *
	 * @since 2.5.0
	 */
	public function up_sell() {

		$args = apply_filters('themeblvd_woocommerce_up_sell_args', array(
			'posts_per_page'	=> '-1',
			'orderby'			=> apply_filters('woocommerce_upsells_orderby', 'rand'), // woo default filter, let's keep it for compat
			'order'				=> 'desc',
			'columns'			=> $this->loop_columns()
		));

		woocommerce_upsell_display(
			$args['posts_per_page'],
			$args['columns'],
			$args['orderby'],
			$args['order']
		);

	}

	/**
	 * Cross sell product display. Override Woo's woocommerce_cross_sell_display().
	 *
	 * @since 2.5.0
	 */
	public function cross_sell() {

		if ( is_cart() && themeblvd_get_option('woo_cross_sell') == 'yes' ) {

			$args = apply_filters('themeblvd_woocommerce_cross_sell_args', array(
				'posts_per_page' 	=> '-1',
				'order'				=> 'desc',
				'orderby'        	=> 'rand',
				'columns'        	=> $this->loop_columns()
			));

			woocommerce_cross_sell_display(
				$args['posts_per_page'],
				$args['columns'],
				$args['orderby'],
				$args['order']
			);

		}

	}

	/**
	 * Set the display view when using WooCommerce shortcode
	 * by allowing user to add view arg to shortcode, which
	 * can be set to list, grid, or catalog.
	 *
	 * @since 2.5.0
	 */
	public function shortcode_set_view( $atts ) {

		if ( ! function_exists('themeblvd_set_att') ) {
			return $atts;
		}

		if ( ! empty( $atts['columns'] ) ) {
			themeblvd_set_att( 'woo_product_columns', $atts['columns'] );
		}

		if ( isset($atts['view']) && in_array($atts['view'], array('list', 'grid', 'catalog')) ) {
			themeblvd_set_att( 'woo_product_view_reset', themeblvd_get_att('woo_product_view') );
			themeblvd_set_att( 'woo_product_view', $atts['view'] );
		}

		return $atts; // Pass $atts back through, untouched.
	}

	/**
	 * Set the number of columns when using WooCommerce
	 * [product_categories] shortcode.
	 *
	 * @since 2.5.0
	 */
	public function shortcode_categories_set_view( $out, $pairs, $atts ) {

		if ( ! function_exists('themeblvd_set_att') ) {
			return $out;
		}

		if ( ! empty( $atts['columns'] ) ) {
			themeblvd_set_att( 'woo_product_columns', $atts['columns'] );
		}

		themeblvd_set_att( 'woo_product_view_reset', themeblvd_get_att('woo_product_view') );
		themeblvd_set_att( 'woo_product_view', 'grid' ); // Categories always displayed in a grid.

		return $out; // pass $out back through, untouched
	}

	/**
	 * After the current loop, check for what we did in
	 * shortcode_set_view(), and reset everything back.
	 * Function called in loop-end.php.
	 *
	 * @since 2.5.0
	 */
	public function shortcode_reset_view() {

		if ( ! function_exists('themeblvd_set_att') ) {
			return;
		}

		if ( themeblvd_get_att('woo_product_columns') ) {
			themeblvd_set_att( 'woo_product_columns', null );
		}

		if ( $reset = themeblvd_get_att('woo_product_view_reset') ) {
			themeblvd_set_att( 'woo_product_view', $reset );
			themeblvd_set_att( 'woo_product_view_reset', null );
		}
	}

	/**
	 * Edit searchform output
	 *
	 * @since 2.5.0
	 */
	public function search_form( $html ) {

		$html = get_search_form(false);

		$add = '<input type="hidden" name="post_type" value="product" />';
		$find = '<button class="search-submit btn-primary" type="submit">';

		return str_replace($find, $add."\n\t\t\t".$find, $html);
	}

	/**
	 * Modify button classes for notices
	 *
	 * @since 2.5.0
	 */
	function notice_message( $msg ) {
		return str_replace('button wc-forward', 'wc-forward btn btn-primary btn-xs', $msg);
	}

	/**
	 * Sidebar Layouts
	 *
	 * @since 2.5.0
	 */
	public function sidebar_layout( $layout ) {

		if ( is_product() ) {
			$layout = themeblvd_get_option('woo_product_sidebar_layout');
		} else if ( is_shop() && ! is_search() ) {
			$layout = themeblvd_get_option('woo_shop_sidebar_layout');
		} else if ( is_woocommerce() ) {
			$layout = themeblvd_get_option('woo_archive_sidebar_layout');
		}

		return $layout;
	}

	/**
	 * Shop loop columns
	 *
	 * @since 2.5.0
	 */
	public function loop_columns( $cols = 4 ) {

		if ( themeblvd_get_att('woo_product_columns') ) {
			$cols = themeblvd_get_att('woo_product_columns');
		} else if ( is_product_category() || is_product_tag() || is_search() ) {
			$cols = themeblvd_get_option('woo_archive_columns');
		} else if ( is_shop() ) {
			$cols = themeblvd_get_option('woo_shop_columns');
		}

		return intval($cols);
	}

	/**
	 * Get current shop view
	 *
	 * @since 2.5.0
	 */
	public function loop_view() {

		$view = themeblvd_get_att('woo_product_view');

		if ( ! $view ) {
			$view = 'grid';
		}

		return apply_filters('themeblvd_product_view', $view);
	}

	/**
	 * Whether to display floating shopping cart.
	 *
	 * @since 2.6.1
	 */
	public function do_cart() {

		if ( themeblvd_get_option('woo_floating_cart') != 'no' ) {
			return true;
		}

		return false;
	}

	/**
	 * Output WooCommerce shopping cart widget
	 *
	 * @since 2.5.0
	 */
	public function cart() {
		the_widget( 'WC_Widget_Cart', 'title=' );
	}

	/**
	 * Output the full floating cart modal, so it can
	 * be accessed with our trigger the theme may output
	 * any where it needs.
	 *
	 * @since 2.5.0
	 */
	public function add_cart() {
		if ( ! is_cart() && ! is_checkout() ) {
			themeblvd_cart_popup();
		}
	}

	/**
	 * Ajaxify number of items for floating shopping cart trigger.
	 *
	 * @since 2.5.0
	 */
	public function cart_link_fragment( $fragments ) {

		$fragments['.tb-woocommerce-cart-popup-link'] = themeblvd_get_cart_popup_trigger();

		$fragments['.tb-woocommerce-cart-page-link'] = themeblvd_get_cart_popup_trigger( array( 'target' => null ) );

		$fragments['#mobile-to-cart'] = themeblvd_get_mobile_cart_link();

		return $fragments;
	}

}
