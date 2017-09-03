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
    'cart' => WCProductsWizard\Instance()->cart->get($id),
    'class' => 'woocommerce-products-wizard-form-item',
    'id' => $id,
    'product' => null,
    'settings' => WCProductsWizard\Instance()->getWizardSettings($id),
    'stepId' => $stepId,
    'termSettings' => isset($termsSettings[$stepId]) ? $termsSettings[$stepId] : []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$isInCart = WCProductsWizard\Instance()->cart->productIsset($arguments['id'], $arguments['product']->get_id());

$individualControls = isset($arguments['termSettings']['individual_controls'])
    ? filter_var($arguments['termSettings']['individual_controls'], FILTER_VALIDATE_BOOLEAN)
    : false;

$singleMode = isset($arguments['settings']['enable_single_step_mode'])
    && filter_var($arguments['settings']['enable_single_step_mode'], FILTER_VALIDATE_BOOLEAN);

if ($individualControls || $singleMode) {
    if ($isInCart) {
        ?>
        <button class="btn btn-danger <?php echo esc_attr($arguments['class']); ?>-remove-from-cart"
            data-component="wcpw-form-item-remove-from-cart"
            data-item-id="<?php echo esc_attr($arguments['product']->get_id()); ?>">
            <?php _e('Remove', 'woocommerce-products-wizard'); ?>
        </button>
        <!--space-->
        <?php
    } else {
        ?>
        <button class="btn btn-primary <?php echo esc_attr($arguments['class']); ?>-add-to-cart"
            data-component="wcpw-form-item-add-to-cart"
            data-item-id="<?php echo esc_attr($arguments['product']->get_id()); ?>">
            <?php _e('Add to cart', 'woocommerce-products-wizard'); ?>
        </button>
        <!--space-->
        <?php
    }
}
?>
