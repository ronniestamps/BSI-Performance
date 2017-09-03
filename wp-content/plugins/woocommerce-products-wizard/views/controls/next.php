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
<button class="btn btn-primary woocommerce-products-wizard-next"
    form="wcpw-form-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    name="woocommerce-products-wizard-submit"
    value="1"
    data-component="wcpw-next wcpw-nav-item"
    data-nav-action="submit">
    <span><?php echo wp_kses_post($arguments['settings']['next_button_text']); ?></span>
</button>
<!--space-->
