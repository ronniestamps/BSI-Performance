<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item'
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<h3 class="<?php echo esc_attr($arguments['class']); ?>-title panel-title">
    <?php the_title(); ?>
</h3>
