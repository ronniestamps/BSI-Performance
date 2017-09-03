<?php
namespace WCProductsWizard;

/**
 * WCProductsWizard Admin Class
 *
 * @class Admin
 * @version 3.1.0
 */
class Admin
{
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
     * Admin Constructor.
     *
     * @var $args array
     */
    public function __construct($args = [
        'postSettingsModel' => [],
        'termSettingsModel' => []
    ])
    {
        $this->postSettingsModel = $args['postSettingsModel'];
        $this->termSettingsModel = $args['termSettingsModel'];

        // scripts and styles
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin'], 9);
        add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\Core::enqueueAjaxUrl', 10);

        // post types
        add_action('init', [$this, 'createProductWizardPostType']);
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('save_post', [$this, 'savePostAction']);

        // product variation fields
        add_action('woocommerce_product_after_variable_attributes', [$this, 'productVariationFields'], 10, 3);
        add_action('woocommerce_process_product_meta_variable', [$this, 'productVariationFieldsSave'], 10, 1);
        add_action('woocommerce_save_product_variation', [$this, 'productVariationFieldsSave'], 10, 1);

        // settings page
        add_filter('woocommerce_settings_tabs_array', [$this, 'addSettingsTab'], 50);
        add_action('woocommerce_settings_tabs_products_wizard_settings_tab', [$this, 'settingsTabInit']);
        add_action('woocommerce_update_options_products_wizard_settings_tab', [$this, 'settingsTabUpdate']);

        // ajax
        add_action('wp_ajax_wcpwGetTermsListItemSettingsAjax', [$this, 'getTermsListItemSettingsAjax']);
        add_action('wp_ajax_wcpwSaveTermsListItemSettingsAjax', [$this, 'saveTermsListItemSettingsAjax']);
    }

    /**
     * Register wizard post-type action
     */
    public function createProductWizardPostType()
    {
        $name = 'Products Wizard';

        $args = [
            'label' => $name,
            'labels' => [
                'name' => $name,
                'singular_name' => $name,
                'menu_name' => $name
            ],
            'description' => __(
                'This is where you can add new products wizard items.',
                'woocommerce-products-wizard'
            ),
            'public' => false,
            'show_ui' => true,
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_menu' => current_user_can('manage_woocommerce') ? 'woocommerce' : true,
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => false,
            'supports' => [
                'title',
                'editor'
            ],
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => true
        ];

        register_post_type('wc_product_wizard', $args);
    }

    /**
     * Styles and scripts enqueue in admin.
     */
    public function enqueueAdmin()
    {
        $path = WC_PRODUCTS_WIZARD_DEBUG ? 'src' : 'assets';
        $suffix = WC_PRODUCTS_WIZARD_DEBUG ? '' : '.min';
        $stylesFolder = WC_PRODUCTS_WIZARD_DEBUG ? 'scss' : 'css';

        wp_register_script(
            'select2',
            WC()->plugin_url() . '/assets/js/select2/select2' . $suffix . '.js',
            ['jquery'],
            '4.0.3'
        );

        wp_register_script(
            'wc-enhanced-select',
            WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js',
            ['jquery', 'select2'],
            WC_VERSION
        );

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('select2');
        wp_enqueue_script('wc-enhanced-select');
        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', [], WC_VERSION);

        wp_enqueue_script(
            'wcpw-data-table',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/admin/js/data-table' . $suffix . '.js',
            [
                'jquery',
                'jquery-ui-sortable'
            ],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'wcpw-terms',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/admin/js/terms' . $suffix . '.js',
            [
                'jquery',
                'jquery-ui-sortable'
            ],
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_script(
            'wcpw-hooks',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/admin/js/hooks' . $suffix . '.js',
            'jquery',
            WC_PRODUCTS_WIZARD_VERSION,
            true
        );

        wp_enqueue_style(
            'wcpw-app',
            WC_PRODUCTS_WIZARD_PLUGIN_URL . $path . '/admin/' . $stylesFolder . '/app' . $suffix . '.css',
            [],
            WC_PRODUCTS_WIZARD_VERSION
        );
    }

    /**
     * Save the product meta values
     *
     * @param integer $postId
     */
    public function savePostAction($postId)
    {
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_page', $postId)) {
            return;
        }

        if (!in_array(get_post_type($postId), ['product', 'wc_product_wizard'])) {
            return;
        }

        // save product dependencies
        if (get_post_type($postId) == 'product') {
            $dependenciesMetaKey = 'dependency_ids';
            $ids = [];

            if (isset($_POST[$dependenciesMetaKey]) && !empty($_POST[$dependenciesMetaKey])) {
                foreach ($_POST[$dependenciesMetaKey] as $postItemKey => $postItem) {
                    if (is_array($postItem)) {
                        // woo v3
                        $ids[$postItemKey] = $postItem;
                    } elseif (is_string($postItem)) {
                        // woo v2
                        $ids[$postItemKey] = explode(',', !empty($postItem) ? $postItem : '1');
                    }
                }

                update_post_meta($postId, $dependenciesMetaKey, $ids);
            } else {
                update_post_meta($postId, $dependenciesMetaKey, [['1']]);
            }
        }

        $keys = [
            'products_wizard_variations_type',
            'settings',
            'terms_list_ids'
        ];

        foreach ($keys as $key) {
            if (!isset($_POST[$key])) {
                continue;
            }

            update_post_meta($postId, $key, $_POST[$key]);
        }
    }

