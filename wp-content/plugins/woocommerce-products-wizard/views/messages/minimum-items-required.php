<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'termsSettings' => [],
    'stepId' => WCProductsWizard\Instance()->getActiveStepId($id)
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$minimumProductsToAdd = isset($arguments['termsSettings'][$arguments['stepId']])
    && isset($arguments['termsSettings'][$arguments['stepId']]['minimum_products_to_add'])
    ? $arguments['termsSettings'][$arguments['stepId']]['minimum_products_to_add']
    : 1;
?>
<div class="alert alert-warning">
    <?php
    _e('Minimum selected items are required', 'woocommerce-products-wizard');
    echo ': ' . wp_kses_post($minimumProductsToAdd);
    ?>
</div>
