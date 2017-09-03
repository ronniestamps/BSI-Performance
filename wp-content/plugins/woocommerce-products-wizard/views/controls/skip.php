<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'id' => false,
    'settings' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

if (!$arguments['id']) {
    throw new Exception('Empty wizard id');
}
?>
<button class="btn btn-default woocommerce-products-wizard-skip"
    form="wcpw-form-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    name="woocommerce-products-wizard-skip"
    value="1"
    data-component="wcpw-skip wcpw-nav-item"
    data-nav-action="skip">
    <?php echo wp_kses_post($arguments['settings']['skip_button_text']); ?>
</button>
<!--space-->
