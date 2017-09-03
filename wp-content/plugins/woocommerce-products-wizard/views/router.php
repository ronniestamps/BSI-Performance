<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'settings' => WCProductsWizard\Instance()->getWizardSettings($id),
    'termsSettings' => WCProductsWizard\Instance()->getTermsSettings($id)
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$singleMode = isset($arguments['settings']['enable_single_step_mode'])
    && filter_var($arguments['settings']['enable_single_step_mode'], FILTER_VALIDATE_BOOLEAN);

WCProductsWizard\Instance()->getTemplatePart('js-data', $arguments);

if (!$singleMode) {
    WCProductsWizard\Instance()->getTemplatePart('tabs/nav', $arguments);
}

WCProductsWizard\Instance()->getTemplatePart('header', $arguments);

if ($singleMode) {
    WCProductsWizard\Instance()->getTemplatePart('router/single-mode', $arguments);
} else {
    WCProductsWizard\Instance()->getTemplatePart('router/tabs-mode', $arguments);
}

//WCProductsWizard\Instance()->getTemplatePart('footer', $arguments);
