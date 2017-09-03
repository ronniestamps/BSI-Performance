<?php
namespace WCProductsWizard;

/**
 * Core Main Class
 *
 * @class Core
 * @version 3.2.0
 */
class Core
{
    /**
     * Self instance variable
     *
     * @var Core The single instance of the class
     */
    protected static $instance = null;

    /**
     * Cart instance variable
     *
     * @var Cart
     */
    public $cart = null;

    /**
     * Excluded products instance variable
     *
     * @var Excluded
     */
    public $excluded = null;

    /**
     * Admin part instance variable
     *
     * @var Admin
     */
    public $admin = null;

    /**
     * Active steps session keys variable
     *
     * @var string
     */
    public $activeStepsSessionKey = null;

    /**
     * WC products wizard post settings model variable
     *
     * @var array
     */
    public $postSettingsModel = [];

    /**
     * Product terms settings model variable
     *
     * @var array
     */
    public $termSettingsModel = [];

    /**
     * Core Constructor.
     */
    public function __construct()
    {
        self::$instance = $this;

        $this->postSettingsModel = apply_filters(
            'wcProductsWizardPostSettingsModel',
            [
                [
                    'label' => 'Disable products dependencies',
                    'key' => 'dependencies_disable',
                    'type' => 'checkbox',
                    'default' => false
                ],
                [
                    'label' => 'Enable all tabs availability',
                    'key' => 'enable_all_tabs_availability',
                    'type' => 'checkbox',
                    'description' => 'Required disabled products dependencies',
                    'default' => false
                ],
                [
                    'label' => 'Enable single step mode',
                    'key' => 'enable_single_step_mode',
                    'type' => 'checkbox',
                    'default' => false,
                    'description' => 'Enables individual controls'
                ],
                [
                    'label' => 'Item template',
                    'key' => 'item_template',
                    'type' => 'select',
                    'values' => $this->getFormItemTemplates(),
                    'default' => 'type-1'
                ],
                [
                    'label' => 'Template',
                    'key' => 'template',
                    'type' => 'select',
                    'values' => $this->getFormTemplates(),
                    'default' => 'type-1'
                ],
                [
                    'label' => 'Always show sidebar',
                    'key' => 'always_show_sidebar',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'Description position',
                    'key' => 'description_position',
                    'type' => 'select',
                    'values' => [
                        [
                            'name' => 'Top',
                            'value' => 'top'
                        ],
                        [
                            'name' => 'Bottom',
                            'value' => 'bottom'
                        ]
                    ],
                    'default' => 'top'
                ],
                [
                    'label' => 'Enable description tab',
                    'key' => 'description_tab_enable',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'Description tab title',
                    'key' => 'description_tab_title',
                    'type' => 'text',
                    'default' => 'Welcome'
                ],
                [
                    'label' => 'Results tab title',
                    'key' => 'results_tab_title',
                    'type' => 'text',
                    'default' => 'Total'
                ],
                [
                    'label' => 'Start-button text',
                    'key' => 'start_button_text',
                    'type' => 'text',
                    'default' => 'Start'
                ],
                [
                    'label' => 'Add-to-cart-button text',
                    'key' => 'add_to_cart_button_text',
                    'type' => 'text',
                    'default' => 'Add to cart'
                ],
                [
                    'label' => 'Enable remove button',
                    'key' => 'enable_remove_button',
                    'type' => 'checkbox',
                    'default' => false,
                    'description' => 'Appears in the cart tab and widget'
                ],
                [
                    'label' => 'Remove-button text',
                    'key' => 'remove_button_text',
                    'type' => 'text',
                    'default' => 'Remove'
                ],
                [
                    'label' => 'Enable back-button',
                    'key' => 'enable_back_button',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'Back-button text',
                    'key' => 'back_button_text',
                    'type' => 'text',
                    'default' => 'Back'
                ],
                [
                    'label' => 'Enable next-button',
                    'key' => 'enable_next_button',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'Next-button text',
                    'key' => 'next_button_text',
                    'type' => 'text',
                    'default' => 'Next'
                ],
                [
                    'label' => 'Enable reset-button',
                    'key' => 'enable_reset_button',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'Reset-button text',
                    'key' => 'reset_button_text',
                    'type' => 'text',
                    'default' => 'Reset'
                ],
                [
                    'label' => 'Enable skip-button',
                    'key' => 'enable_skip_button',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'Skip-button text',
                    'key' => 'skip_button_text',
                    'type' => 'text',
                    'default' => 'Skip'
                ],
                [
                    'label' => 'Enable to-results-button',
                    'key' => 'enable_to_results_button',
                    'type' => 'checkbox',
                    'default' => true
                ],
                [
                    'label' => 'To-results-button text',
                    'key' => 'to_results_button_text',
                    'type' => 'text',
                    'default' => 'To results'
                ]
            ]
        );

        $this->termSettingsModel = apply_filters(
            'wcProductsWizardTermSettingsModel',
            [
                'individual_controls' => [
                    'label' => 'Individual controls',
                    'key' => 'individual_controls',
                    'type' => 'checkbox',
                    'default' => false
                ],
                'several_products' => [
                    'label' => 'Can add several products from a term',
                    'key' => 'several_products',
                    'type' => 'checkbox',
                    'default' => false
                ],
                'sold_individually' => [
                    'label' => 'Sold individually',
                    'key' => 'sold_individually',
                    'type' => 'checkbox',
                    'default' => false
                ],
                'no_selected_items_by_default' => [
                    'label' => 'No selected items by default',
                    'key' => 'no_selected_items_by_default',
                    'type' => 'checkbox',
                    'default' => false
                ],
                'description' => [
                    'label' => 'Description',
                    'key' => 'description',
                    'type' => 'textarea',
                    'default' => ''
                ],
                'description_position' => [
                    'label' => 'Description position',
                    'key' => 'description_position',
                    'type' => 'select',
                    'values' => [
                        [
                            'name' => 'Default',
                            'value' => 'default'
                        ],
                        [
                            'name' => 'Top',
                            'value' => 'top'
                        ],
                        [
                            'name' => 'Bottom',
                            'value' => 'bottom'
                        ]
                    ],
                    'default' => 'default'
                ],
                'template' => [
                    'label' => 'Template',
                    'key' => 'template',
                    'type' => 'select',
                    'values' => Core::getFormTemplates(),
                    'default' => 'list'
                ],
                'item_template' => [
                    'label' => 'Item template',
                    'key' => 'item_template',
                    'type' => 'select',
                    'values' => Core::getFormItemTemplates(),
                    'default' => 'type-1'
                ],
                'variations_type' => [
                    'label' => 'Variation template',
                    'key' => 'variations_type',
                    'type' => 'select',
                    'values' => [
                        [
                            'name' => 'Select',
                            'value' => 'select'
                        ],
                        [
                            'name' => 'Radio',
                            'value' => 'radio'
                        ]
                    ],
                    'default' => 'select'
                ],
                'minimum_products_to_add' => [
                    'label' => 'Minimum items to add',
                    'key' => 'minimum_products_to_add',
                    'type' => 'number',
                    'default' => 1
                ],
                'included_products' => [
                    'label' => 'Included products',
                    'type' => 'data-table',
                    'key' => 'included_products',
                    'showHeader' => false,
                    'default' => [],
                    'values' => [
                        [
                            'label' => 'Products',
                            'key' => 'included_products',
                            'type' => 'wc-product-search',
                            'action' => 'woocommerce_json_search_products',
                            'multiple' => false,
                            'default' => []
                        ]
                    ]
                ],
                'excluded_products' => [
                    'label' => 'Excluded products',
                    'key' => 'excluded_products',
                    'type' => 'wc-product-search',
                    'action' => 'woocommerce_json_search_products',
                    'default' => 0
                ],
                'required_added_products' => [
                    'label' => 'Required added products',
                    'type' => 'data-table',
                    'key' => 'required_added_products',
                    'showHeader' => false,
                    'default' => [],
                    'description' => 'The tab will be visible then one of conditions will be satisfied',
                    'values' => [
                        [
                            'label' => 'Required added products',
                            'key' => 'required_added_products',
                            'type' => 'wc-product-search',
                            'action' => 'woocommerce_json_search_products',
                            'default' => 0
                        ]
                    ]
                ],
                'products_per_page' => [
                    'label' => 'Products per page',
                    'key' => 'products_per_page',
                    'type' => 'number',
                    'min' => 0,
                    'default' => 0,
                    'description' => 'Zero is equal infinity'
                ],
            ]
        );

        // include base slave classes
        if (!class_exists('Cart')) {
            require_once('Cart.php');
        }

        if (!class_exists('Excluded')) {
            require_once('Excluded.php');
        }

        $this->cart = new Cart();
        $this->excluded = new Excluded();

        // if is admin page
        if (is_admin()) {
            if (!class_exists('Admin')) {
                require_once('Admin.php');
            }

            $this->admin = new Admin([
                'postSettingsModel' => $this->postSettingsModel,
                'termSettingsModel' => $this->termSettingsModel
            ]);
        }

        // actions
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_action('wp_loaded', [$this, 'requests']);
        add_action('wp_head', [$this, 'enqueueAjaxUrl']);
        add_action('woocommerce_init', [$this, 'sessionStart'], 1);

        // ajax actions
        add_action('wp_ajax_nopriv_submitWoocommerceProductsWizardForm', [$this, 'submitFormAjax']);
        add_action('wp_ajax_submitWoocommerceProductsWizardForm', [$this, 'submitFormAjax']);
        add_action('wp_ajax_nopriv_getWoocommerceProductsWizardForm', [$this, 'getFormAjax']);
        add_action('wp_ajax_getWoocommerceProductsWizardForm', [$this, 'getFormAjax']);
        add_action('wp_ajax_nopriv_skipWoocommerceProductsWizardForm', [$this, 'skipFormAjax']);
        add_action('wp_ajax_skipWoocommerceProductsWizardForm', [$this, 'skipFormAjax']);
        add_action('wp_ajax_nopriv_skipAllWoocommerceProductsWizardForm', [$this, 'skipAllAjax']);
        add_action('wp_ajax_skipAllWoocommerceProductsWizardForm', [$this, 'skipAllAjax']);
        add_action('wp_ajax_nopriv_resetWoocommerceProductsWizardForm', [$this, 'resetFormAjax']);
        add_action('wp_ajax_resetWoocommerceProductsWizardForm', [$this, 'resetFormAjax']);
        add_action('wp_ajax_nopriv_addWoocommerceProductsWizardItemToCart', [$this, 'addItemToCartAjax']);
        add_action('wp_ajax_addWoocommerceProductsWizardItemToCart', [$this, 'addItemToCartAjax']);
        add_action('wp_ajax_nopriv_removeWoocommerceProductsWizardCartItem', [$this, 'removeCartItemAjax']);
        add_action('wp_ajax_removeWoocommerceProductsWizardCartItem', [$this, 'removeCartItemAjax']);
    }

