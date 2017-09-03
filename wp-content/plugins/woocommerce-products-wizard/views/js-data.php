<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$stepId = isset($stepId) ? $stepId : WCProductsWizard\Instance()->getActiveStepId($id);
$defaults = [
    'id' => $id,
    'stepId' => $stepId
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$cartStepsItems = [];
$cart = WCProductsWizard\Instance()->cart->get($id);

foreach ($cart as $cartItem) {
    $cartStepsItems[$cartItem['term_id']][] = $cartItem['product_id'];
}

$thumbnail_id = get_woocommerce_term_meta( $arguments['stepId'], 'thumbnail_id', true ); 

// get the image URL
$image = wp_get_attachment_url( $thumbnail_id ); 
?>
<span data-component="wcpw-js-data"
    data-minimum-item-required-message="<?php
    echo __('Minimum selected items are required', 'woocommerce-products-wizard') . ': %n%';
    ?>"
    data-not-all-options-selected-message="<?php
    _e('Not all options are selected', 'woocommerce-products-wizard');
    ?>"
    data-image="<?php echo $image; ?>"
    data-cart-steps-items="<?php echo esc_attr(wp_json_encode($cartStepsItems)); ?>">
</span>
