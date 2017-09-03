/* wcProductsWizard
 * Original author: troll_winner@mail.ru
 * Further changes, comments: troll_winner@mail.ru
 */

(function (root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object'
        && typeof module !== 'undefined'
        && typeof require === 'function'
    ) {
        module.exports = factory(require('jquery'));
    } else {
        factory(root.jQuery);
    }
})(this, function ($) {
    'use strict';

    /* global window */
    /* global document */

    const pluginName = 'wcProductsWizard';
    const defaults = {};
    const ajaxUrl = window.wordpressAjaxUrl || '/wp-admin/admin-ajax.php';

    const Plugin = function (element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);
        this.init();
    };

    /**
     * Init the instance
     * @returns {Object} self instance
     */
    Plugin.prototype.init = function () {
        const _this = this;

        _this.$element = $(_this.element);

        _this.$element
            // change the active form item
            .on('click', '[data-component="wcpw-form-item"]', function () {
                return $(this)
                    .find('[data-component="wcpw-form-item-id"][type="radio"]')
                    .prop('checked', true);
            })

            // add-to-cart ie polyfill
            .on('click', '[data-component="wcpw-add-to-cart"]', function (event) {
                event.preventDefault();

                return _this.addToMainCart($(this));
            })

            // remove item from cart
            .on('click', '[data-component="wcpw-form-item-remove-from-cart"]', function (event) {
                event.preventDefault();

                const $element = $(this);
                const $form = $element.parents('[data-component="wcpw-form"]');

                return _this.removeItem({
                    itemId: $element.data('item-id'),
                    page: $form.data('page'),
                    stepId: $form.data('step-id')
                });
            })

            // add item to cart
            .on('click', '[data-component="wcpw-form-item-add-to-cart"]', function (event) {
                event.preventDefault();

                const $element = $(this);
                const $form = $element.parents('[data-component="wcpw-form"]');

                return _this.addItem({
                    itemId: $element.data('item-id'),
                    page: $form.data('page'),
                    stepId: $form.data('step-id')
                });
            })

            // nav item click
            .on('click', '[data-component~="wcpw-nav-item"]', function (event) {
                event.preventDefault();

                const $element = $(this);

                // send ajax
                return _this.navRouter({
                    action: $element.data('nav-action'),
                    stepId: $element.data('nav-id')
                });
            })

            // pagination link click
            .on('click', '[data-component="wcpw-form-pagination-link"]', function (event) {
                event.preventDefault();

                const $element = $(this);

                // send ajax
                return _this.navRouter({
                    action: 'get',
                    stepId: $element.data('step-id'),
                    page: $element.data('page')
                });
            });

        $(document).trigger('launched.wcProductsWizard', _this);

        return _this;
    };

    /**
     * Makes the ajax-request
     * @param {Object} args - object of arguments
     * @returns {Promise} ajax request
     */
    Plugin.prototype.ajaxRequest = function (args) {
        const _this = this;
        const itemWidth = _this.$element.find('.active.step-block').innerWidth() + _this.$element.find('.active.step-block').prev().innerWidth() + 3;
        const itemHeight = _this.$element.find('.active.step-block').innerHeight() + _this.$element.find('.active.step-block').prev().innerHeight() + 2;

        _this.$element.addClass('loading');

        args.id = _this.$element.data('id');

        return $.post(
            ajaxUrl,
            args,
            (response) => {
                _this.$element
                    .html(response.content)
                    .removeClass('loading')
                    .find('.builder-frame')
                    .addClass('fadeInLeft animated');

                if (!args.hasOwnProperty('stepId')) {
                    if ($(window).width() > 991) {
                        _this.$element.find('.steps').animate({scrollTop: itemHeight + 'px'}, 800);

                    } else {
                        _this.$element.find('.steps').animate({scrollLeft: itemWidth + 'px'}, 800);
                    }
                }

                $(document).trigger('ajaxCompleted.wcProductsWizard', _this);

                return _this;
            },
            'json'
        );
    };

    /**
     * Route to the required navigation event
     * @param {Object} args - object of arguments
     * @returns {Object} nav function
     */
    Plugin.prototype.navRouter = function (args) {
        const data = {
            stepId: args.stepId,
            page: args.page || 1
        };

        switch (args.action) {
            case 'get':
                 return this.getStep(data);
                break;
            case 'skip':
                 return this.skipStep();
                break;
            case 'skip-all':
                 return this.skipAll(data);
                break;
            case 'submit':
                 return this.submitStep();
                break;
            case 'reset':
                 return this.reset();
                break;
            case 'none':
                break;
            default:
                return this.getStep(data);
                break;
        }
    };

    /**
     * Get current products from the active step
     * @returns {Array || Boolean} products array or false
     */
    Plugin.prototype.getProductsToAdd = function () {
        const _this = this;
        const $activeFormItemsIds = this.$element.find('[data-component="wcpw-form-item-id"]:checked');
        const productsToAdd = [];

        $activeFormItemsIds.each(function () {
            const addedItem = _this.getProductToAdd({
                id: $(this).val()
            });

            if (addedItem) {
                return productsToAdd.push(addedItem);
            }
        });

        return productsToAdd;
    };

    /**
     * Get current products from the active step
     * @param {Object} args - object of arguments
     * @returns {Boolean || Object} products array or false
     */
    Plugin.prototype.getProductToAdd = function (args) {
        const _this = this;
        const $activeFormItem = _this.$element.find(`[data-component="wcpw-form-item"][data-id="${args.id}"]`);
        const $activeFormItemId = $activeFormItem.find('[data-component="wcpw-form-item-id"]');
        const attributes = $activeFormItem.find('[data-component="wcpw-form-item-variations-attributes"]');
        const $attributesItemValue = $activeFormItem
            .find('[data-component="wcpw-form-item-variations-attributes-item-value"]');
        const quantity = $activeFormItem
                .find('[data-component="wcpw-form-item-quantity"]')
                .find(':input:not([type="button"])')
                .val() || 1;
        let allInputsAreSelected = true;

        if ($attributesItemValue.filter('[type="radio"]').length
            && $attributesItemValue.filter('[type="radio"]:checked').length === 0
        ) {
            allInputsAreSelected = false;
        }

        // is variation chosen
        if ($activeFormItem.attr('data-type') === 'variable'
            && (!$activeFormItem.find('[data-component="wcpw-form-item-variations-variation-id"]').val()
            || !allInputsAreSelected)
        ) {
            $(document)
                .trigger(
                    'noProductVariationSelected.error.wcProductsWizard',
                    {
                        instance: _this,
                        $attributesItemValue: $attributesItemValue
                    }
                );

            return false;
        }

        return {
            product_id: $activeFormItemId.val(),
            variation_id: $activeFormItem
                .find('[data-component="wcpw-form-item-variations-variation-id"]')
                .val(),
            variation: $.param(
                $activeFormItem
                    .find('[data-component="wcpw-form-item-variations-attributes"]')
                    .find('select, input')
                    .serializeArray()
            ),
            quantity: quantity
        };
    };

    /**
     * Remove form item from cart
     * @param {Object} args - object of arguments
     * @returns {Promise} ajax request
     */
    Plugin.prototype.removeItem = function (args) {
        return this.ajaxRequest(
            $.extend(
                {
                    action: 'removeWoocommerceProductsWizardCartItem'
                },
                args
            )
        );
    };

    /**
     * Remove form item from cart
     * @param {Object} args - object of arguments
     * @returns {Promise || Boolean} ajax request
     */
    Plugin.prototype.addItem = function (args) {
        const productToAdd = this.getProductToAdd({
            id: args.itemId
        });

        if (!productToAdd) {
            return false;
        }

        return this.ajaxRequest(
            $.extend(
                {
                    action: 'addWoocommerceProductsWizardItemToCart',
                    productsToAdd: [productToAdd]
                },
                args
            )
        );
    };

    /**
     * Send custom products from the active step to the wizard cart
     * @returns {Promise || Boolean} ajax request or false
     */
    Plugin.prototype.submitStep = function () {
        const stepId = this.$element.find('[data-component="wcpw-form"]').data('step-id');
        const productsToAdd = this.getProductsToAdd();
        const minimumItemsToAdd = this.$element.find('[data-component="wcpw-form-minimum-products-to-add"]').val();
        const cartStepsItems = this.$element.find('[data-component="wcpw-js-data"]').data('cart-steps-items');
        let cartStepItems = cartStepsItems[stepId] || [];
        const selectedProductsIds = [];

        // get selected products ids
        productsToAdd.forEach((item) => {
            return selectedProductsIds.push(item.product_id);
        });

        // get unique item in cart step and new items to add
        cartStepItems = cartStepItems.concat(selectedProductsIds).filter(function(elem, index, self) {
            return index == self.indexOf(elem);
        });

        if (cartStepItems.length < minimumItemsToAdd) {
            $(document).trigger(
                'minimumItemsRequired.error.wcProductsWizard',
                {
                    instance: this
                }
            );

            return false;
        }

        // send ajax
        return this.ajaxRequest({
            action: 'submitWoocommerceProductsWizardForm',
            productsToAdd: productsToAdd
        });
    };

    /**
     * Add all products to the main cart
     * @returns {Default || Boolean} false or browser event
     */
    Plugin.prototype.addToMainCart = function ($button, args) {
        const cartData = this.$element.find(`#${$button.attr('form')}`).serialize();

        $('.builder-sidebar').addClass('loading');

        if (!$button.data('meet-minimum-products-to-add')) {
            $(document).trigger(
                'minimumItemsRequired.error.wcProductsWizard',
                {
                    instance: this,
                    stepId: $button.data('step-id')
                }
            );

            return false;
        }

        // return this.$element
        //     .find(`#${$button.attr('form')}`)
        //     .submit();


        return $.post( 
            ajaxUrl,
            cartData,
            () => {
                $('.builder-sidebar').removeClass('loading');
                $('#floating-shopping-cart').modal('show');
            },
            'json'
        );
    };

    /**
     * Skip form to the next step without adding products to the wizard cart
     * @returns {Promise} ajax request
     */
    Plugin.prototype.skipStep = function () {
        return this.ajaxRequest({
            action: 'skipWoocommerceProductsWizardForm'
        });
    };

    /**
     * Skip form to the next step without adding products to the wizard cart
     * @param {Object} args - object of arguments
     * @returns {Promise} ajax request
     */
    Plugin.prototype.skipAll = function (args) {
        return this.ajaxRequest(
            $.extend(
                {
                    action: 'skipAllWoocommerceProductsWizardForm'
                },
                args
            )
        );
    };
    
    /**
     * Get step content by the id
     * @param {Object} args - object of arguments
     * @returns {Promise} ajax request
     */
    Plugin.prototype.getStep = function (args) {
        return this.ajaxRequest(
            $.extend(
                {
                    action: 'getWoocommerceProductsWizardForm'
                },
                args
            )
        );
    };

    /**
     * Reset form to the first step
     * @returns {Promise} ajax request
     */
    Plugin.prototype.reset = function () {
        return this.ajaxRequest({
            action: 'resetWoocommerceProductsWizardForm'
        });
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                    new Plugin(this, options));
            }
        });
    };

    function init() {
        return $('[data-component="wcpw"]').each(function () {
            $(this).wcProductsWizard();
        });
    }

    $(document)
        .ready(() => init())
        .on('init.wcProductsWizard', () => init());
});
