<?php
// Since 2.10.5 - older versions support
if (!function_exists('WCProdWiz')) {
    function WCProdWiz()
    {
        return WCProductsWizard\Core::instance();
    }
}