    /**
     * Add wizard metaboxes
     */
    public function addMetaBoxes()
    {
        add_meta_box(
            'products-wizard',
            __('Products Wizard', 'woocommerce-products-wizard'),
            [$this, 'productMetaBoxCallback'],
            'product',
            'normal'
        );

        add_meta_box(
            'options',
            __('Options', 'woocommerce-products-wizard'),
            [$this, 'productsWizardPostTypeMetaboxCallback'],
            'wc_product_wizard',
            'normal'
        );
    }

    /**
     * Product page wizard metabox content
     *
     * @param object $post
     */
    public function productMetaBoxCallback($post)
    {
        $currentVariationsType = get_post_meta($post->ID, 'products_wizard_variations_type', 1);
        $dependency = get_post_meta($post->ID, 'dependency_ids', 1);
        $dependency = isset($dependency[0]) ? $dependency : [['1']];

        $variationTypes = apply_filters('wcProductsWizardAdminVariationsType', ['Default', 'Select', 'Radio']);

        // prepare variations types array for the settingFieldView method using
        array_walk(
            $variationTypes,
            function (&$item) {
                $item = [
                    'name' => $item,
                    'value' => $item
                ];
            }
        );
        ?>
        <p>
            <?php _e('Dependencies', 'woocommerce-products-wizard'); ?>
        </p>
        <?php
        self::settingFieldView(
            [
                'label' => 'Dependencies',
                'type' => 'data-table',
                'key' => 'dependency_ids',
                'default' => [],
                'showHeader' => false,
                'values' => [
                    [
                        'label' => 'Products',
                        'key' => 'dependency_ids',
                        'type' => 'wc-product-search',
                        'action' => 'woocommerce_json_search_products_and_variations',
                        'default' => []
                    ]
                ]
            ],
            [
                'values' => [
                    'dependency_ids' => $dependency
                ]
            ]
        );
        ?>
        <p>
            <label for="products-wizard-variations-type">
                <?php _e('Variation template', 'woocommerce-products-wizard'); ?>
            </label>
        </p>
        <?php
        self::settingFieldView(
            [
                'label' => 'Variation template',
                'type' => 'select',
                'key' => 'products_wizard_variations_type',
                'default' => [],
                'values' => $variationTypes
            ],
            [
                'values' => [
                    'products_wizard_variations_type' => $currentVariationsType
                ]
            ]
        );
    }

