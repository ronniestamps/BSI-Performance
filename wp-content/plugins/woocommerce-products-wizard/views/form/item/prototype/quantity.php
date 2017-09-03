<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;
$stepId = isset($stepId) ? $stepId : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$termsSettings = WCProductsWizard\Instance()->getTermsSettings($id);

$defaults = [
    'id' => $id,
    'class' => 'woocommerce-products-wizard-form-item',
    'product' => null,
    'termSettings' => isset($termsSettings[$stepId]) ? $termsSettings[$stepId] : [],
    'stepId' => $stepId
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$soldIndividually = isset($arguments['termSettings']['sold_individually'])
    ? filter_var($arguments['termSettings']['sold_individually'], FILTER_VALIDATE_BOOLEAN)
    : false;

if (!$arguments['product'] || $arguments['product']->is_sold_individually() || $soldIndividually) {
    return;
}
?>
<div class="<?php echo esc_attr($arguments['class']); ?>-quantity" data-component="wcpw-form-item-quantity">
    <?php
    woocommerce_quantity_input([
        'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $arguments['product']),
        'max_value' => apply_filters(
            'woocommerce_quantity_input_max',
            $arguments['product']->backorders_allowed() ? '' : $arguments['product']->get_stock_quantity(),
            $arguments['product']
        ),
        'input_name' => 'productsToAdd[' . $arguments['product']->get_id() . '][quantity]'
    ]);
    ?>
</div>
