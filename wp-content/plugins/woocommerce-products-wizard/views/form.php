<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$step = isset($step) ? $step : WCProductsWizard\Instance()->getActiveStep($id);
$stepId = isset($stepId) ? $stepId : WCProductsWizard\Instance()->getActiveStepId($id);

$defaults = [
    'description' => WCProductsWizard\Instance()->getTermDescription($id, $stepId),
    'descriptionPosition' => WCProductsWizard\Instance()->getTermDescriptionPosition($id, $stepId),
    'settings' => [],
    'termsSettings' => [],
    'step' => $step,
    'stepId' => $stepId,
    'page' => max(1, get_query_var('paged')),
    'title' => isset($step['name']) ? $step['name'] : 'No title'
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$minimumProductsToAdd = isset($arguments['termsSettings'][$arguments['stepId']]['minimum_products_to_add'])
    ? $arguments['termsSettings'][$arguments['stepId']]['minimum_products_to_add']
    : 1;

$singleMode = isset($arguments['settings']['enable_single_step_mode'])
    && filter_var($arguments['settings']['enable_single_step_mode'], FILTER_VALIDATE_BOOLEAN);
?>

<form action="#"
    method="POST"
    id="wcpw-form-<?php echo esc_attr($id); ?>"
    class="woocommerce-products-wizard-form"
    data-component="wcpw-form"
    data-step-id="<?php echo esc_attr($arguments['stepId']); ?>"
    data-page="<?php echo esc_attr($arguments['page']); ?>"
    data-minimum-items-to-add="<?php echo esc_attr($minimumProductsToAdd); ?>">
    <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
    <input type="hidden"
        name="stepId"
        value="<?php echo esc_attr($arguments['stepId']); ?>">
    <input type="hidden"
        data-component="wcpw-form-minimum-products-to-add"
        name="minimumProductsToAdd"
        value="<?php echo esc_attr($minimumProductsToAdd); ?>">
    <?php
    if (isset($_POST['minimumProductsToAdd'])
        && isset($_POST['selectedProductsIds'])
        && count($_POST['selectedProductsIds'][$_POST['stepId']]) < $_POST['minimumProductsToAdd']
        && $_POST['stepId'] == $arguments['stepId']
    ) {
        WCProductsWizard\Instance()->getTemplatePart('messages/minimum-items-required', $_POST);
    }

    if ($singleMode) {
        WCProductsWizard\Instance()->getTemplatePart('form/title', $arguments);
    }

    if ($arguments['description'] && $arguments['descriptionPosition'] == 'top') {
        WCProductsWizard\Instance()->getTemplatePart('form/description', $arguments);
    }

    WCProductsWizard\Instance()->productsRequest($id, $arguments['stepId'], $arguments['page']);

    if ($arguments['description'] && $arguments['descriptionPosition'] == 'bottom') {
        WCProductsWizard\Instance()->getTemplatePart('form/description', $arguments);
    }
    ?>
</form>