<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item',
    'product' => null,
    'stepId' => null,
    'cartProductsIds' => null,
    'productsByTermId' => [],
    'attributeKey' => null,
    'attributeValue' => null
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$selected_attributes = $arguments['product']->get_default_attributes();
?>
<dt>
    <label for="<?php echo sanitize_title($arguments['attributeKey']); ?>">
        <?php echo wc_attribute_label($arguments['attributeKey']); ?>
    </label>
</dt>
<dd>
    <?php
    if (is_array($arguments['attributeValue'])) {
        // set active product if have in the cart
        if (isset($_REQUEST['attribute_' . sanitize_title($arguments['attributeKey'])])) {
            $selected_value = $_REQUEST['attribute_' . sanitize_title($arguments['attributeKey'])];
        } elseif (isset($selected_attributes[sanitize_title($arguments['attributeKey'])])) {
            $selected_value = $selected_attributes[sanitize_title($arguments['attributeKey'])];
        } elseif (in_array($arguments['product']->get_id(), array_keys($arguments['productsByTermId']))
            && isset($arguments['productsByTermId'][$arguments['product']->get_id()]['variation']['attribute_'
                . sanitize_title($arguments['attributeKey'])])
        ) {
            $selected_value =
                $arguments['productsByTermId'][$arguments['product']->get_id()]['variation']['attribute_'
                . sanitize_title($arguments['attributeKey'])];
        } else {
            $selected_value = '';
        }

        // Get terms if this is a taxonomy - ordered
        if (taxonomy_exists($arguments['attributeKey'])) {
            $args = [];

            switch (wc_attribute_orderby($arguments['attributeKey'])) {
                case 'name':
                    $args = [
                        'orderby' => 'name',
                        'hide_empty' => false,
                        'menu_order' => false
                    ];
                    break;
                case 'id':
                    $args = [
                        'orderby' => 'id',
                        'order' => 'ASC',
                        'menu_order' => false,
                        'hide_empty' => false
                    ];
                    break;
                case 'menu_order':
                    $args = ['menu_order' => 'ASC', 'hide_empty' => false];
                    break;
                default:
                    // Nothing
                    break;
            }

            $terms = get_terms($arguments['attributeKey'], $args);

            foreach ($terms as $term) {
                if (!in_array($term->slug, $arguments['attributeValue'])) {
                    continue;
                }
                ?>
                <label class="radio-inline">
                    <input type="radio"
                        class="<?php echo esc_attr($arguments['class']); ?>-variations-attributes-item-value"
                        data-component="wcpw-form-item-variations-attributes-item-value"
                        data-name="attribute_<?php echo sanitize_title($arguments['attributeKey']); ?>"
                        name="<?php
                        echo $arguments['product']->get_id()
                            . '-attribute_'
                            . sanitize_title($arguments['attributeKey']);
                        ?>"
                        value="<?php echo esc_attr($term->slug); ?>"
                        <?php
                        if (!$selected_value && reset($terms) == $term) {
                            echo 'checked="checked"';
                        } else {
                            checked(sanitize_title($selected_value), sanitize_title($term->slug));
                        }
                        ?>>
                    <?php echo apply_filters('woocommerce_variation_option_name', $term->name); ?>
                </label>
                <?php
            }
        } else {
            $selected_value = '';

            if (in_array($arguments['product']->get_id(), array_keys($arguments['productsByTermId']))
                && isset($arguments['productsByTermId'][$arguments['product']->get_id()]['variation']['attribute_'
                    . sanitize_title($arguments['attributeKey'])])
            ) {
                $selected_value
                    = $arguments['productsByTermId'][$arguments['product']->get_id()]['variation']['attribute_'
                . sanitize_title($arguments['attributeKey'])];
            }

            foreach ($arguments['attributeValue'] as $option) {
                ?>
                <label class="radio-inline">
                    <input
                        type="radio"
                        class="<?php echo esc_attr($arguments['class']); ?>-variations-attributes-item-value"
                        data-component="wcpw-form-item-variations-attributes-item-value"
                        data-name="attribute_<?php echo sanitize_title($arguments['attributeKey']); ?>"
                        name="<?php
                        echo $arguments['product']->get_id()
                            . '-attribute_'
                            . sanitize_title($arguments['attributeKey']);
                        ?>"
                        value="<?php echo esc_attr($option); ?>"
                        <?php
                        if (!$selected_value && reset($arguments['attributeValue']) == $option) {
                            echo 'checked="checked"';
                        } else {
                            checked(sanitize_title($selected_value), sanitize_title($option));
                        }
                        ?>>
                    <?php echo esc_html(apply_filters('woocommerce_variation_option_name', $option)); ?>
                </label>
                <?php
            }
        }
    }
    ?>
</dd>
