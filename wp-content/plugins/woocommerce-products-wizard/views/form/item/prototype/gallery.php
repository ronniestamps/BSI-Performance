<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item',
    'product' => null,
    'galleryGrid' => [
        'lg' => 4,
        'md' => 4,
        'sm' => 4,
        'xs' => 4
    ]
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$attachmentIds = $arguments['product']->get_gallery_image_ids();
$loop = 1;

if ($attachmentIds) {
    ?>
    <section class="<?php echo esc_attr($arguments['class']); ?>-gallery"
        data-component="wcpw-form-item-gallery">
        <div class="row">
            <?php
            foreach ($attachmentIds as $attachmentId) {
                $classes = [
                    'thumbnail',
                    'zoom'
                ];

                $imageLink = wp_get_attachment_url($attachmentId);

                if (!$imageLink) {
                    continue;
                }

                $imageTitle = esc_attr(get_the_title($attachmentId));
                $imageCaption = esc_attr(get_post_field('post_excerpt', $attachmentId));

                $image = wp_get_attachment_image(
                    $attachmentId,
                    apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'),
                    0,
                    $attr = [
                        'title' => $imageTitle,
                        'alt' => $imageTitle
                    ]
                );

                $imageClass = esc_attr(implode(' ', $classes));

                echo '<div class="col-lg-'
                    . $arguments['galleryGrid']['lg']
                    . ' col-md-'
                    . $arguments['galleryGrid']['md']
                    . ' col-sm-'
                    . $arguments['galleryGrid']['sm']
                    . ' col-xs-'
                    . $arguments['galleryGrid']['xs']
                    . '">';

                echo apply_filters(
                    'woocommerce_single_product_image_thumbnail_html',
                    sprintf(
                        '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery-%s]">%s</a>',
                        $imageLink,
                        $imageClass,
                        $imageCaption,
                        $arguments['product']->get_id(),
                        $image
                    ),
                    $attachmentId,
                    $arguments['product']->get_id(),
                    $imageClass
                );

                echo '</div>';
                echo $loop % (12 / $arguments['galleryGrid']['lg']) == 0
                    ? '<div class="clearfix visible-lg"></div>'
                    : '';
                echo $loop % (12 / $arguments['galleryGrid']['md']) == 0
                    ? '<div class="clearfix visible-md"></div>'
                    : '';
                echo $loop % (12 / $arguments['galleryGrid']['sm']) == 0
                    ? '<div class="clearfix visible-sm"></div>'
                    : '';
                echo $loop % (12 / $arguments['galleryGrid']['xs']) == 0
                    ? '<div class="clearfix visible-xs"></div>'
                    : '';

                $loop++;
            }
            ?>
        </div>
    </section>
    <?php
}
?>