    /**
     * Products wizard post type metabox content
     *
     * @param object $post
     */
    public function productsWizardPostTypeMetaboxCallback($post)
    {
        // terms list
        add_thickbox();

        function getWizardTerms($args = [], $level = 0)
        {
            $defaults = [
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'hierarchical' => true,
                'parent' => 0
            ];

            $args = array_merge($defaults, $args);
            $allTerms = [];
            $terms = get_terms($args);

            foreach ($terms as $term) {
                $term->name = str_repeat('-', $level) . $term->name;
                $allTerms[$term->term_id] = $term;
                
                $allTerms = $allTerms
                    + getWizardTerms(
                        [
                            'parent' => $term->term_id
                        ],
                        $level + 1
                    );
            }

            return $allTerms;
        }

        $allTerms = getWizardTerms();
        $selectedTermsIds = get_post_meta(get_the_ID(), 'terms_list_ids', 1);
        $selectedTermsIds = $selectedTermsIds ? $selectedTermsIds : [];
        $settings = get_post_meta(get_the_ID(), 'settings', 1);

        $defaultSettingsUrl = [
            'action' => 'wcpwGetTermsListItemSettingsAjax',
            'post_id' => $post->ID,
            'term_id' => '%TERM_ID%'
        ];
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="woocommerce-products-wizard-shortcode">
                        <?php _e('Using', 'woocommerce-products-wizard'); ?>
                    </label>
                </th>
                <td>
                    <input type="text"
                        id="woocommerce-products-wizard-shortcode"
                        readonly
                        style="width: 100%;"
                        value="<?php echo esc_attr('[woocommerce-products-wizard id="' . get_the_ID() . '"]'); ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcpw-terms-select">
                        <?php _e('Select and add categories', 'woocommerce-products-wizard'); ?>
                    </label>
                </th>
                <td>
                    <div data-component="wcpw-terms">
                        <select name="wcpw-terms-select"
                            id="wcpw-terms-select"
                            data-component="wcpw-terms-select">
                            <option value=""></option>
                            <?php foreach ($allTerms as $allTermsItem) { ?>
                                <option value="<?php echo esc_attr($allTermsItem->term_id); ?>"<?php
                                echo in_array($allTermsItem->term_id, $selectedTermsIds) ? ' class="hidden"' : '';
                                ?>>
                                    <?php echo wp_kses_post($allTermsItem->name); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button class="button" data-component="wcpw-terms-add">+</button>
                        <table class="form-table" data-component="wcpw-terms-list">
                            <tbody>
                                <?php
                                $counter = 0;

                                foreach ($selectedTermsIds as $selectedTermId) {
                                    if (!in_array($selectedTermId, array_keys($allTerms))) {
                                        continue;
                                    }

                                    $selectedTerm = $allTerms[$selectedTermId];

                                    $settingsUrl = [
                                        'action' => 'wcpwGetTermsListItemSettingsAjax',
                                        'post_id' => $post->ID,
                                        'term_id' => $selectedTerm->term_id
                                    ];
                                    ?>
                                    <tr class="form-group"
                                        data-component="wcpw-terms-list-item"
                                        data-id="<?php echo esc_attr($selectedTerm->term_id); ?>">
                                        <td>
                                            <span data-component="wcpw-terms-list-item-name">
                                                <?php echo wp_kses_post($selectedTerm->name); ?>
                                            </span>
                                            <input type="hidden"
                                                data-component="wcpw-terms-list-item-id"
                                                name="terms_list_ids[<?php echo esc_attr($selectedTerm->term_id); ?>]"
                                                value="<?php echo esc_attr($selectedTerm->term_id); ?>">
                                        </td>
                                        <td style="width: 1%;">
                                            <button class="button"
                                                data-component="wcpw-terms-list-item-settings"
                                                data-settings="<?php echo esc_attr(wp_json_encode($settingsUrl)); ?>">
                                                <?php _e('Settings', 'woocommerce-products-wizard'); ?>
                                            </button>
                                        </td>
                                        <td style="width: 1%;">
                                            <button class="button" 
                                                data-component="wcpw-terms-list-item-remove">&times;</button>
                                        </td>
                                    </tr>
                                    <?php
                                    $counter++;
                                }
                                ?>
                            </tbody>
                            <tfoot style="display: none;">
                                <tr class="form-group" data-component="wcpw-terms-list-item-template">
                                    <td>
                                        <span data-component="wcpw-terms-list-item-name"></span>
                                        <input type="hidden"
                                            data-component="wcpw-terms-list-item-id">
                                    </td>
                                    <td style="width: 1%;">
                                        <button class="button"
                                            data-component="wcpw-terms-list-item-settings"
                                            data-settings="<?php
                                            echo esc_attr(wp_json_encode($defaultSettingsUrl));
                                            ?>">
                                            <?php _e('Settings', 'woocommerce-products-wizard'); ?>
                                        </button>
                                    </td>
                                    <td style="width: 1%;">
                                        <button class="button" 
                                            data-component="wcpw-terms-list-item-remove">&times;</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </td>
            </tr>
            <?php foreach ($this->postSettingsModel as $setting) { ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="<?php echo esc_attr($setting['key']); ?>">
                            <?php _e($setting['label'], 'woocommerce-products-wizard'); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                        self::settingFieldView(
                            $setting,
                            [
                                'values' => $settings,
                                'namePattern' => 'settings[%key%]'
                            ]
                        );
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php
    }

    /**
     * Get wizard term settings-form by ajax
     */
    public function getTermsListItemSettingsAjax()
    {
        $this->getTermsListItemSettingsForm($_GET);

        die();
    }

    /**
     * Get wizard term settings-form
     *
     * @param array $args
     *
     * @throws \Exception if empty term or post id
     */
    public function getTermsListItemSettingsForm($args)
    {
        $postId = $args['post_id'];
        $termId = $args['term_id'];

        if (!$termId || !$postId) {
            throw new \Exception('Empty term or post id');
        }

        $settings = get_post_meta($postId, 'terms_list_settings', 1);
        $settings = isset($settings[$termId]) ? $settings[$termId] : [];
        $term = get_term($termId, 'product_cat');

        $termSettingsModel = [
            'title' => [
                'label' => 'Title',
                'key' => 'title',
                'type' => 'text',
                'default' => $term->name
            ]
        ];

        $termSettingsModel = array_replace_recursive($this->termSettingsModel, $termSettingsModel);
        ?>
        <form action="#"
            data-component="wcpw-terms-list-item-settings-form"
            data-term-id="<?php echo esc_attr($termId); ?>"
            data-post-id="<?php echo esc_attr($postId); ?>">
            <table class="form-table">
                <?php foreach ($termSettingsModel as $setting) { ?>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="<?php echo esc_attr($setting['key']); ?>">
                                <?php _e($setting['label'], 'woocommerce-products-wizard'); ?>
                            </label>
                        </th>
                        <td>
                            <?php
                            self::settingFieldView(
                                $setting,
                                [
                                    'values' => $settings
                                ]
                            );
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="form-field">
                    <td colspan="2">
                        <button class="button button-primary button-large"
                            data-component="wcpw-terms-list-item-settings-save">
                            <?php _e('Save', 'woocommerce-products-wizard'); ?>
                        </button>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }

    /**
     * Save wizard term settings-form by ajax
     */
    public function saveTermsListItemSettingsAjax()
    {
        $args = $_POST;
        $values = [];
        parse_str($args['values'], $values);
        $args['values'] = $values;

        $this->saveTermsListItemSettings($args);

        die();
    }

    /**
     * Save wizard term settings-form
     *
     * @param array $args
     *
     * @throws \Exception if empty term or post id
     */
    public function saveTermsListItemSettings($args)
    {
        $postId = $args['post_id'];
        $termId = $args['term_id'];

        if (!$termId || !$postId) {
            throw new \Exception('Empty term or post id');
        }

        $settings = get_post_meta($postId, 'terms_list_settings', 1);
        $settings = is_array($settings) ? $settings : [];
        $settings[$termId] = isset($args['values']) ? $args['values'] : [];

        update_post_meta($postId, 'terms_list_settings', $settings);
    }

    /**
     * Add wizard metabox to the product variations edit block
     *
     * @param integer $loop
     * @param array   $variationData
     * @param object  $variation
     */
    public function productVariationFields($loop, $variationData, $variation)
    {
        $dependency = get_post_meta($variation->ID, 'variation_dependency_ids', 1);
        $dependency = (isset($dependency[0])) ? $dependency : [['1']];
        ?>
        <div>
            <p>
                <?php _e('Dependencies', 'woocommerce-products-wizard'); ?>
            </p>
            <?php
            self::settingFieldView(
                [
                    'label' => 'Dependencies',
                    'type' => 'data-table',
                    'key' => 'variation_dependency_ids',
                    'default' => [],
                    'showHeader' => false,
                    'values' => [
                        [
                            'label' => 'Products',
                            'key' => 'variation_dependency_ids',
                            'type' => 'wc-product-search',
                            'action' => 'woocommerce_json_search_products_and_variations',
                            'default' => []
                        ]
                    ]
                ],
                [
                    'namePattern' => '%key%[' . $loop . ']',
                    'values' => [
                        'variation_dependency_ids' => $dependency,
                    ]
                ]
            );
            ?>
        </div>
        <?php
    }

    /**
     * Save the product variations meta values
     */
    public function productVariationFieldsSave()
    {
        if (!$_POST['variable_post_id']) {
            return;
        }

        $variablePostIds = $_POST['variable_post_id'];
        $dependenciesMetaKey = 'variation_dependency_ids';

        // save dependencies
        foreach ($variablePostIds as $variablePostKey => $variablePostId) {
            $ids = [];

            if (isset($_POST[$dependenciesMetaKey][$variablePostKey]) && !empty($_POST[$dependenciesMetaKey])) {
                foreach ($_POST[$dependenciesMetaKey][$variablePostKey] as $postItemKey => $postItem) {
                    if (is_array($_POST[$dependenciesMetaKey][$variablePostKey])) {
                        // woo v3
                        $ids[$postItemKey] = $postItem;
                    } elseif (is_string($_POST[$dependenciesMetaKey][$variablePostKey])) {
                        // woo v2
                        $ids[$postItemKey] = explode(',', !empty($postItem) ? $postItem : '1');
                    }
                }

                update_post_meta($variablePostIds[$variablePostKey], $dependenciesMetaKey, $ids);
            } else {
                update_post_meta($variablePostIds[$variablePostKey], $dependenciesMetaKey, [['1']]);
            }
        }
    }

    /**
     * Add wizard page to the woocommerce options page
     *
     * @param array $settings_tabs
     *
     * @return array $settings_tabs
     */
    public function addSettingsTab($settings_tabs)
    {
        $settings_tabs['products_wizard_settings_tab'] = 'Products Wizard';

        return $settings_tabs;
    }

    /**
     * Return an array of the global plugin settings
     *
     * @return array
     */
    public function getGlobalSettingsModel()
    {
        return [
            'includeFullStylesFile' => [
                'name' => __('Include full styles file', 'woocommerce-products-wizard'),
                'default' => 'yes',
                'type' => 'checkbox',
                'id' => 'woocommerce_products_wizard_include_full_styles_file'
            ]
        ];
    }

    /**
     * Init wizard option page fields
     */
    public function settingsTabInit()
    {
        woocommerce_admin_fields(
            array_merge(
                [
                    'section_title' => [
                        'name' => 'Products Wizard',
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'WCProdWizSettingsTab_section_title'
                    ]
                ],
                $this->getGlobalSettingsModel()
            )
        );

        woocommerce_admin_fields([
            'section_end' => [
                'type' => 'sectionend',
                'id' => 'WCProdWizSettingsTab_section_end'
            ]
        ]);
    }

    /**
     * Save wizard option page fields
     */
    public function settingsTabUpdate()
    {
        woocommerce_update_options($this->getGlobalSettingsModel());
    }

    /**
     * Generate html-field from passed args
     *
     * @param array $modelItem
     * @param array $args
     */
    public static function settingFieldView($modelItem, $args = [])
    {
        global $woocommerce;

        $defaultArgs = [
            'values' => [],
            'namePattern' => '%key%'
        ];

        $args = array_replace_recursive($defaultArgs, $args);

        // create name from pattern
        $name = str_replace('%key%', $modelItem['key'], $args['namePattern']);

        // define value
        $value = isset($args['values'][$modelItem['key']])
            ? $args['values'][$modelItem['key']]
            : $modelItem['default'];

        // is data-table template
        $asTemplate = isset($args['asTemplate']) ? $args['asTemplate'] : false;

        echo isset($modelItem['before']) ? $modelItem['before'] : '';

        if ($modelItem['type'] == 'text') {
            ?>
            <input id="<?php echo esc_attr($modelItem['key']); ?>"
                <?php echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" '; ?>
                type="text"
                value="<?php echo esc_attr($value); ?>"
                style="width: 100%;">
            <?php
        } elseif ($modelItem['type'] == 'number') {
            ?>
            <input id="<?php echo esc_attr($modelItem['key']); ?>"
                <?php echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" '; ?>
                type="number"
                value="<?php echo esc_attr($value); ?>"
                <?php
                echo isset($modelItem['min'])
                    ? ' min="' . esc_attr($modelItem['min']) . '" '
                    : '';
                echo isset($modelItem['step'])
                    ? ' step="' . esc_attr($modelItem['step']) . '" '
                    : '';
                ?>
                style="width: 100%;">
            <?php
        } elseif ($modelItem['type'] == 'checkbox') {
            $isChecked = (isset($args['values'][$modelItem['key']])
                    && filter_var($args['values'][$modelItem['key']], FILTER_VALIDATE_BOOLEAN))
                || (!isset($args['values'][$modelItem['key']])
                    && isset($modelItem['default'])
                    && $modelItem['default']);
            ?>
            <input type="hidden"
                <?php echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" '; ?>
                value="0">
            <input id="<?php echo esc_attr($modelItem['key']); ?>"
                <?php echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" '; ?>
                type="checkbox"
                value="1"
                <?php
                echo $isChecked
                    ? ' checked="checked" '
                    : '';
                ?>>
            <?php
        } elseif ($modelItem['type'] == 'textarea') {
            ?>
            <textarea
                <?php echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" '; ?>
                cols="30"
                rows="10"><?php echo $value; ?></textarea>
            <?php
        } elseif ($modelItem['type'] == 'data-table') {
            $items = isset($args['values'][$modelItem['key']])
                ? $args['values'][$modelItem['key']]
                : $modelItem['default'];

            $items = empty($items) ? [[]] : $items;

            $isSingleValue = count($modelItem['values']) <= 1;
            ?>
            <table class="form-table" data-component="wcpw-data-table">
                <?php if (isset($modelItem['showHeader']) && $modelItem['showHeader']) { ?>
                    <thead>
                        <tr>
                            <td></td>
                            <?php foreach ($modelItem['values'] as $modelItemValue) { ?>
                                <th><?php echo wp_kses_post($modelItemValue['label']); ?></th>
                            <?php } ?>
                            <td></td>
                        </tr>
                    </thead>
                <?php } ?>
                <tbody>
                    <?php foreach ($items as $itemKey => $itemValue) { ?>
                        <tr data-component="wcpw-data-table-item">
                            <td style="width: 1%;">
                                <span class="button" data-component="wcpw-data-table-item-add">+</span>
                            </td>
                            <?php foreach ($modelItem['values'] as $modelItemValue) { ?>
                                <td>
                                    <?php
                                    $namePattern = $name . '[' . $itemKey . ']'
                                        . (!$isSingleValue ? '[' . $modelItemValue['key'] . ']' : '');

                                    $values = isset($args['values'][$modelItem['key']][$itemKey])
                                        ? [
                                            $modelItem['key'] => $args['values'][$modelItem['key']][$itemKey]
                                        ]
                                        : [];

                                    self::settingFieldView(
                                        $modelItemValue,
                                        [
                                            'values' => $values,
                                            'namePattern' => $namePattern
                                        ]
                                    );
                                    ?>
                                </td>
                            <?php } ?>
                            <td style="width: 1%;">
                                <span class="button" data-component="wcpw-data-table-item-remove">-</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot style="display: none;">
                    <tr data-component="wcpw-data-table-item-template">
                        <td style="width: 1%;">
                            <span class="button" data-component="wcpw-data-table-item-add">+</span>
                        </td>
                        <?php foreach ($modelItem['values'] as $modelItemValue) { ?>
                            <td>
                                <?php
                                $namePattern = $name . '[0]'
                                    . (!$isSingleValue ? '[' . $modelItemValue['key'] . ']' : '');

                                self::settingFieldView(
                                    $modelItemValue,
                                    [
                                        'values' => [],
                                        'namePattern' => $namePattern,
                                        'asTemplate' => true
                                    ]
                                );
                                ?>
                            </td>
                        <?php } ?>
                        <td style="width: 1%;">
                            <span class="button" data-component="wcpw-data-table-item-remove">-</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php
        } elseif ($modelItem['type'] == 'select') {
            ?>
            <select id="<?php echo esc_attr($modelItem['key']); ?>"
                <?php echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" '; ?>
                style="width: 100%;">
                <?php foreach ($modelItem['values'] as $modelItemValue) { ?>
                    <option value="<?php echo esc_attr($modelItemValue['value']); ?>"<?php
                    echo isset($args['values'][$modelItem['key']])
                    && $modelItemValue['value'] == $args['values'][$modelItem['key']]
                        ? ' selected="selected"'
                        : '';
                    ?>>
                        <?php echo wp_kses_post($modelItemValue['name']); ?>
                    </option>
                <?php } ?>
            </select>
            <?php
        } elseif ($modelItem['type'] == 'wc-product-search') {
            $default = [
                'action' => 'woocommerce_json_search_products_and_variations',
                'exclude' => 0,
                'multiple' => true,
                'placeholder' => __('Search for a product&hellip;', 'woocommerce')
            ];

            $defaultQueryArgs = [
                'post_type' => ['product', 'product_variation'],
                'include' => $value
            ];

            $queryArgs = isset($modelItem['queryArgs'])
                ? array_replace_recursive($defaultQueryArgs, $modelItem['queryArgs'])
                : $defaultQueryArgs;

            $inputAttributes = array_replace_recursive($default, $modelItem);
            $isMultiply = filter_var($inputAttributes['multiple'], FILTER_VALIDATE_BOOLEAN);
            $products = [];

            if (!$asTemplate && !empty($value)) {
                $productsPosts = get_posts($queryArgs);

                foreach ($productsPosts as $productsItem) {
                    $products[$productsItem->ID] = rawurldecode(
                        $productsItem->post_title . ' (#' . $productsItem->ID . ')'
                    );
                }
            }

            if (version_compare($woocommerce->version, '3.0.0', '>=')) {
                ?>
                <select style="width: 100%;"
                    <?php
                    echo $isMultiply ? 'multiple="multiple" ' : '';
                    echo ($asTemplate ? 'data-make-' : '') . 'class="wc-product-search" ';
                    echo ($asTemplate ? 'data-make-' : '')
                        . 'name="' . esc_attr($name) . ($isMultiply ? '[]' : '') . '" ';
                    ?>
                    data-placeholder="<?php echo esc_attr($inputAttributes['placeholder']); ?>"
                    data-multiple="<?php echo esc_attr(var_export($isMultiply, true)); ?>"
                    data-action="<?php echo esc_attr($inputAttributes['action']); ?>"
                    data-exclude="<?php echo esc_attr($inputAttributes['exclude']); ?>">
                    <?php
                    foreach ($products as $productId => $productTitle) {
                        echo '<option value="'
                            . esc_attr($productId) . '"'
                            . selected(true, true, false) . '>'
                            . wp_kses_post($productTitle)
                            . '</option>';
                    }
                    ?>
                </select>
            <?php
            } else {
                ?>
                <input type="text"
                    style="width: 100%;"
                    <?php
                    echo ($asTemplate ? 'data-make-' : '') . 'class="wc-product-search" ';
                    echo ($asTemplate ? 'data-make-' : '') . 'name="' . esc_attr($name) . '" ';
                    ?>
                    data-placeholder="<?php echo esc_attr($inputAttributes['placeholder']); ?>"
                    data-multiple="<?php echo esc_attr(var_export($isMultiply, true)); ?>"
                    data-action="<?php echo esc_attr($inputAttributes['action']); ?>"
                    data-exclude="<?php echo esc_attr($inputAttributes['exclude']); ?>"
                    data-selected="<?php echo esc_attr(wp_json_encode($value)); ?>"
                    value="<?php echo esc_attr(implode(',', array_keys($value))); ?>">
                <?php
            }
        } elseif ($modelItem['type'] == 'thumbnail') {
            ?>
            <div data-component="wcpw-thumbnail">
                <div data-component="wcpw-thumbnail-image" style="max-width: 150px;">
                    <?php if ($value) { ?>
                        <img src="<?php
                        echo esc_attr(wp_get_attachment_image_src($value, 'thumbnail')[0]);
                        ?>"
                            alt="<?php echo esc_attr(get_the_title($value)); ?>">
                    <?php } ?>
                </div>
                <input data-component="wcpw-thumbnail-id"
                    type="hidden"
                    name="<?php echo esc_attr($name); ?>"
                    value="<?php echo esc_attr($value); ?>">
                <p class="hide-if-no-js">
                    Image:
                    <a href="#" data-component="wcpw-thumbnail-set" role="button">
                        <?php _e('Set', 'windows-calculator'); ?>
                    </a>
                    <!--space-->
                    /
                    <!--space-->
                    <a href="#" data-component="wcpw-thumbnail-remove" role="button">
                        <?php _e('Remove', 'windows-calculator'); ?>
                    </a>
                </p>
            </div>
            <?php
        }

        echo isset($modelItem['after']) ? $modelItem['after'] : '';

        if (isset($modelItem['description'])) {
            ?>
            <p class="description">
                <?php echo wp_kses_post($modelItem['description']); ?>
            </p>
            <?php
        }
    }
}
