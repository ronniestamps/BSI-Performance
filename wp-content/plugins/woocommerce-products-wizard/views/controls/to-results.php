<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'id' => false,
    'stepId' => WCProductsWizard\Instance()->getActiveStepId($id),
    'settings' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<form class="hidden"
    action="#"
    id="wcpw-to-results-<?php echo esc_attr($arguments['id']); ?>"
    method="POST">
    <input type="hidden" name="id" value="<?php echo esc_attr($arguments['id']); ?>">
    <input type="hidden" name="stepId" value="<?php echo esc_attr($arguments['stepId']); ?>">
</form>
<button class="btn btn-default woocommerce-products-wizard-to-results"
    form="wcpw-to-results-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    name="woocommerce-products-wizard-skip-all"
    value="1"
    data-component="wcpw-to-results wcpw-nav-item"
    data-nav-action="skip-all"
    data-nav-id="<?php echo esc_attr($arguments['stepId']); ?>">
    <?php echo wp_kses_post($arguments['settings']['to_results_button_text']); ?>
</button>
<!--space-->
