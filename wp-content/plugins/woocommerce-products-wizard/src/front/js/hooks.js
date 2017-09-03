(function (root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define(['jquery', 'bootstrap-notify', 'sticky-kit'], factory);
    } else if (typeof exports === 'object'
        && typeof module !== 'undefined'
        && typeof require === 'function'
    ) {
        module.exports = factory(
            require('jquery'),
            require('bootstrap-notify'),
            require('sticky-kit')
        );
    } else {
        factory(root.jQuery);
    }
})(this, function ($) {
    'use strict';

    function makeNotify(message, type = 'warning') {
        if (typeof $.notify === 'undefined') {
            return false;
        }

        return $.notify(
            {
                message: message
            },
            {
                element: '[data-component="wcpw"]',
                position: 'fixed',
                type: type
            }
        );
    }

    function isScrolledIntoView(elem) {
        const docViewTop = $(window).scrollTop();
        const docViewBottom = docViewTop + $(window).height();

        const elemTop = $(elem).offset().top;
        const elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    $(document)
        .on('launched.wcProductsWizard ajaxCompleted.wcProductsWizard', (event, instance) => {
            // relaunch prettyPhoto scripts
            if (typeof $.fn.prettyPhoto !== 'undefined'
                && typeof instance.$element !== 'undefined'
            ) {
                instance.$element
                    .find('a[data-rel^="prettyPhoto"]')
                    .prettyPhoto({
                        hook: 'data-rel',
                        social_tools: false,
                        theme: 'pp_woocommerce',
                        horizontal_padding: 20,
                        opacity: 0.8,
                        deeplinking: false
                    });
            }

            // sticky widget
            $('[data-component="wcpw-widget"]').each(function () {
                return $(this).stick_in_parent({
                    parent: '[data-component="wcpw-main-row"]',
                    offset_top: 30
                });
            });
        })

        .on('minimumItemsRequired.error.wcProductsWizard', (event, args) => {
            let $form = args.instance.$element.find('[data-component="wcpw-form"]');

            if (args.stepId) {
                $form = args.instance
                    .$element
                    .find('[data-component="wcpw-form"][data-step-id="${args.stepId}"]');

                // scroll window to the form
                if (!isScrolledIntoView($form)) {
                    $('html, body').scrollTop($form.offset().top);
                }
            }

            const message = args.instance
                .$element
                .find('[data-component="wcpw-js-data"]')
                .data('minimum-item-required-message')
                .toString()
                .replace('%n%', $form.data('minimum-items-to-add'));

            return makeNotify(message);
        })

        .on('noProductVariationSelected.error.wcProductsWizard', (event, args) => {
            const $attributesItemValueParent = args.$attributesItemValue.first().parent();
            const $form = args.instance.$element.find('[data-component="wcpw-form"]');

            args.$attributesItemValue.addClass('focus');

            // scroll window to the unselected option
            if (!isScrolledIntoView($attributesItemValueParent)) {
                $('html, body').scrollTop($attributesItemValueParent.offset().top);
            }

            // set focus on the unselected option
            setTimeout(() => args.$attributesItemValue.removeClass('focus'), 2000);

            const message = args.instance
                .$element
                .find('[data-component="wcpw-js-data"]')
                .data('not-all-options-selected-message')
                .toString();

            return makeNotify(message);
        });
});