    /**
     * Main Core Instance
     *
     * @static
     * @see WCProdWiz()
     * @return Core - Main instance
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Start the session if it isn't isset
     */
    public static function sessionStart()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Styles and scripts enqueue.
     */
    public static function enqueue()
    {
        $path = WC_PRODUCTS_WIZARD_DEBUG ? 'src' : 'assets';
        $suffix = WC_PRODUCTS_WIZARD_DEBUG ? '' : '.min';
        $stylesFolder = WC_PRODUCTS_WIZARD_DEBUG ? 'scss' : 'css';

        wp_enqueue_script('jquery');

        wp_enqueue_script(
            'bootstrap-notify',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/js/bootstrap-notify' . $suffix . '.js',
            ['jquery'],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'sticky-kit',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/js/sticky-kit' . $suffix . '.js',
            ['jquery'],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'wcpw',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/js/app' . $suffix . '.js',
            ['jquery'],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'wcpw-hooks',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/js/hooks' . $suffix . '.js',
            ['jquery'],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'wcpw-variation-form',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/js/variation-form' . $suffix . '.js',
            ['jquery'],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'wcpw-table-responsive',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/js/table-responsive' . $suffix . '.js',
            ['jquery'],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        if (filter_var(get_option('woocommerce_products_wizard_include_full_styles_file'), FILTER_VALIDATE_BOOLEAN)) {
            wp_enqueue_style(
                'wcpw-full',
                WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/' . $stylesFolder . '/app-full' . $suffix . '.css',
                [],
                WC_PRODUCTS_WIZARD_VERSION
            );
        } else {
            wp_enqueue_style(
                'wcpw',
                WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/front/' . $stylesFolder . '/app' . $suffix . '.css',
                [],
                WC_PRODUCTS_WIZARD_VERSION
            );
        }

        // WooCommerce assets
        if (get_option('woocommerce_enable_lightbox') === 'yes') {
            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            $assetsPath = str_replace(['http:', 'https:'], '', WC()->plugin_url()) . '/assets/';

            wp_enqueue_script(
                'prettyPhoto',
                $assetsPath . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js',
                ['jquery'],
                '3.1.6',
                true
            );

            wp_enqueue_script(
                'prettyPhoto-init',
                $assetsPath . 'js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js',
                ['prettyPhoto'],
                '3.1.6',
                true
            );

            wp_enqueue_style('woocommerce_prettyPhoto_css', $assetsPath . 'css/prettyPhoto.css');
        }
    }

    /**
     * Add the ajax url property to the window object
     */
    public static function enqueueAjaxUrl()
    {
        ?>
        <script type="text/javascript">
            if (typeof window.wordpressAjaxUrl === 'undefined') {
                window.wordpressAjaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
            }
        </script>
        <?php
    }

    /**
     * Get available form templates from plugin and theme directory
     *
     * @return array $templates
     */
    public static function getFormTemplates()
    {
        $templates = [
            [
                'name' => 'Default',
                'value' => 'default'
            ]
        ];

        $templatesInTheme = get_template_directory()
            . DIRECTORY_SEPARATOR . WC_PRODUCTS_WIZARD_THEME_TEMPLATES_DIR . DIRECTORY_SEPARATOR;

        // search form templates
        $templatePartials = WC_PRODUCTS_WIZARD_PLUGIN_PATH
            . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'form';

        if (file_exists($templatePartials)) {
            foreach (scandir($templatePartials) as $file) {
                if (!is_dir(
                    WC_PRODUCTS_WIZARD_PLUGIN_PATH
                    . DIRECTORY_SEPARATOR . 'views'
                    . DIRECTORY_SEPARATOR . 'form'
                    . DIRECTORY_SEPARATOR . $file
                )
                ) {
                    $templates[] = [
                        'name' => str_replace('.php', '', $file),
                        'value' => str_replace('.php', '', $file)
                    ];
                }
            }
        }

        if (file_exists($templatesInTheme) && file_exists($templatesInTheme . DIRECTORY_SEPARATOR . 'form')) {
            foreach (scandir($templatesInTheme . DIRECTORY_SEPARATOR . 'form') as $file) {
                if (!is_dir($templatesInTheme . DIRECTORY_SEPARATOR . 'form' . DIRECTORY_SEPARATOR . $file)) {
                    $templates[] = [
                        'name' => str_replace('.php', '', $file),
                        'value' => str_replace('.php', '', $file)
                    ];
                }
            }
        }

        return $templates;
    }

    /**
     * Get available form item templates from plugin and theme directory
     *
     * @return array $templates
     */
    public static function getFormItemTemplates()
    {
        $templates = [
            [
                'name' => 'Default',
                'value' => 'default'
            ]
        ];

        $templatesInTheme = get_template_directory()
            . DIRECTORY_SEPARATOR . WC_PRODUCTS_WIZARD_THEME_TEMPLATES_DIR . DIRECTORY_SEPARATOR;
        $itemTemplatesInTheme = $templatesInTheme . DIRECTORY_SEPARATOR . 'form' . DIRECTORY_SEPARATOR . 'item';

        // search form templates
        $templatePartials = WC_PRODUCTS_WIZARD_PLUGIN_PATH
            . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'form'
            . DIRECTORY_SEPARATOR . 'item';

        if (file_exists($templatePartials)) {
            foreach (scandir($templatePartials) as $file) {
                if (!is_dir(
                    WC_PRODUCTS_WIZARD_PLUGIN_PATH
                    . DIRECTORY_SEPARATOR . 'views'
                    . DIRECTORY_SEPARATOR . 'form'
                    . DIRECTORY_SEPARATOR . 'item'
                    . DIRECTORY_SEPARATOR . $file
                )) {
                    $templates[] = [
                        'name' => str_replace('.php', '', $file),
                        'value' => str_replace('.php', '', $file)
                    ];
                }
            }
        }

        if (file_exists($templatesInTheme) && file_exists($itemTemplatesInTheme)) {
            foreach (scandir($itemTemplatesInTheme) as $file) {
                if (is_dir($itemTemplatesInTheme . DIRECTORY_SEPARATOR . $file)) {
                    continue;
                }

                $templates[] = [
                    'name' => str_replace('.php', '', $file),
                    'value' => str_replace('.php', '', $file)
                ];
            }
        }

        return $templates;
    }

    /**
     * Include php-template by the name.
     * First looking in the "theme folder/woocommerce-products-wizard (WC_PRODUCTS_WIZARD_THEME_TEMPLATES_DIR)"
     * Second looking in the "plugin folder/views"
     * Making extraction of the arguments as variables
     *
     * @param string $name
     * @param array  $arguments
     * @param array  $settings
     *
     * @return string
     */
    public function getTemplatePart($name = '', $arguments = [], $settings = [])
    {
        $defaultSettings = [
            'echo' => true
        ];

        $settings = array_merge($defaultSettings, $settings);

        if (is_array($arguments)) {
            extract($arguments, EXTR_PREFIX_SAME, 'data');
        }

        $path = get_template_directory()
            . DIRECTORY_SEPARATOR
            . WC_PRODUCTS_WIZARD_THEME_TEMPLATES_DIR
            . DIRECTORY_SEPARATOR
            . $name
            . '.php';

        if (!file_exists($path)) {
            $path = WC_PRODUCTS_WIZARD_PLUGIN_PATH
                . DIRECTORY_SEPARATOR
                . 'views'
                . DIRECTORY_SEPARATOR
                . $name
                . '.php';
        }

        if (!file_exists($path)) {
            return '';
        }

        if ($settings['echo']) {
            return include($path);
        } else {
            ob_start();
            include($path);
            return ob_get_clean();
        }
    }

    /**
     * Add request actions
     */
    public function requests()
    {
        if (isset($_POST['woocommerce-products-wizard-add-to-cart'])) {
            $this->addProductsToMainCart($_POST['woocommerce-products-wizard-add-to-cart']);
        }

        if (isset($_POST['woocommerce-products-wizard-submit'])) {
            $this->submitForm($_POST);
        }

        if (isset($_POST['woocommerce-products-wizard-skip'])) {
            $this->skipForm($_POST);
        }

        if (isset($_POST['woocommerce-products-wizard-skip-all'])) {
            $this->skipAll($_POST);
        }

        if (isset($_POST['woocommerce-products-wizard-back'])) {
            $this->changeState(
                array_replace_recursive(
                    $_POST,
                    [
                        'nextStepId' => $_POST['stepId']
                    ]
                )
            );
        }

        if (isset($_POST['woocommerce-products-wizard-start'])) {
            $this->changeState($_POST);
        }

        if (isset($_POST['woocommerce-products-wizard-reset'])) {
            $this->resetForm($_POST);
        }
    }

    /**
     * Handles form submit
     *
     * @param array $args
     *
     * @return boolean
     */
    public function submitForm($args)
    {
        $defaults = [
            'stepId' => false,
            'incrementActiveStep' => true,
            'selectedProductsIds' => []
        ];

        $args = array_replace_recursive($defaults, $args);

        $activeStepId = $args['stepId'] ? $args['stepId'] : $this->getActiveStepId($args['id']);
        $stepCart = $this->cart->getProductsByTermId($args['id'], $activeStepId);
        $stepCartItemsIds = [];
        $settings = $this->getWizardSettings($args['id']);
        $termsSettings = $this->getTermsSettings($args['id']);
        $minimumProductsToAdd = isset($termsSettings[$args['stepId']])
            && isset($termsSettings[$args['stepId']]['minimum_products_to_add'])
                ? $termsSettings[$args['stepId']]['minimum_products_to_add']
                : 1;

        // get step cart items ids
        foreach ($stepCart as $stepCartItem) {
            $stepCartItemsIds[] = $stepCartItem['product_id'];
        }

        // no-js version: minimum item dependency
        if (isset($args['selectedProductsIds'][$activeStepId])
            && count(
                array_unique($stepCartItemsIds + $args['selectedProductsIds'][$activeStepId])
            ) < $minimumProductsToAdd
        ) {
            return false;
        }

        // is need to drop items from the same step
        if (!isset($termsSettings[$activeStepId]['several_products'])
            || !filter_var($termsSettings[$activeStepId]['several_products'], FILTER_VALIDATE_BOOLEAN)
        ) {
            $this->excluded->remove($args['id'], $activeStepId);
            $this->cart->removeByTermId($args['id'], $activeStepId);
        }

        if (is_array($args['productsToAdd'])) {
            foreach ($args['productsToAdd'] as $product) {
                // different ways to submit
                $productId = isset($product['product_id']) ? $product['product_id'] : false;
                $productId = !$productId && isset($product['productId'])
                    ? $product['productId']
                    : $productId;
                $variationId = isset($product['variation_id']) ? $product['variation_id'] : false;
                $variationId = !$variationId && isset($product['variationId'])
                    ? $product['variationId']
                    : $variationId;

                // no-js version: if product wasn't selected
                if (isset($args['selectedProductsIds'][$activeStepId])
                    && !in_array($productId, $args['selectedProductsIds'][$activeStepId])
                ) {
                    continue;
                }

                $quantity = isset($product['quantity']) ? $product['quantity'] : 1;
                $data = isset($product['data']) ? $product['data'] : [];

                $addToCart = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'term_id' => $activeStepId,
                    'data' => $data
                ];

                // if is variable product
                if ($variationId) {
                    parse_str(
                        urldecode(str_replace($productId . '-', '', $product['variation'])),
                        $variations
                    );

                    foreach ($variations as $variationKey => $variationItem) {
                        if (is_array($variationItem)) {
                            $variations[$variationKey] = array_shift($variationItem);
                        }
                    }

                    $addToCart['variation_id'] = $variationId;
                    $addToCart['variation'] = $variations;
                }

                $this->cart->add($args['id'], $addToCart);
            }
        }

        if (!isset($settings['dependencies_disable']) || !$settings['dependencies_disable']) {
            // remove products from the next steps
            $skip = true;

            foreach ($this->getStepsIds($args['id']) as $stepId) {
                if (!$skip) {
                    $this->cart->removeByTermId($args['id'], $stepId);
                }

                if ($stepId == $activeStepId) {
                    $skip = false;
                }
            }
        }

        // increment active step
        if (filter_var($args['incrementActiveStep'], FILTER_VALIDATE_BOOLEAN)) {
            $this->setActiveStep($args['id'], $this->getNextStepId($args['id']));
        }

        return true;
    }

