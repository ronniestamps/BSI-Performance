<?php
if (!defined('ABSPATH')) {
    exit();
}

$defaults = [];
$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<footer class="woocommerce-products-wizard-footer">
    <?php WCProductsWizard\Instance()->getTemplatePart('controls', $arguments); ?>
</footer>
