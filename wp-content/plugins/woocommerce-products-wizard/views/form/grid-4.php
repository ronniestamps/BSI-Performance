<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'itemTemplate' => 'form/item/type-1',
    'queryArgs' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$loop = 1;
$productsQuery = new WP_Query($arguments['queryArgs']);
?>
<div class="row">
    <?php
    while ($productsQuery->have_posts()) {
        $productsQuery->the_post();

        global $product;

        $arguments['product'] = $product;
        ?>
        <div class="col col-lg-4 col-md-6 col-sm-6">
            <?php WCProductsWizard\Instance()->getTemplatePart($arguments['itemTemplate'], $arguments); ?>
        </div>
        <?php
        echo $loop % 4 == 0 ? '<div class="clearfix visible-lg"></div>' : '';
        echo $loop % 3 == 0 ? '<div class="clearfix visible-md"></div>' : '';
        echo $loop % 2 == 0 ? '<div class="clearfix visible-sm"></div>' : '';

        $loop++;
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