    /**
     * Handles form submit by ajax
     */
    public function submitFormAjax()
    {
        $this->submitForm($_POST);

        wp_send_json([
            'content' => $this->getTemplatePart('router', $_POST, ['echo' => false])
        ]);
    }

    /**
     * Change active step and drop cart items from the next steps if this is needed
     *
     * @param array $args
     */
    public function changeState($args)
    {
        $defaults = [
            'nextStepId' => $this->getNextStepId($args['id'])
        ];

        $args = array_replace_recursive($defaults, $args);

        $settings = $this->getWizardSettings($args['id']);

        if (!isset($settings['dependencies_disable']) || !$settings['dependencies_disable']) {
            // remove products from the next steps
            $skip = true;

            foreach ($this->getStepsIds($args['id']) as $stepId) {
                if (!$skip) {
                    $this->cart->removeByTermId($args['id'], $stepId);
                }

                if ($stepId == $args['nextStepId']) {
                    $skip = false;
                }
            }
        }

        $this->setActiveStep($args['id'], $args['nextStepId']);
    }

    /**
     * Get the form template by ajax
     */
    public function getFormAjax()
    {
        $defaults = [
            'id' => null,
            'stepId' => null,
            'page' => 1
        ];

        $args = array_replace_recursive($defaults, $_POST);

        $this->changeState([
            'id' => $args['id'],
            'nextStepId' => $args['stepId'],
            'page' => $args['page']
        ]);

        wp_send_json([
            'content' => $this->getTemplatePart('router', $_POST, ['echo' => false])
        ]);
    }

