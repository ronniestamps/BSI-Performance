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
    'stepId' => WCProductsWizard\Instance()->getActiveStepId($id),
    'settings' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$alwaysShowSidebar = isset($arguments['settings']['always_show_sidebar'])
    ? filter_var($arguments['settings']['always_show_sidebar'], FILTER_VALIDATE_BOOLEAN)
    : true;

if (is_numeric($arguments['stepId']) || ($alwaysShowSidebar || empty($arguments['cart']))) {
    ?>
    <div class="builder-frame" id="builder-frame" data-component="wcpw-main-row">
        <div class="builder">
            <?php WCProductsWizard\Instance()->getTemplatePart('form', $arguments); ?>
        </div>
    </div>
    <?php
} elseif (is_numeric($arguments['stepId'])) {
    WCProductsWizard\Instance()->getTemplatePart('form', $arguments);
} elseif ($arguments['stepId'] == 'start') {
    WCProductsWizard\Instance()->getTemplatePart('start', $arguments);
} elseif ($arguments['stepId'] == 'result') {
    WCProductsWizard\Instance()->getTemplatePart('result', $arguments);
}
