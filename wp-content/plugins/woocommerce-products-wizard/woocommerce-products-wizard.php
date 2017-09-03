<?php
/**
 * Plugin Name: WooCommerce Products Wizard
 * Description: This plugin helps you sell your products by the step-by-step wizard.
 * Version: 2.11.0
 * Author: troll_winner@mail.ru
 * Author URI: troll_winner@mail.ru
 */

namespace {

    define('WC_PRODUCTS_WIZARD_VERSION', '2.11.0');

    if (!defined('WC_PRODUCTS_WIZARD_PLUGIN_PATH')) {
        define('WC_PRODUCTS_WIZARD_PLUGIN_PATH', plugin_dir_path(__FILE__));
    }

    if (!defined('WC_PRODUCTS_WIZARD_DEBUG')) {
        if (defined('SCRIPT_DEBUG')) {
            define('WC_PRODUCTS_WIZARD_DEBUG', SCRIPT_DEBUG);
        } else {
            define('WC_PRODUCTS_WIZARD_DEBUG', false);
        }
    }

    if (!defined('WC_PRODUCTS_WIZARD_THEME_TEMPLATES_DIR')) {
        define('WC_PRODUCTS_WIZARD_THEME_TEMPLATES_DIR', 'woocommerce-products-wizard');
    }

    if (!defined('WC_PRODUCTS_WIZARD_PLUGIN_URL')) {
        define('WC_PRODUCTS_WIZARD_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

    // include base class
    require_once('includes/Core.php');
    require_once('includes/global.php');
}

namespace WCProductsWizard {

    function Instance()
    {
        return Core::instance();
    }

    $GLOBALS['woocommerceProductsWizard'] = Instance();

    // register shortcodes
    \add_shortcode('woocommerce-products-wizard', __NAMESPACE__. '\\shortCode');

    function shortCode($attributes)
    {
        return Instance()->getTemplatePart(
            'app',
            $attributes,
            ['echo' => false]
        );
    }

    \add_action('plugins_loaded', __NAMESPACE__ . '\\loadTextDomain');

    function loadTextDomain()
    {
        load_plugin_textdomain(
            'woocommerce-products-wizard',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }
}