    /**
     * Handles form skipping
     *
     * @param array $args
     */
    public function skipForm($args)
    {
        $activeStep = $this->getActiveStepId($args['id']);

        $this->excluded->add($args['id'], $activeStep);
        $this->cart->removeByTermId($args['id'], $activeStep);
        $this->setActiveStep($args['id'], $this->getNextStepId($args['id']));
    }

    /**
     * Handles form skipping by ajax
     */
    public function skipFormAjax()
    {
        $this->skipForm($_POST);

        wp_send_json([
            'content' => $this->getTemplatePart('router', $_POST, ['echo' => false])
        ]);
    }

    /**
     * Handles all steps skipping
     *
     * @param array $args
     */
    public function skipAll($args)
    {
        $defaults = [
            'skipAll' => true
        ];

        $args = array_merge($defaults, $args);

        $stepsIds = $this->getStepsIds($args['id']);
        $args['previousStepId'] = $args['stepId'];
        $args['stepId'] = end($stepsIds);

        $this->setActiveStep($args['id'], end($stepsIds));
    }

    /**
     * Handles all steps skipping by ajax
     */
    public function skipAllAjax()
    {
        $this->skipAll($_POST);

        $defaults = [
            'skipAll' => true
        ];

        $_POST = array_merge($defaults, $_POST);

        $stepsIds = $this->getStepsIds($_POST['id']);
        $_POST['previousStepId'] = $_POST['stepId'];
        $_POST['stepId'] = end($stepsIds);

        wp_send_json([
            'content' => $this->getTemplatePart('router', $_POST, ['echo' => false])
        ]);
    }

    /**
     * Reset cart and set the form to the first step
     *
     * @param array $args
     */
    public function resetForm($args)
    {
        $stepsIds = $this->getStepsIds($args['id']);

        $this->excluded->truncate($args['id']);
        $this->cart->truncate($args['id']);
        $this->setActiveStep($args['id'], reset($stepsIds));
    }

