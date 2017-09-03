<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'steps' => WCProductsWizard\Instance()->getSteps($id),
    'settings' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$alwaysShowSidebar = isset($arguments['settings']['always_show_sidebar'])
    ? filter_var($arguments['settings']['always_show_sidebar'], FILTER_VALIDATE_BOOLEAN)
    : true;
?>
<div class="row" data-component="wcpw-main-row">
    <div class="col-md-<?php echo ($alwaysShowSidebar || !empty($arguments['cart'])) ? 9 : 12; ?>">
        <?php
        foreach ($arguments['steps'] as $step) {
            if (is_numeric($step['id'])) {
                WCProductsWizard\Instance()->setActiveStep($id, $step['id']);
                WCProductsWizard\Instance()->getTemplatePart('form', $arguments);
            } elseif ($step['id'] == 'start') {
                WCProductsWizard\Instance()->getTemplatePart('start', $arguments);
            }
        }
        ?>
    </div>
    <?php if ($alwaysShowSidebar || !empty($arguments['cart'])) { ?>
        <div class="col-md-3 woocommerce-products-wizard-sidebar">
            <?php WCProductsWizard\Instance()->getTemplatePart('widget', $arguments); ?>
        </div>
    <?php } ?>
</div>
