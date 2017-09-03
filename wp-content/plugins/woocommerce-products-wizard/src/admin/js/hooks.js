(function (root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object'
        && typeof module !== 'undefined'
        && typeof require === 'function'
    ) {
        /* eslint-disable global-require */
        module.exports = factory(require('jquery'));
        /* eslint-enable */
    } else {
        factory(root.jQuery);
    }
})(this, function ($) {
    'use strict';

    $(document)
        .on('itemAdded.dataTable.wcpwProductsWizard', () => {
            // reInit woocommerce js actions
            $(document.body).trigger('wc-enhanced-select-init');
        });
});
