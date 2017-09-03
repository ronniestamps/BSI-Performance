<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item'
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<div class="<?php echo esc_attr($arguments['class']); ?>-description"
    data-component="wcpw-form-item-description"
    data-default="<?php echo esc_attr(apply_filters('the_content', get_the_content())); ?>"><?php
    the_content();
    ?></div>