    /**
     * Reset cart and set the form to the first step by ajax
     */
    public function resetFormAjax()
    {
        $this->resetForm($_POST);

        wp_send_json([
            'content' => $this->getTemplatePart('router', $_POST, ['echo' => false])
        ]);
    }

    /**
     * Add item to cart by ajax
     */
    public function addItemToCartAjax()
    {
        $defaults = [
            'id' => null,
            'page' => 1,
            'stepId' => null
        ];

        $args = array_merge($defaults, $_POST);

        $this->addItemToCart($args);

        wp_send_json([
            'content' => $this->getTemplatePart(
                'router',
                [
                    'id' => $args['id'],
                    'page' => $args['page']
                ],
                ['echo' => false]
            )
        ]);
    }

    /**
     * Add item to cart
     *
     * @param array $args
     */
    public function addItemToCart($args = [])
    {
        $defaults = [
            'id' => null,
            'stepId' => false,
            'incrementActiveStep' => false
        ];

        $args = array_merge($defaults, $args);

        $this->submitForm($args);
    }

    /**
     * Remove item from cart
     *
     * @param array $args
     */
    public function removeCartItem($args)
    {
        $this->cart->removeByProductId($args['id'], $args['itemId']);
    }

    /**
     * Remove item from cart by ajax
     */
    public function removeCartItemAjax()
    {
        $defaults = [
            'id' => null,
            'page' => 1,
            'itemId' => null
        ];

        $args = array_merge($defaults, $_POST);

        $this->removeCartItem($args);

        wp_send_json([
            'content' => $this->getTemplatePart(
                'router',
                [
                    'id' => $args['id'],
                    'page' => $args['page']
                ],
                ['echo' => false]
            )
        ]);
    }

    /**
     * Get an array of the steps ids which used in the wizard
     *
     * @param integer $postId
     *
     * @return array
     */
    public function getStepsIds($postId)
    {
        $stepsIds = [];

        foreach ($this->getSteps($postId) as $step) {
            $stepsIds[] = $step['id'];
        }

        return $stepsIds;
    }

    /**
     * Get an array of the terms settings which used in the wizard
     *
     * @param integer $postId
     *
     * @return array
     */
    public function getTermsSettings($postId)
    {
        $termsIds = get_post_meta($postId, 'terms_list_settings', 1);
        $termsIds = $termsIds ? $termsIds : [];

        return $termsIds;
    }

    /**
     * Get an array of the steps which used in the wizard
     *
     * @param integer $postId
     *
     * @return array
     */
    public function getSteps($postId)
    {
        $wizardSettings = $this->getWizardSettings($postId);
        $termsSettings = $this->getTermsSettings($postId);
        $steps = [];
        $cart = $this->cart->get($postId);
        $cartProductsIds = [];

        foreach ($cart as $cartItem) {
            $cartProductsIds[] = $cartItem['product_id'];
        }

        $terms = get_terms(
            'product_cat',
            [
                'orderby' => 'include',
                'order' => 'ASC',
                'hide_empty' => true,
                'include' => get_post_meta($postId, 'terms_list_ids', 1),
                'hierarchical' => 0
            ]
        );

        foreach ($terms as $term) {
            $skip = true;
            $dependency = isset($termsSettings[$term->term_id]['required_added_products'])
                ? $termsSettings[$term->term_id]['required_added_products']
                : false;

            // check availability by dependencies
            if ($dependency
                && is_array($dependency)
                && (!isset($wizardSettings['dependencies_disable']) || !$wizardSettings['dependencies_disable'])
            ) {
                foreach ($dependency as $dependencyItem) {
                    // woo v2 support
                    $dependencyItem = is_array($dependencyItem) ? $dependencyItem : explode(',', $dependencyItem);

                    if (count(array_intersect($dependencyItem, $cartProductsIds)) == count($dependencyItem)) {
                        $skip = false;
                    }
                }
            } else {
                // if term have not setting
                $skip = false;
            }

            // skip this term
            if ($skip) {
                continue;
            }

            $steps[$term->term_id] = (array) $term;
            $steps[$term->term_id]['id'] = $term->term_id;

            // change step title if have in setting
            if (isset($termsSettings[$term->term_id]) && isset($termsSettings[$term->term_id]['title'])) {
                $steps[$term->term_id]['name'] = $termsSettings[$term->term_id]['title'];
            }
        }

        if (filter_var($wizardSettings['description_tab_enable'], FILTER_VALIDATE_BOOLEAN)) {
            // add description tab
            $startTabTitle = $wizardSettings['description_tab_title'];
            array_unshift(
                $steps,
                [
                    'id' => 'start',
                    'name' => $startTabTitle ? $startTabTitle : __('Welcome', 'woocommerce-products-wizard')
                ]
            );
        }

        $resultsTabTitle = $wizardSettings['results_tab_title'];

        // add results tab
        array_push(
            $steps,
            [
                'id' => 'result',
                'name' => $resultsTabTitle ? $resultsTabTitle : __('Total', 'woocommerce-products-wizard')
            ]
        );

        return $steps;
    }

    /**
     * Get active wizard step id from the session variable
     *
     * @param integer $postId
     *
     * @return string
     */
    public function getActiveStepId($postId)
    {
        $stepsIds = $this->getStepsIds($postId);
        $activeStep = reset($stepsIds);

        if (isset($_SESSION[$this->activeStepsSessionKey][$postId])
            && in_array($_SESSION[$this->activeStepsSessionKey][$postId], $stepsIds)
        ) {
            $activeStep = $_SESSION[$this->activeStepsSessionKey][$postId];
        }

        return $activeStep;
    }

    /**
     * Get active wizard step from the session variable
     *
     * @param integer $postId
     *
     * @return array
     */
    public function getActiveStep($postId)
    {
        $activeStep = [];
        $activeStepId = $this->getActiveStepId($postId);
        $steps = $this->getSteps($postId);

        foreach ($steps as $step) {
            if ($step['id'] != $activeStepId) {
                continue;
            }

            $activeStep = $step;
        }

        return $activeStep;
    }

    /**
     * Set active wizard step to the session variable
     *
     * @param integer $postId
     * @param integer $step
     */
    public function setActiveStep($postId, $step)
    {
        $_SESSION[$this->activeStepsSessionKey][$postId] = $step;
    }

    /**
     * Get the next active wizard step from the session variable
     *
     * @param integer $postId
     *
     * @return string || boolean
     */
    public function getNextStepId($postId)
    {
        $stepsIds = $this->getStepsIds($postId);
        $activeStep = $this->getActiveStepId($postId);
        $prevStep = false;

        foreach ($stepsIds as $stepId) {
            if ($prevStep == $activeStep) {
                return $stepId;
            }

            $prevStep = $stepId;
        }

        return false;
    }

