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

$loop = 1;

if (empty($arguments['cart'])) {
    WCProductsWizard\Instance()->getTemplatePart('messages/cart-is-empty');
} else {
    ?>
    <div class="builder-frame fadeInLeft animated" id="builder-frame">
        <div class="table-responsive">
            <table class="woocommerce-products-wizard-results table table-bordered table-hover"
                data-component="wcpw-results-table table-responsive">
                <thead class="woocommerce-products-wizard-results-header">
                    <tr>
                        <?php
                        if (isset($arguments['settings']['enable_remove_button'])
                            && $arguments['settings']['enable_remove_button']
                        ) {
                            ?>
                            <th><?php _e('Remove', 'woocommerce-products-wizard'); ?></th>
                            <?php
                        }
                        ?>
                        <th><?php _e('Thumbnail', 'woocommerce-products-wizard'); ?></th>
                        <th><?php _e('Data', 'woocommerce-products-wizard'); ?></th>
                        <th><?php _e('Price', 'woocommerce-products-wizard'); ?></th>
                    </tr>
                </thead>
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
                        <tr class="woocommerce-products-wizard-results-item
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
                            <?php
                            if (isset($arguments['settings']['enable_remove_button'])
                                && $arguments['settings']['enable_remove_button']
                            ) {
                                ?>
                                <td class="woocommerce-products-wizard-results-item-remove-wrapper">
                                    <button class="close woocommerce-products-wizard-results-item-remove"
                                        aria-label="Close"
                                        data-component="wcpw-form-item-remove-from-cart"
                                        data-item-id="<?php echo esc_attr($cart_item['product_id']); ?>">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </td>
                                <?php
                            }
                            ?>
                            <td class="woocommerce-products-wizard-results-item-thumbnail-wrapper">
                                <figure class="woocommerce-products-wizard-results-item-thumbnail">
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
                            </td>
                            <td class="woocommerce-products-wizard-results-item-title-wrapper">
                                <strong class="woocommerce-products-wizard-results-item-title">
                                    <?php echo $_product->get_name(); ?>
                                </strong>
                                <div class="woocommerce-products-wizard-results-item-data">
                                    <?php
                                    // Localization variations
                                    $cartItemLocalized = $cart_item;

                                    if (isset($cart_item['variation'])) {
                                        foreach ($cartItemLocalized['variation'] as &$variationsItem) {
                                            $variationsItem = urldecode($variationsItem);
                                        }
                                    }

                                    echo WC()->cart->get_item_data($cartItemLocalized);

                                    // Backorder notification
                                    if ($_product->backorders_require_notification()
                                        && $_product->is_on_backorder($cart_item['quantity'])
                                    ) {
                                        echo '<p class="backorder_notification">' . __(
                                            'Available on backorder',
                                            'woocommerce'
                                        ) . '</p>';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="woocommerce-products-wizard-results-item-price-wrapper">
                                <span class="woocommerce-products-wizard-results-item-price">
                                    <?php echo WC()->cart->get_product_price($_product) . ' x ' . $cart_item['quantity']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot class="woocommerce-products-wizard-results-footer">
                    <tr>
                        <td colspan="4">
                            <?php _e('Subtotal', 'woocommerce-products-wizard'); ?>
                            <b><?php echo wc_price(WCProductsWizard\Instance()->cart->getTotal($arguments['id'])); ?></b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php
}
?>
