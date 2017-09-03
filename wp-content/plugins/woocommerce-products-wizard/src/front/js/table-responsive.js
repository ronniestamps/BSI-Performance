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

    const pluginName = 'tableResponsive';
    const defaults = {};

    function Plugin(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);
        this.init();
    }

    Plugin.prototype.init = function () {
        this.$element = $(this.element);
        this.$headerTh = this.$element.find('thead th');
        this.$bodyTh = this.$element.find('tbody th');

        const matrix = [];

        for (let rowIndex = 0; rowIndex < this.$element[0].rows.length; rowIndex++) {
            const row = this.$element[0].rows[rowIndex];

            for (let colIndex = 0; colIndex < row.cells.length; colIndex++) {
                const cell = row.cells[colIndex];
                let xx = colIndex;

                for (; matrix[rowIndex] && matrix[rowIndex][xx]; ++xx) {
                    // nothing
                }

                for (let tx = xx; tx < xx + cell.colSpan; ++tx) {
                    for (let ty = rowIndex; ty < rowIndex + cell.rowSpan; ++ty) {
                        // some logic. can be better probably.
                        if (!matrix[ty]) {
                            matrix[ty] = [];
                        }

                        matrix[ty][tx] = true;
                    }
                }

                row.cells[colIndex].setAttribute('data-col', xx);
                row.cells[colIndex].setAttribute('data-row', rowIndex);
                cell.setAttribute('data-th', this.getCellHeaderByIndex(xx, rowIndex));
            }
        }

        return this;
    };

    Plugin.prototype.getCellHeaderByIndex = function (col, row) {
        const $headerTh = this.$headerTh.filter(`[data-col="${col}"][data-row="0"]`);
        const $bodyTh = this.$bodyTh.filter(`[data-col="0"][data-row="${row}"]`);

        if ($headerTh.length > 0) {
            return $.trim($headerTh.text().replace(/\r?\n|\r/, ''));
        } else if ($bodyTh.length > 0) {
            return $.trim($bodyTh.text().replace(/\r?\n|\r/, ''));
        }

        return '';
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, pluginName)) {
                $.data(this, pluginName, new Plugin(this, options));
            }
        });
    };

    $(document).on('ready ajaxCompleted.wcProductsWizard', function () {
        $('[data-component~="table-responsive"]').tableResponsive();
    });
});
