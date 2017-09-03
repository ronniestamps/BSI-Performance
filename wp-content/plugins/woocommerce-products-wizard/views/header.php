<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [];
$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<header class="woocommerce-products-wizard-header">
    <?php WCProductsWizard\Instance()->getTemplatePart('controls', $arguments); ?>
</header>
