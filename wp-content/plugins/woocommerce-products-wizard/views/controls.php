<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'id' => $id,
    'settings' => [],
    'stepId' => WCProductsWizard\Instance()->getActiveStepId($id)
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$singleMode = isset($arguments['settings']['enable_single_step_mode'])
    && filter_var($arguments['settings']['enable_single_step_mode'], FILTER_VALIDATE_BOOLEAN);

$toResultsButtonEnabled = isset($arguments['settings']['enable_to_results_button'])
    && filter_var($arguments['settings']['enable_to_results_button'], FILTER_VALIDATE_BOOLEAN);

$skipButtonEnabled = isset($arguments['settings']['enable_skip_button'])
    && filter_var($arguments['settings']['enable_skip_button'], FILTER_VALIDATE_BOOLEAN);

$backButtonEnabled = isset($arguments['settings']['enable_back_button'])
    && filter_var($arguments['settings']['enable_back_button'], FILTER_VALIDATE_BOOLEAN);

$resetButtonEnabled = isset($arguments['settings']['enable_reset_button'])
    && filter_var($arguments['settings']['enable_reset_button'], FILTER_VALIDATE_BOOLEAN);

$nextButtonEnabled = isset($arguments['settings']['enable_next_button'])
    && filter_var($arguments['settings']['enable_next_button'], FILTER_VALIDATE_BOOLEAN);
?>
<div class="woocommerce-products-wizard-controls" data-component="wcpw-controls">
    <?php
    if ($singleMode) {
        WCProductsWizard\Instance()->getTemplatePart('controls/add-to-cart', $arguments);
    } elseif (is_numeric($arguments['stepId'])) {
        if ($toResultsButtonEnabled) {
            WCProductsWizard\Instance()->getTemplatePart('controls/to-results', $arguments);
        }

        if ($skipButtonEnabled) {
            WCProductsWizard\Instance()->getTemplatePart('controls/skip', $arguments);
        }
        
        if ($backButtonEnabled && WCProductsWizard\Instance()->canGoBack($arguments['id'])) {
            WCProductsWizard\Instance()->getTemplatePart('controls/back', $arguments);
        }

        if ($resetButtonEnabled) {
            WCProductsWizard\Instance()->getTemplatePart('controls/reset', $arguments);
        }

        if ($nextButtonEnabled) {
            WCProductsWizard\Instance()->getTemplatePart('controls/next', $arguments);
        }
    } elseif ($arguments['stepId'] == 'start') {
        WCProductsWizard\Instance()->getTemplatePart('controls/start', $arguments);
    } elseif ($arguments['stepId'] == 'result') {
        if ($backButtonEnabled) {
            WCProductsWizard\Instance()->getTemplatePart('controls/back', $arguments);
        }

        if ($resetButtonEnabled) {
            WCProductsWizard\Instance()->getTemplatePart('controls/reset', $arguments);
        }

        WCProductsWizard\Instance()->getTemplatePart('controls/add-to-cart', $arguments);
    }
    ?>
</div>
