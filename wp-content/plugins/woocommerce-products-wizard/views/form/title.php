<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'title' => ''
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<h2 class="woocommerce-products-wizard-form-title">
    <?php echo wp_kses_post($arguments['title']); ?>
</h2>
