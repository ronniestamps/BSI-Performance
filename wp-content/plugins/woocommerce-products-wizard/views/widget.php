<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'cart' => WCProductsWizard\Instance()->cart->get($id),
    'id' => $id,
    'settings' => [],
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<section class="wcpw-widget" data-component="wcpw-widget">
    <?php
    if (empty($arguments['cart'])) {
        WCProductsWizard\Instance()->getTemplatePart('messages/cart-is-empty');
    } else {
        ?>
        <table class="table table-bordered table-hover">
            <tbody>
                <?php
                foreach ($arguments['cart'] as $cart_item_key => $cart_item) {
                    $productId = isset($cart_item['variation_id'])
                        ? $cart_item['variation_id']
                        : $cart_item['product_id'];
                    $_product = wc_get_product($productId);

                    if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) {
                        continue;
                    }
                    ?>
                    <tr class="woocommerce-products-wizard-widget-item
                    <?php
                    echo esc_attr(
                        apply_filters(
                            'woocommerce_cart_item_class',
                            'cart_item',
                            $cart_item,
                            $cart_item_key
                        )
                    );
                    ?>">
                        <td class="woocommerce-products-wizard-widget-item-thumbnail-wrapper">
                            <figure class="woocommerce-products-wizard-widget-item-thumbnail">
                                <?php
                                $thumbnail = apply_filters(
                                    'woocommerce_cart_item_thumbnail',
                                    $_product->get_image('shop_thumbnail', ['class' => 'img-thumbnail']),
                                    $cart_item,
                                    $cart_item_key
                                );

                                $href = wp_get_attachment_image_src($_product->get_image_id(), 'large');

                                if (!$_product->is_visible()) {
                                    echo $thumbnail;
                                } else {
                                    echo '<a href="'
                                        . $href[0]
                                        . '" data-rel="prettyPhoto" rel="lightbox">'
                                        . $thumbnail
                                        . '</a>';
                                }
                                ?>
                            </figure>
                            <?php
                            if (isset($arguments['settings']['enable_remove_button'])
                                && $arguments['settings']['enable_remove_button']
                            ) {
                                ?>
                                <button class="btn btn-default btn-xs woocommerce-products-wizard-widget-item-remove"
                                    data-component="wcpw-form-item-remove-from-cart"
                                    data-item-id="<?php echo esc_attr($cart_item['product_id']); ?>">
                                    <?php _e('Remove', 'woocommerce-products-wizard'); ?>
                                </button>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="woocommerce-products-wizard-widget-item-title-wrapper">
                            <strong class="woocommerce-products-wizard-widget-item-title">
                                <?php echo $_product->get_name(); ?>
                            </strong>
                            <div class="woocommerce-products-wizard-widget-item-data">
                                <?php
                                // Localization variations
                                $cartItemLocalized = $cart_item;

                                if (isset($cart_item['variation'])) {
                                    foreach ($cartItemLocalized['variation'] as &$variationsItem) {
                                        $variationsItem = urldecode($variationsItem);
                                    }
                                }

                                echo WC()->cart->get_item_data($cartItemLocalized);
                                ?>
                            </div>
                            <div class="woocommerce-products-wizard-widget-item-price">
                                <?php echo WC()->cart->get_product_price($_product) . ' x ' . $cart_item['quantity']; ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot class="woocommerce-products-wizard-widget-footer">
                <tr>
                    <td colspan="2">
                        <?php _e('Subtotal', 'woocommerce-products-wizard'); ?>
                        <b><?php echo wc_price(WCProductsWizard\Instance()->cart->getTotal($arguments['id'])); ?></b>
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php
    }
    ?>
</section>
