<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'id' => false
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

if (!$arguments['id']) {
    throw new Exception('Empty wizard id');
}
?>
<div class="clearfix woocommerce-products-wizard-start-description">
    <?php echo apply_filters('the_content', get_post_field('post_content', $arguments['id'])); ?>
</div>
