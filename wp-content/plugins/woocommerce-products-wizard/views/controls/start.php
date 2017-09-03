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
    id="wcpw-start-<?php echo esc_attr($arguments['id']); ?>"
    method="POST">
    <input type="hidden" name="id" value="<?php echo esc_attr($arguments['id']); ?>">
    <input type="hidden"
        name="stepId"
        value="<?php echo esc_attr(WCProductsWizard\Instance()->getNextStepId($arguments['id'])); ?>">
</form>
<button class="btn btn-primary woocommerce-products-wizard-start"
    form="wcpw-start-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    name="woocommerce-products-wizard-start"
    value="<?php echo esc_attr($arguments['id']); ?>"
    data-component="wcpw-start wcpw-nav-item"
    data-nav-action="get"
    data-nav-id="<?php echo esc_attr(WCProductsWizard\Instance()->getNextStepId($arguments['id'])); ?>">
    <?php echo wp_kses_post($arguments['settings']['start_button_text']); ?>
</button>
<!--space-->
