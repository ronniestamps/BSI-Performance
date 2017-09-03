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
<form class="hidden"
    action="#"
    id="wcpw-reset-<?php echo esc_attr($arguments['id']); ?>"
    method="POST">
    <input type="hidden" name="id" value="<?php echo esc_attr($arguments['id']); ?>">
</form>
<button class="btn btn-default woocommerce-products-wizard-reset"
    form="wcpw-reset-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    name="woocommerce-products-wizard-reset"
    value="1"
    data-component="wcpw-reset wcpw-nav-item"
    data-nav-action="reset">
    <?php echo wp_kses_post($arguments['settings']['reset_button_text']); ?>
</button>
<!--space-->
