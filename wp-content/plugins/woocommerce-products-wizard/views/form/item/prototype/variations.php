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
    'cartProductsIds' => null,
    'class' => 'woocommerce-products-wizard-form-item',
    'id' => $id,
    'stepId' => $stepId,
    'product' => null,
    'productsByTermId' => [],
    'termSettings' => isset($termsSettings[$stepId]) ? $termsSettings[$stepId] : []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$defaultVariationType = isset($arguments['termSettings']['variations_type'])
    ? $arguments['termSettings']['variations_type']
    : 'select';

$currentVariationsType = get_post_meta($arguments['product']->get_id(), 'products_wizard_variations_type', 1);

$currentVariationsType = $currentVariationsType && strtolower($currentVariationsType) != 'default'
    ? strtolower($currentVariationsType)
    : $defaultVariationType;

if ($arguments['product']->is_type('variable')) {
    $variationArguments = WCProductsWizard\Instance()->getVariationArguments($arguments);

    $variations = $variationArguments['variations'];
    $attributes = $variationArguments['attributes'];
    ?>
    <div class="variations_form <?php echo esc_attr($arguments['class']); ?>-variations"
        data-product_id="<?php echo esc_attr($arguments['product']->get_id()); ?>"
        data-product_variations="<?php echo esc_attr(json_encode($variations)); ?>"
        data-component="wcpw-form-item-variations">
        <dl class="variations <?php echo esc_attr($arguments['class']); ?>-variations-attributes"
            data-component="wcpw-form-item-variations-attributes"
            data-form-item-variations-attributes-size="<?php echo count($attributes); ?>">
            <?php
            foreach ($attributes as $attributeKey => $attributeValue) {
                WCProductsWizard\Instance()->getTemplatePart(
                    'form/item/prototype/variations/attributes/item/' . $currentVariationsType,
                    array_replace_recursive(
                        $arguments,
                        [
                            'attributeValue' => $attributeValue,
                            'attributeKey' => $attributeKey
                        ]
                    )
                );
            }
            ?>
        </dl>
        <input type="hidden"
            name="productsToAdd[<?php echo esc_attr($arguments['product']->get_id()); ?>][variation_id]"
            value=""
            data-component="wcpw-form-item-variations-variation-id">
    </div>
    <?php
}
?>
