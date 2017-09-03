/* wcpwDataTable
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

    const pluginName = 'wcpwDataTable';
    const defaults = {};

    const Plugin = function (element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);

        return this.init();
    };

    // on plugin init
    Plugin.prototype.init = function () {
        this.$element = $(this.element);

        if ($.fn.sortable) {
            // init sortable
            this.$element.sortable({
                items: '[data-component="wcpw-data-table-item"]'
            });
        }

        this.$element.data('wcpw-data-table', this);

        return this;
    };

    // add the new element in the table
    Plugin.prototype.addItem = function ($insertAfterItem) {
        $insertAfterItem = $insertAfterItem.length !== 0
            ? $insertAfterItem
            : this.$element.find('[data-component="wcpw-data-table-item"]:last');

        let $template = this.$element.find('[data-component="wcpw-data-table-item-template"]');

        if ($template.length === 0) {
            $template = $insertAfterItem;
        }

        // make the new element from default template
        $template
            .clone()
            .insertAfter($insertAfterItem)
            .attr('data-component', 'wcpw-data-table-item')
            .find(':input')
            .each(function () {
                const $this = $(this);

                // make real attributes from placeholders
                $.each(this.attributes, (i, attr) => {
                    const name = attr.name;
                    const value = attr.value;

                    if (name.indexOf('data-make') === -1) {
                        return this;
                    }

                    return $this.attr(name.replace('data-make-', ''), value);
                });
            });

        this.recalculation();

        $(document).trigger('itemAdded.dataTable.wcpwProductsWizard');

        return this;
    };

    // remove element from the table
    Plugin.prototype.removeItem = function ($item) {
        if ($item.is(':only-child')) {
            // clear values of the last item
            $item.find(':input').val('');
        } else {
            // remove the non-last item
            $item.remove();
        }

        this.recalculation();

        $(document).trigger('itemRemoved.dataTable.wcpwProductsWizard');

        return this;
    };

    // reCalculate the input names indexes
    Plugin.prototype.recalculation = function () {
        return this.$element
            .find('[data-component="wcpw-data-table-item"]')
            .each(function (index) {
                return $(this)
                    .find(':input')
                    .each(function () {
                        const name = $(this).attr('name');

                        if (!name) {
                            return this;
                        }

                        // replace the first array key from the end
                        return $(this).attr(
                            'name',
                            name
                                .split('')
                                .reverse()
                                .join('')
                                .replace(/\]\d+\[/, `]${index}[`)
                                .split('')
                                .reverse()
                                .join('')
                        );
                    });
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

    const init = function () {
        return $('[data-component="wcpw-data-table"]').each(function () {
            return $(this).wcpwDataTable();
        });
    };

    $(document)
        .ready(() => init())
        .on('ajaxComplete.wcProductsWizard init.dataTable.wcpwProductsWizard', () => init())

        // add the form item
        .on('click', '[data-component="wcpw-data-table-item-add"]', function () {
            const $button = $(this);
            const $wcpwDataTable = $button.closest('[data-component="wcpw-data-table"]');

            if ($wcpwDataTable.data('wcpw-data-table')) {
                return $wcpwDataTable
                    .data('wcpw-data-table')
                    .addItem($button.closest('[data-component="wcpw-data-table-item"]'));
            }

            return this;
        })

        // remove the form item
        .on('click', '[data-component="wcpw-data-table-item-remove"]', function () {
            const $button = $(this);
            const $wcpwDataTable = $button.closest('[data-component="wcpw-data-table"]');

            if ($wcpwDataTable.data('wcpw-data-table')) {
                return $wcpwDataTable
                    .data('wcpw-data-table')
                    .removeItem($button.closest('[data-component="wcpw-data-table-item"]'));
            }

            return this;
        });
});
