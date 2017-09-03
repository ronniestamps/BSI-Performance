<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item',
    'thumbnailSize' => 'shop_catalog',
    'thumbnailLink' => wp_get_attachment_image_src(get_post_thumbnail_id(), 'large')[0],
    'product' => null
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$dimensions = wc_get_image_size($arguments['thumbnailSize']);
?>
<figure class="<?php echo esc_attr($arguments['class']); ?>-thumbnail thumbnail"
    data-component="wcpw-form-item-thumbnail">

        <?php
        echo has_post_thumbnail()
            ? get_the_post_thumbnail(
                $arguments['product']->get_id(),
                $arguments['thumbnailSize'],
                [
                    'data-component' => 'wcpw-form-item-thumbnail-image'
                ]
            )
            : ('<img src="' . wc_placeholder_img_src()
            . '" alt="'
            . __('Placeholder', 'woocommerce')
            . '" width="'
            . esc_attr($dimensions['width'])
            . '" class="woocommerce-placeholder wp-post-image" data-component="wcpw-form-item-thumbnail-image" height="'
            . esc_attr($dimensions['height']) . '" />');
        ?>

</figure>
