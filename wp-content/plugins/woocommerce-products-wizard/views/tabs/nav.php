<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'nextStepId' => WCProductsWizard\Instance()->getNextStepId($id),
    'settings' => [],
    'steps' => WCProductsWizard\Instance()->getSteps($id),
    'stepId' => WCProductsWizard\Instance()->getActiveStepId($id),
    'skipAll' => false,
    'tabs' => WCProductsWizard\Instance()->getTabsItems($id)
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$activeNavItem = false;
$previousNavItem = false;

$enableAllTabsAvailability = isset($arguments['settings']['enable_all_tabs_availability'])
    && filter_var($arguments['settings']['enable_all_tabs_availability'], FILTER_VALIDATE_BOOLEAN)
    && isset($arguments['settings']['dependencies_disable'])
    && filter_var($arguments['settings']['dependencies_disable'], FILTER_VALIDATE_BOOLEAN);
?>
<div class="nav nav-tabs woocommerce-products-wizard-tabs-nav steps" role="tablist">
    <?php
    foreach ($arguments['steps'] as $step) {
        if ($arguments['stepId'] == $step['id']) {
            // set active nav item attributes
            $activeNavItem = $step['id'];
            $step['class'] = 'active';
            $step['action'] = 'none';
        } elseif ($step['id'] == $arguments['nextStepId']) {
            // enable the next tab after the active
            $step['class'] = '';
            $step['action'] = $activeNavItem == 'start' || $enableAllTabsAvailability ? 'get' : 'submit';
        } else {
            // other items
            $step['class'] = $activeNavItem && !$enableAllTabsAvailability ? 'disabled' : '';
            $step['action'] = $activeNavItem && !$enableAllTabsAvailability ? 'none' : 'get';
        }

        // if action is "skip all"
        if (!$enableAllTabsAvailability && filter_var($arguments['skipAll'], FILTER_VALIDATE_BOOLEAN)) {
            if ($arguments['previousStepId'] == $step['id']) {
                $previousNavItem = $step['id'];
                $step['class'] = '';
                $step['action'] = 'get';
            } elseif ($arguments['stepId'] == $step['id']) {
                $step['class'] = 'active';
                $step['action'] = 'none';
            } elseif (!$previousNavItem) {
                $step['class'] = '';
                $step['action'] = 'get';
            } else {
                $step['class'] = 'disabled';
                $step['action'] = 'none';
            }
        }
        ?>
        <div role="presentation"
            class="<?php echo esc_attr($step['class']); ?> step-block"
            data-component="wcpw-tabs-nav-item wcpw-nav-item"
            data-nav-action="<?php echo esc_attr($step['action']); ?>"
            data-nav-id="<?php echo esc_attr($step['id']); ?>">
            <a href="#" role="tab">
                <?php echo wp_kses_post($step['name']); ?>
            </a>
        </div>
        <?php 
    } 
    ?>
</div>
