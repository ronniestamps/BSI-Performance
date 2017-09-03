<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'itemTemplate' => 'form/item/type-2',
    'queryArgs' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$productsQuery = new WP_Query($arguments['queryArgs']);
?>
<div class="panel-group">
    <?php
    while ($productsQuery->have_posts()) {
        $productsQuery->the_post();

        global $product;

        $arguments['product'] = $product;
        WCProductsWizard\Instance()->getTemplatePart($arguments['itemTemplate'], $arguments);
    }
    ?>
</div>
<?php
WCProductsWizard\Instance()->getTemplatePart(
    'form/pagination',
    array_merge(
        [
            'productsQuery' => $productsQuery
        ],
        $arguments
    )
);

wp_reset_query();
?>
