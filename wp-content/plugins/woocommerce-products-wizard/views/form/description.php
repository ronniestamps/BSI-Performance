<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'description' => ''
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<div class="woocommerce-products-wizard-form-description">
    <?php echo apply_filters('the_content', $arguments['description']); ?>
</div>