    /**
     * Get the previous active wizard step from the session variable
     *
     * @param integer $postId
     *
     * @return string || boolean
     */
    public function getPreviousStepId($postId)
    {
        $stepsIds = $this->getStepsIds($postId);
        $activeStep = $this->getActiveStepId($postId);
        $prevStep = false;

        foreach ($stepsIds as $stepId) {
            if ($stepId == $activeStep) {
                return $prevStep;
            }

            $prevStep = $stepId;
        }

        return false;
    }

    /**
     * Add an array of the products to the main woocommerce cart
     *
     * @param integer $postId
     * @param array|boolean $items
     */
    public function addProductsToMainCart($postId, $items = false)
    {
        $stepsIds = $this->getStepsIds($postId);

        // add products to the cart
        $productsAdded = [];
        $items = $items ? $items : $this->cart->get($postId);

        if (!empty($items)) {
            foreach ($items as $item) {
                $this->addProductToMainCart(
                    $item['product_id'],
                    $item['quantity'],
                    isset($item['variation_id']) ? $item['variation_id'] : '',
                    isset($item['variation']) ? $item['variation'] : [],
                    isset($item['data']) ? $item['data'] : []
                );

                $productsAdded[] = $item['product_id'];
            }
        }

        // truncate the cart and the excluded
        $this->cart->truncate($postId);
        $this->excluded->truncate($postId);
        $this->setActiveStep($postId, reset($stepsIds));
    }

    /**
     * Add a product to the main woocommerce cart
     *
     * @param integer        $productId
     * @param integer        $quantity
     * @param string|integer $variationId
     * @param array          $variation
     * @param array          $data
     */
    public function addProductToMainCart(
        $productId,
        $quantity = 1,
        $variationId = '',
        $variation = [],
        $data = []
    ) {
        $cart = WC()->cart->get_cart();
        $cartQuantity = 0;

        // get the same product's quantity from the main cart and remove it
        foreach ($cart as $cartItemKey => $cartItem) {
            if ($cartItem['product_id'] != $productId
                || $cartItem['variation_id'] != $variationId
                || $cartItem['variation'] != $variation
            ) {
                continue;
            }

            $cartQuantity = (int) $cartItem['quantity'];

            WC()->cart->remove_cart_item($cartItemKey);
        }

        WC()->cart->add_to_cart($productId, $quantity + $cartQuantity, $variationId, $variation, $data);

        return;
    }

    /**
     * Return the wizard settings
     *
     * @param integer $postId
     *
     * @return string
     */
    public function getWizardSettings($postId)
    {
        $defaults = [];

        foreach ($this->postSettingsModel as $setting) {
            $defaults[$setting['key']] = $setting['default'];
        }

        $settings = get_post_meta($postId, 'settings', 1);
        $settings = is_array($settings) ? $settings : [];
        $settings = array_merge($defaults, $settings);

        return $settings;
    }

    /**
     * Return term description text
     *
     * @param integer $postId
     * @param integer $termId
     *
     * @return string
     */
    public function getTermDescription($postId, $termId)
    {
        $termsSettings = $this->getTermsSettings($postId);

        if (!in_array($termId, array_keys($termsSettings))
            || !isset($termsSettings[$termId]['description'])
        ) {
            return '';
        }

        return $termsSettings[$termId]['description'];
    }

    /**
     * Return term description position
     *
     * @param integer $postId
     * @param integer $termId
     *
     * @return string
     */
    public function getTermDescriptionPosition($postId, $termId)
    {
        $termsSettings = $this->getTermsSettings($postId);
        $defaultPosition = $this->getWizardSettings($postId)['description_position'];

        if (isset($termsSettings[$termId]['description_position'])) {
            $descriptionPosition = $termsSettings[$termId]['description_position'];

            if (!$descriptionPosition || $descriptionPosition == 'default') {
                $descriptionPosition = $defaultPosition;
            }
        } else {
            $descriptionPosition = 'top';
        }

        return $descriptionPosition;
    }

    /**
     * Return term view template name
     *
     * @param integer $postId
     * @param integer $termId
     *
     * @return string
     */
    public function getTermTemplate($postId, $termId)
    {
        $termsSettings = $this->getTermsSettings($postId);
        $defaultTemplate = $this->getWizardSettings($postId)['template'];
        $defaultTemplate = $defaultTemplate == 'default' ? 'list' : $defaultTemplate;

        $currentTemplate = isset($termsSettings[$termId]['template'])
            && $termsSettings[$termId]['template'] != 'default'
            ? $termsSettings[$termId]['template']
            : false;
        $currentTemplate = $currentTemplate ? $currentTemplate : $defaultTemplate;
        $currentTemplate = $currentTemplate ? $currentTemplate : 'list';

        return $currentTemplate;
    }

    /**
     * Return item view template name
     *
     * @param integer $postId
     * @param integer $termId
     *
     * @return string
     */
    public function getItemTemplate($postId, $termId)
    {
        $termsSettings = $this->getTermsSettings($postId);
        $defaultTemplate = $this->getWizardSettings($postId)['item_template'];
        $defaultTemplate = $defaultTemplate == 'default' ? 'type-1' : $defaultTemplate;

        $currentTemplate = isset($termsSettings[$termId]['item_template'])
            && $termsSettings[$termId]['item_template'] != 'default'
            ? $termsSettings[$termId]['item_template']
            : false;
        $currentTemplate = $currentTemplate ? $currentTemplate : $defaultTemplate;
        $currentTemplate = $currentTemplate ? $currentTemplate : 'type-1';

        return $currentTemplate;
    }

