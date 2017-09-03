<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item-type-1',
    'product' => null
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<article class="<?php echo esc_attr($arguments['class']); ?> product panel panel-primary"
    data-component="wcpw-form-item"
    data-type="<?php echo esc_attr($arguments['product']->get_type()); ?>"
    data-id="<?php echo esc_attr($arguments['product']->get_id()); ?>">
    <header class="<?php echo esc_attr($arguments['class']); ?>-header panel-heading">
        <?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/title', $arguments); ?>
    </header>
    <?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/thumbnail', $arguments); ?>
    <div class="<?php echo esc_attr($arguments['class']); ?>-body panel-body"><?php
        WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/variations', $arguments);
        WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/description', $arguments);
        ?></div>
    <footer class="<?php echo esc_attr($arguments['class']); ?>-footer panel-footer">
        <div class="form-inline clearfix">
            <div class="hidePrice"><?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/price', $arguments); ?></div>
            <div class="pull-right">
                <?php
                WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/quantity', $arguments);
                WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/add-to-cart', $arguments);
                ?>
            </div>
        </div>
    </footer>
</article>
