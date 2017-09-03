<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'cart' => WCProductsWizard\Instance()->cart->get($id, ['groupByTerm' => true]),
    'settings' => [],
    'steps' => WCProductsWizard\Instance()->getSteps($id),
    'termsSettings' => [],
    'id' => $id
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$notEnoughFullCartTerms = [];

// check is some terms required more than one product to add
foreach ($arguments['steps'] as $step) {
    $minimumProductsToAdd = isset($arguments['termsSettings'][$step['id']]['minimum_products_to_add'])
        ? $arguments['termsSettings'][$step['id']]['minimum_products_to_add']
        : 1;

    if (isset($arguments['cart'][$step['id']]) && count($arguments['cart'][$step['id']]) < $minimumProductsToAdd) {
        $notEnoughFullCartTerms[$step['id']] = $minimumProductsToAdd;
    }
}

// get the first item and key
reset($notEnoughFullCartTerms);
$firstNotEnoughFullCartTermId = key($notEnoughFullCartTerms);
$firstNotEnoughFullCartTermMinimum = current($notEnoughFullCartTerms);

// set form action value
$formAction = !empty($notEnoughFullCartTerms) ? '#' : get_permalink(wc_get_page_id('cart'));
?>
<form class="hidden"
    action="<?php echo esc_attr($formAction); ?>"
    id="wcpw-add-to-cart-<?php echo esc_attr($arguments['id']); ?>"
    method="POST">
    <?php if (empty($notEnoughFullCartTerms)) { ?>
        <input type="hidden"
            name="woocommerce-products-wizard-add-to-cart"
            value="<?php echo esc_attr($arguments['id']); ?>">
    <?php } else { ?>
        <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
        <input type="hidden"
            name="stepId"
            value="<?php echo esc_attr($firstNotEnoughFullCartTermId); ?>">
        <input type="hidden"
            data-component="wcpw-form-minimum-products-to-add"
            name="minimumProductsToAdd"
            value="<?php echo esc_attr($firstNotEnoughFullCartTermMinimum); ?>">
    <?php } ?>
</form>
<button class="btn btn-danger woocommerce-products-wizard-add-to-cart"
    form="wcpw-add-to-cart-<?php echo esc_attr($arguments['id']); ?>"
    type="submit"
    data-component="wcpw-add-to-cart"
    data-meet-minimum-products-to-add="<?php echo empty($notEnoughFullCartTerms) ? 1 : 0; ?>"
    data-step-id="<?php echo esc_attr($firstNotEnoughFullCartTermId); ?>">
    <?php echo wp_kses_post($arguments['settings']['add_to_cart_button_text']); ?>
</button>
<!--space-->