    /**
     * Prepare step products request query considering all conditions
     *
     * @param integer $postId
     * @param integer $termId
     *
     * @return array
     */
    public function getStepProductsIds($postId, $termId)
    {
        $cartProductsIds = [1];
        $excludedPosts = [];
        $productsIds = [];

        $cart = $this->cart->get($postId);
        $settings = $this->getWizardSettings($postId);
        $termSettings = $this->getTermsSettings($postId);

        $includedProductsIds = isset($termSettings[$termId]['included_products'])
            && !empty($termSettings[$termId]['included_products'])
            ? $termSettings[$termId]['included_products']
            : false;

        $excludedProductsIds = isset($termSettings[$termId]['excluded_products'])
            && !empty($termSettings[$termId]['excluded_products'])
            ? $termSettings[$termId]['excluded_products']
            : false;

        // get products and variations ids from cart
        foreach ($cart as $value) {
            if ($value['term_id'] == $termId) {
                continue;
            }

            $cartProductsIds[] = $value['product_id'];

            if (isset($value['variation_id']) && !empty($value['variation_id'])) {
                $cartProductsIds[] = $value['variation_id'];
            }
        }

        // product request by current category
        $queryArgs = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $termId
                ]
            ]
        ];

        // exclude products
        if (is_array($excludedProductsIds)) {
            $queryArgs['post__not_in'] = $excludedProductsIds;
        }

        // query products by ids and order
        if (is_array($includedProductsIds)) {
            $queryArgs['orderby'] = 'post__in';
            $queryArgs['post__in'] = $includedProductsIds;
        }

        $products = get_posts($queryArgs);

        // get excluded posts
        if ($this->excluded->get($postId)) {
            $excludedPosts = get_posts([
                'post_type' => 'product',
                'posts_per_page' => -1,
                'numberposts' => -1,
                'fields' => 'ids',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $this->excluded->get($postId)
                    ]
                ]
            ]);
        }

        // find dependencies
        $cartProductsIdsWithExcluded = array_filter(array_merge($cartProductsIds, $excludedPosts));

        foreach ($products as $product) {
            $skip = true;
            $dependency = get_post_meta($product->ID, 'dependency_ids', 1);
            $_product = new \WC_Product($product->ID);

            // check availability by dependencies
            if ($dependency && (!isset($settings['dependencies_disable']) || !$settings['dependencies_disable'])) {
                foreach ($dependency as $dependencyItem) {
                    $diff = array_intersect($dependencyItem, $cartProductsIdsWithExcluded);

                    if (!empty($diff)) {
                        $skip = false;
                    }
                }
            } else {
                // if product have not meta
                $skip = false;
            }

            // check availability by default woocommerce methods
            if (!$_product->is_visible() || !$_product->is_purchasable() || !$_product->is_in_stock()) {
                $skip = true;
            }

            // add to available products if no skip
            if (!$skip) {
                $productsIds[] = $product->ID;
            }
        }

        return $productsIds;
    }

    /**
     * Return arguments for the products form template part
     *
     * @param integer $postId
     * @param integer $termId
     * @param integer $page
     *
     * @return array
     */
    public function getProductsRequestArgs($postId, $termId, $page = 1)
    {
        $settings = $this->getWizardSettings($postId);
        $termSettings = $this->getTermsSettings($postId);

        // get products by filtered ids
        $productsIds = $this->getStepProductsIds($postId, $termId);

        $singleMode = isset($settings['enable_single_step_mode'])
            && filter_var($settings['enable_single_step_mode'], FILTER_VALIDATE_BOOLEAN);

        $cartProductsIds = [1];
        $activeFormItemsIds = [];
        $cart = $this->cart->get($postId);

        // get products and variations ids from cart
        foreach ($cart as $value) {
            if ($value['term_id'] == $termId) {
                continue;
            }

            $cartProductsIds[] = $value['product_id'];

            if (isset($value['variation_id']) && !empty($value['variation_id'])) {
                $cartProductsIds[] = $value['variation_id'];
            }
        }

        // product request by current category
        $queryArgs = [
            'orderby' => 'post__in',
            'post_type' => 'product',
            'post__in' => $productsIds,
            'posts_per_page' => -1,
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $termId
                ]
            ],
            'paged' => max($page, get_query_var('paged'))
        ];

        // change products per page value
        if (isset($termSettings[$termId]['products_per_page'])
            && (int) $termSettings[$termId]['products_per_page'] != 0
            && !$singleMode
        ) {
            $queryArgs['posts_per_page'] = (int) $termSettings[$termId]['products_per_page'];
            $queryArgs['numberposts'] = (int) $termSettings[$termId]['products_per_page'];
        }

        // set active products if have in the cart
        $productsByTermId = $this->cart->getProductsByTermId($postId, $termId);

        if (!empty($productsByTermId)) {
            foreach ($productsByTermId as $productsByTermIdItem) {
                $activeFormItemsIds[] = $productsByTermIdItem['product_id'];
            }
        }

        $activeFormItemsIds = array_filter($activeFormItemsIds);

        // set the first product as active
        if (empty($activeFormItemsIds)
            && (!isset($termSettings[$termId]['no_selected_items_by_default'])
            || !filter_var($termSettings[$termId]['no_selected_items_by_default'], FILTER_VALIDATE_BOOLEAN))
        ) {
            $productsQuery = get_posts(
                array_replace_recursive(
                    $queryArgs,
                    [
                        'numberposts' => 1
                    ]
                )
            );

            $activeFormItemsIds[] = $productsQuery[0]->ID;
        }

        return [
            'id' => $postId,
            'queryArgs' => $queryArgs,
            'itemTemplate' => 'form/item/' . $this->getItemTemplate($postId, $termId),
            'activeFormItemsIds' => $activeFormItemsIds,
            'productsByTermId' => $productsByTermId,
            'stepId' => $termId,
            'page' => $page,
            'cartProductsIds' => $cartProductsIds
        ];
    }

    /**
     * Makes the products query considering all conditions
     *
     * @param integer $postId
     * @param integer $termId
     * @param integer $page
     *
     * @return string
     */
    public function productsRequest($postId, $termId, $page = 1)
    {
        // php 5.4 support
        $productsIds = $this->getStepProductsIds($postId, $termId);

        if (!empty($productsIds)) {
            $queryArgs = $this->getProductsRequestArgs($postId, $termId, $page);

            return $this->getTemplatePart('form/' . $this->getTermTemplate($postId, $termId), $queryArgs);
        }

        return $this->getTemplatePart('messages/nothing-found');
    }

    /**
     * Check previous step existence
     *
     * @param integer $postId
     *
     * @return boolean
     */
    public function canGoBack($postId)
    {
        $steps = $this->getSteps($postId);
        $activeStep = $this->getActiveStepId($postId);

        return reset($steps)['id'] != $activeStep;
    }

    /**
     * Check next step existence
     *
     * @param integer $postId
     *
     * @return boolean
     */
    public function canGoForward($postId)
    {
        $steps = $this->getSteps($postId);
        $activeStep = $this->getActiveStepId($postId);

        return end($steps)['id'] != $activeStep;
    }

    /**
     * Get, filter and return available product attributes and variables
     *
     * @param array $arguments
     *
     * @return array
     */
    public function getVariationArguments($arguments)
    {
        $variations = $arguments['product']->get_available_variations();
        $attributes = $arguments['product']->get_variation_attributes();
        $attributesToRemove = [];
        $attributesToSave = [];
        $settings = $this->getWizardSettings($arguments['id']);

        foreach ($variations as $variationKey => $variation) {
            $dependency = get_post_meta($variation['variation_id'], 'variation_dependency_ids', 1);

            // change image size
            if (!empty($variations[$variationKey]['image_src'])) {
                $src = wp_get_attachment_image_src(
                    get_post_thumbnail_id($variations[$variationKey]['variation_id']),
                    'shop_catalog'
                );

                $variations[$variationKey]['image_src'] = $src[0];
            } elseif (!empty($variations[$variationKey]['image']['src'])) {
                $src = wp_get_attachment_image_src(
                    get_post_thumbnail_id($variations[$variationKey]['variation_id']),
                    'shop_catalog'
                );

                $variations[$variationKey]['image']['src'] = $src[0];
            }

            // if have no dependencies
            if (!$dependency
                || empty($dependency)
                || (isset($settings['dependencies_disable']) && $settings['dependencies_disable'])
            ) {
                continue;
            }

            // check dependencies
            foreach ($dependency as $dependencyItem) {
                if (count(array_intersect($dependencyItem, $arguments['cartProductsIds'])) == count($dependencyItem)) {
                    foreach ($variation['attributes'] as $attributeItemKey => $attributeItemValue) {
                        $attributesToSave[$attributeItemKey][] = $attributeItemValue;
                    }

                    continue;
                }

                $variations[$variationKey]['variation_is_visible'] = 0;
                $variations[$variationKey]['variation_is_active'] = 0;

                foreach ($variation['attributes'] as $attributeItemKey => $attributeItemValue) {
                    $attributesToRemove[$attributeItemKey][] = $attributeItemValue;
                }
            }
        }

        // clean attributes to remove from attributes to save
        foreach ($attributesToSave as $attributeToSaveKey => $attributeToSaveValue) {
            if (!isset($attributesToRemove[$attributeToSaveKey])) {
                continue;
            }

            $attributesToRemove[$attributeToSaveKey] = array_diff(
                $attributesToRemove[$attributeToSaveKey],
                $attributeToSaveValue
            );
        }

        // find and remove unmet product attributes
        foreach ($attributesToRemove as $attributeToRemoveItemKey => $attributeToRemoveItemValue) {
            foreach ($attributes as $attributeKey => $attributeValue) {
                if (urldecode(str_replace('attribute_', '', $attributeToRemoveItemKey))
                    != mb_strtolower($attributeKey)
                ) {
                    continue;
                }

                foreach ($attributeToRemoveItemValue as $attributeToRemoveItemValueItem) {
                    foreach ($attributeValue as $attributeItemValueItemKey => $attributeItemValueItemValue) {
                        if (urldecode($attributeToRemoveItemValueItem) != urldecode($attributeItemValueItemValue)) {
                            continue;
                        }

                        // unset product attribute
                        unset($attributes[$attributeKey][$attributeItemValueItemKey]);
                    }
                }
            }
        }

        return [
            'variations' => $variations,
            'attributes' => $attributes
        ];
    }

    /**
     * Get pagination items array
     *
     * @param array $args
     *
     * @return array
     */
    public static function getPaginationItems($args)
    {
        $output = [];
        $defaults = [
            'stepId' => null,
            'page' => 1,
            'productsQuery' => false
        ];

        $args = array_replace_recursive($defaults, $args);

        if (!$args['productsQuery']) {
            return [];
        }

        $paginationArgs = apply_filters(
            'wcProductsWizardPaginationArgs',
            [
                'format' => '?paged=%#%',
                'base' => '%_%',
                'total' => $args['productsQuery']->max_num_pages,
                'current' => max($args['page'], get_query_var('paged')),
                'show_all' => false,
                'end_size' => 1,
                'mid_size' => 2,
                'prev_next' => true,
                'prev_text' => __('« Previous', 'woocommerce-products-wizard'),
                'next_text' => __('Next »', 'woocommerce-products-wizard'),
                'type' => 'array'
            ]
        );

        $links = paginate_links($paginationArgs);

        foreach ((array) $links as $link) {
            $xml = new \SimpleXMLElement($link);

            // fix first page link
            if (isset($xml['href']) && $xml['href'] == '') {
                $xml['href'] = '?paged=1';
            }

            // add link attributes
            if (isset($xml['href']) && !empty($xml['href'])) {
                $linkParts = parse_url($xml['href']);
                parse_str($linkParts['query'], $linkPartsQuery);

                if (isset($linkPartsQuery['paged'])) {
                    $xml->addAttribute('data-component', 'wcpw-form-pagination-link');
                    $xml->addAttribute('data-step-id', $args['stepId']);
                    $xml->addAttribute('data-page', $linkPartsQuery['paged']);
                }
            }

            $output[] = [
                'class' => (isset($xml['class']) && strpos($xml['class'], 'current') !== false) ? 'active' : '',
                'innerHtml' => str_replace("<?xml version=\"1.0\"?>\n", '', $xml->asXML())
            ];
        }

        return apply_filters('wcProductsWizardPaginationItems', $output);
    }

    /**
     * Get tabs items array
     *
     * @param integer $id
     *
     * @return array
     */
    public function getTabsItems($id)
    {
        $arguments = [
            'nextStepId' => $this->getNextStepId($id),
            'settings' => $this->getWizardSettings($id),
            'steps' => $this->getSteps($id),
            'stepId' => $this->getActiveStepId($id),
            'skipAll' => false
        ];

        $activeNavItem = false;
        $previousNavItem = false;

        $enableAllTabsAvailability = isset($arguments['settings']['enable_all_tabs_availability'])
            && filter_var($arguments['settings']['enable_all_tabs_availability'], FILTER_VALIDATE_BOOLEAN)
            && isset($arguments['settings']['dependencies_disable'])
            && filter_var($arguments['settings']['dependencies_disable'], FILTER_VALIDATE_BOOLEAN);

        foreach ($arguments['steps'] as $step) {
            if ($arguments['stepId'] == $step['id']) {
                // set active nav item attributes
                $activeNavItem = $step['id'];
                $step['class'] = 'active';
                $step['action'] = 'none';
            } elseif ($step['id'] == $arguments['nextStepId']) {
                // enable the next tab after the active
                $step['class'] = '';
                $step['action'] = $activeNavItem == 'start' || $enableAllTabsAvailability ? 'get' : 'submit';
            } else {
                // other items
                $step['class'] = $activeNavItem && !$enableAllTabsAvailability ? 'disabled' : '';
                $step['action'] = $activeNavItem && !$enableAllTabsAvailability ? 'none' : 'get';
            }

            // if action is "skip all"
            if (!$enableAllTabsAvailability && filter_var($arguments['skipAll'], FILTER_VALIDATE_BOOLEAN)) {
                if ($arguments['previousStepId'] == $step['id']) {
                    $previousNavItem = $step['id'];
                    $step['class'] = '';
                    $step['action'] = 'get';
                } elseif ($arguments['stepId'] == $step['id']) {
                    $step['class'] = 'active';
                    $step['action'] = 'none';
                } elseif (!$previousNavItem) {
                    $step['class'] = '';
                    $step['action'] = 'get';
                } else {
                    $step['class'] = 'disabled';
                    $step['action'] = 'none';
                }
            }
        }
    }
}
