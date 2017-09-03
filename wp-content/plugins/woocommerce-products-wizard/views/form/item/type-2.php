<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [
    'class' => 'woocommerce-products-wizard-form-item-type-2',
    'product' => null
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$isSimple = $arguments['product']->get_type() == 'simple';
?>
<article class="<?php echo esc_attr($arguments['class']); ?> product panel panel-primary"
    data-component="wcpw-form-item"
    data-type="<?php echo esc_attr($arguments['product']->get_type()); ?>"
    data-id="<?php echo esc_attr($arguments['product']->get_id()); ?>">
    <header class="<?php echo esc_attr($arguments['class']); ?>-header panel-heading">
        <?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/title', $arguments); ?>
    </header>
    <div class="<?php echo esc_attr($arguments['class']); ?>-body panel-body">
        <div class="row">
            <div class="<?php echo $isSimple ? 'col-sm-4' : 'col-md-4 col-sm-6'; ?>">
                <?php
                WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/thumbnail', $arguments);
                WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/gallery', $arguments);
                ?>
            </div>
            <?php if (!$isSimple) { ?>
                <div class="col-md-3 col-sm-6">
                    <?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/variations', $arguments); ?>
                </div>
            <?php } ?>
            <div class="<?php echo $isSimple ? 'col-sm-8' : 'col-md-5 col-sm-12'; ?>">
                <?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/description', $arguments); ?>
            </div>
        </div>
    </div>
    <footer class="<?php echo esc_attr($arguments['class']); ?>-footer panel-footer">
        <div class="form-inline clearfix">
            <?php WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/price', $arguments); ?>
            <div class="pull-right">
                <?php
                WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/quantity', $arguments);
                WCProductsWizard\Instance()->getTemplatePart('form/item/prototype/add-to-cart', $arguments);
                ?>
            </div>
        </div>
    </footer>
</article>
