/* wcpwTerms
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

    /* global document */

    const pluginName = 'wcpwTerms';
    const defaults = {};

    const Plugin = function (element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);

        return this.init();
    };

    Plugin.prototype.init = function () {
        this.$element = $(this.element);
        this.$select = this.$element.find('[data-component="wcpw-terms-select"]');
        this.$list = this.$element.find('[data-component="wcpw-terms-list"] tbody');
        this.ajaxUrl = window.wordpressAjaxUrl || '/wp-admin/admin-ajax.php';

        if ($.fn.sortable) {
            // init sortable
            this.$element.sortable({
                items: '[data-component="wcpw-terms-list-item"]'
            });
        }

        this.$element.data('wcpw-terms', this);

        return this;
    };

    // add the new element
    Plugin.prototype.addItem = function () {
        const value = this.$select.val();
        const $selectedOption = this.$select.find('option:selected');

        if (!value) {
            return this;
        }

        $selectedOption.addClass('hidden');
        this.$select.val('');

        // make new element from the default template
        const $newItem = this.$element
            .find('[data-component="wcpw-terms-list-item-template"]')
            .clone()
            .appendTo(this.$list)
            .attr('data-component', 'wcpw-terms-list-item')
            .attr('data-id', value);

        const $newItemSettings = $newItem.find('[data-component="wcpw-terms-list-item-settings"]');

        $newItem
            .find('[data-component="wcpw-terms-list-item-name"]')
            .text($selectedOption.text())
            .end()
            .find('[data-component="wcpw-terms-list-item-id"]')
            .attr('name', `terms_list_ids[${value}]`)
            .attr('value', value);

        $newItemSettings.attr('data-settings', $newItemSettings.attr('data-settings').replace(/%TERM_ID%/g, value));

        $(document).trigger('itemAdded.terms.wcProductsWizard');

        return this;
    };

    // get term item settings
    Plugin.prototype.getSettings = function (args) {
        const data = $.extend({}, {action: 'wcpwGetTermsListItemSettingsAjax'}, args);

        return $.get(
            this.ajaxUrl,
            data,
            (response) => {
                // show modal
                window.tb_show('Add item', '#TB_inline', false);

                // append data
                $('#TB_ajaxContent').html(response);

                // reInit woocommerce js actions
                $(document.body).trigger('wc-enhanced-select-init');
            }
        );
    };

    // save term item settings
    Plugin.prototype.saveSettings = function ($form) {
        const data = {
            action: 'wcpwSaveTermsListItemSettingsAjax',
            post_id: $form.attr('data-post-id'),
            term_id: $form.attr('data-term-id'),
            values: $form.serialize()
        };

        return $.post(
            this.ajaxUrl,
            data,
            () => {},
            'json'
        );
    };

    // remove element
    Plugin.prototype.removeItem = function ($item) {
        this.$select
            .find(`[value="${$item.attr('data-id')}"]`)
            .removeClass('hidden');

        $item.remove();

        $(document).trigger('itemRemoved.terms.wcProductsWizard');

        return this;
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                    new Plugin(this, options));
            }
        });
    };

    const init = function () {
        return $('[data-component="wcpw-terms"]').each(function () {
            return $(this).wcpwTerms();
        });
    };

    $(document)
        .ready(() => init())
        .on('ajaxComplete.wcProductsWizard init.terms.wcProductsWizard', () => init())

        // add the form item
        .on('click', '[data-component="wcpw-terms-add"]', function (event) {
            event.preventDefault();

            const $button = $(this);
            const $wcpwTerms = $button.closest('[data-component="wcpw-terms"]');

            if ($wcpwTerms.data('wcpw-terms')) {
                return $wcpwTerms.data('wcpw-terms').addItem();
            }

            return this;
        })

        // remove the form item
        .on('click', '[data-component="wcpw-terms-list-item-remove"]', function (event) {
            event.preventDefault();

            const $button = $(this);
            const $wcpwTerms = $button.closest('[data-component="wcpw-terms"]');

            if ($wcpwTerms.data('wcpw-terms')) {
                return $wcpwTerms
                    .data('wcpw-terms')
                    .removeItem($button.closest('[data-component="wcpw-terms-list-item"]'));
            }

            return this;
        })

        // open settings modal
        .on('click', '[data-component="wcpw-terms-list-item-settings"]', function(event) {
            event.preventDefault();

            const $button = $(this);
            const $wcpwTerms = $button.closest('[data-component="wcpw-terms"]');

            if ($wcpwTerms.data('wcpw-terms')) {
                return $wcpwTerms
                    .data('wcpw-terms')
                    .getSettings($button.data('settings'));
            }

            return this;
        })

        // save the item settings
        .on('click', '[data-component="wcpw-terms-list-item-settings-save"]', function (event) {
            event.preventDefault();

            const $button = $(this);
            const $wcpwTerms = $('[data-component="wcpw-terms"]');
            const $form = $button.closest('[data-component="wcpw-terms-list-item-settings-form"]');

            if ($wcpwTerms.data('wcpw-terms')) {
                $wcpwTerms.data('wcpw-terms').saveSettings($form);
            }

            window.tb_remove();

            return this;
        });
});
