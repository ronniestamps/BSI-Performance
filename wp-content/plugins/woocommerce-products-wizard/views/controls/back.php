<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'id' => false,
    'settings' => [],
    'skipAll' => false
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

if (!$arguments['id']) {
    throw new Exception('Empty wizard id');
}

$previousStepId = $arguments['skipAll']
    ? $arguments['previousStepId']
    : WCProductsWizard\Instance()->getPreviousStepId($arguments['id']);
?>
<form class="hidden"
    action="#"
    id="wcpw-back-<?php echo esc_attr($arguments['id']); ?>"
    method="POST">
    <input type="hidden" name="id" value="<?php echo esc_attr($arguments['id']); ?>">
    <input type="hidden" name="stepId" value="<?php echo esc_attr($previousStepId); ?>">
</form>
<button class="btn btn-default woocommerce-products-wizard-back"
    form="wcpw-back-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    name="woocommerce-products-wizard-back"
    value="<?php echo esc_attr($arguments['id']); ?>"
    data-component="wcpw-back wcpw-nav-item"
    data-nav-action="get"
    data-nav-id="<?php echo esc_attr($previousStepId); ?>">
    <span><?php echo wp_kses_post($arguments['settings']['back_button_text']); ?></span>
</button>
<!--space-->
