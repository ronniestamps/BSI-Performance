/* WooCommerce Products Wizard Thumbnail
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

    const pluginName = 'wcpwThumbnail';
    const defaults = {};

    const Plugin = function (element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);

        return this.init();
    };

    // on plugin init
    Plugin.prototype.init = function () {
        this.$element = $(this.element);
        this.$id = this.$element.find('[data-component="wcpw-thumbnail-id"]');
        this.$image = this.$element.find('[data-component="wcpw-thumbnail-image"]');

        this.$element.data('wcpw-thumbnail', this);
        
        return this;
    };
    
    // open thumbnail modal
    Plugin.prototype.openModal = function () {
        const _this = this;
        
        // If the media frame already exists, reopen it.
        if (this.modalFrame) {
            this.modalFrame.open();
            
            return this;
        }
        
        // Create the media frame.
        this.modalFrame = wp.media.frames.downloadable_file = wp.media({
            title: 'Select image',
            button: {
                text: 'Select'
            },
            multiple: false
        });
        
        // When an image is selected, run a callback.
        this.modalFrame.on('select', () => {
            return _this
                .modalFrame
                .state()
                .get('selection')
                .map((attachment) => {
                    const attachmentJson = attachment.toJSON();

                    if (!attachmentJson.id) {
                        return null;
                    }

                    const src = {}.hasOwnProperty.call(attachmentJson, 'sizes')
                        && {}.hasOwnProperty.call(attachmentJson.sizes, 'thumbnail')
                            ? attachmentJson.sizes.thumbnail.url
                            : attachmentJson.url;

                    _this.$image.html(`<img src="${src}">`);
                    _this.$id.val(attachmentJson.id);
                });
        });
        
        // Finally, open the modal
        return this.modalFrame.open();
    };

    // detach image is and remove image
    Plugin.prototype.removeImage = function () {
        this.$image.html('');
        this.$id.val('');
        
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
        return $('[data-component="wcpw-thumbnail"]').each(function () {
            return $(this).wcpwThumbnail();
        });
    };

    $(document)
        .ready(() => init())
        .on('init.thumbnail.wcpwProductsWizard', () => init())
        // set thumbnail
        .on('click', '[data-component="wcpw-thumbnail-set"]', function (event) {
            event.preventDefault();
            
            const $button = $(this);
            const $wcpwThumbnail = $button.closest('[data-component="wcpw-thumbnail"]');

            if ($wcpwThumbnail.data('wcpw-thumbnail')) {
                return $wcpwThumbnail
                    .data('wcpw-thumbnail')
                    .openModal();
            }
            
            return this;
        })

        // remove thumbnail
        .on('click', '[data-component="wcpw-thumbnail-remove"]', function (event) {
            event.preventDefault();

            const $button = $(this);
            const $wcpwThumbnail = $button.closest('[data-component="wcpw-thumbnail"]');

            if ($wcpwThumbnail.data('wcpw-thumbnail')) {
                return $wcpwThumbnail
                    .data('wcpw-thumbnail')
                    .removeImage();
            }
            
            return this;
        });
});
