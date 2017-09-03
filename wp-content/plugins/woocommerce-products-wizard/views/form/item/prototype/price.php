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
    'product' => null,
    'activeFormItemsIds' => [],
    'class' => 'woocommerce-products-wizard-form-item',
    'termSettings' => isset($termsSettings[$stepId]) ? $termsSettings[$stepId] : [],
    'stepId' => $stepId
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$severalProducts = isset($arguments['termSettings']['several_products'])
    ? filter_var($arguments['termSettings']['several_products'], FILTER_VALIDATE_BOOLEAN)
    : false;

$inputType = $severalProducts ? 'checkbox' : 'radio';
?>
<span class="<?php echo esc_attr($arguments['class']); ?>-price">
    <span class="<?php echo esc_attr($inputType); ?>-inline">
        <label>
            <input type="<?php echo esc_attr($inputType); ?>"
                name="selectedProductsIds[<?php echo esc_attr($arguments['stepId']); ?>][]"
                value="<?php echo esc_attr($arguments['product']->get_id()); ?>"
                <?php
                echo in_array($arguments['product']->get_id(), $arguments['activeFormItemsIds'])
                    ? ' checked="checked"'
                    : '';
                ?>
                data-component="wcpw-form-item-id">
            <span data-component="wcpw-form-item-price"
                data-default="<?php echo htmlspecialchars($arguments['product']->get_price_html()); ?>">
                <?php echo $arguments['product']->get_price_html(); ?>
            </span>
        </label>
        <input type="hidden"
            name="productsToAdd[<?php echo esc_attr($arguments['product']->get_id()); ?>][product_id]"
            value="<?php echo esc_attr($arguments['product']->get_id()); ?>">
    </span>
</span>
