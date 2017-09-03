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

    /**
     * Main variation plugin
     */
    $.fn.wcpwVariationForm = function () {
        // Unbind any existing events
        this.unbind('checkVariations.variationForm.wcProductsWizard ' +
            'updateVariationValues.variationForm.wcProductsWizard foundVariation.variationForm.wcProductsWizard');
        this.find('[data-component="wcpw-form-item-variations-attributes-item-value"]').unbind('change focusin');

        // Bind events
        const $form = this
            // Upon changing an option
            .on('change', '[data-component="wcpw-form-item-variations-attributes-item-value"]', function () {
                const $variationForm = $(this).closest('[data-component="wcpw-form-item-variations"]');

                $variationForm
                    .find('[data-component="wcpw-form-item-variations-variation-id"]')
                    .val('')
                    .change();

                $variationForm
                    .trigger('woocommerceVariationSelectChange.variationForm.wcProductsWizard')
                    .trigger('checkVariations.variationForm.wcProductsWizard', ['', false]);

                $(this).blur();

                if ($().uniform && $.isFunction($.uniform.update)) {
                    $.uniform.update();
                }
            })

            // Upon gaining focus
            .on('focusin touchstart',
                '[data-component="wcpw-form-item-variations-attributes-item-value"]',
                function () {
                    const $element = $(this);

                    $element
                        .closest('[data-component="wcpw-form-item-variations"]')
                        .trigger('checkVariations.variationForm.wcProductsWizard', [$element.attr('data-name'), true]);
                }
            )

            // Check variations
            .on('checkVariations.variationForm.wcProductsWizard', function (event, exclude, focus) {
                let allSet = true;
                const currentSettings = {};
                const $variationForm = $(this);
                const $productItem = $variationForm.closest('[data-component="wcpw-form-item"]');
                const $productItemPrice = $productItem.find('[data-component="wcpw-form-item-price"]');
                const $productItemDescription = $productItem.find('[data-component="wcpw-form-item-description"]');

                $variationForm
                    .find('[data-component="wcpw-form-item-variations-attributes-item-value"]')
                    .each(function () {
                        const $element = $(this);

                        if ($element.prop('tagName') === 'SELECT' && $element.val().length === 0) {
                            allSet = false;
                        }

                        if (exclude && $element.attr('data-name') === exclude) {
                            allSet = false;
                            currentSettings[$element.attr('data-name')] = '';
                        } else if ($element.prop('tagName') === 'SELECT') {
                            currentSettings[$element.attr('data-name')] = $element.val();
                        } else if ($element.prop('checked') === true) {
                            currentSettings[$element.attr('data-name')] = $element.val();
                        }
                    });

                const productId = parseInt($variationForm.data('product_id'), 10);
                let allVariations = $variationForm.data('product_variations');

                // Fallback to window property if not set - backwards compat
                if (!allVariations) {
                    allVariations = window.product_variations.product_id;
                }

                if (!allVariations) {
                    allVariations = window.product_variations;
                }

                if (!allVariations) {
                    allVariations = window[`product_variations_${productId}`];
                }

                const matchingVariations = $.fn.wcpwVariationFormFindMatchingVariations(allVariations, currentSettings);

                if (allSet) {
                    const variation = matchingVariations.shift();

                    if (variation) {
                        // Found - set ID
                        $variationForm
                            .find('[data-component="wcpw-form-item-variations-variation-id"]')
                            .val(variation.variation_id)
                            .change();

                        $variationForm.trigger('foundVariation.variationForm.wcProductsWizard', [variation]);
                    } else if (!focus) {
                        // Nothing found - reset fields
                        $variationForm.trigger('resetImage.variationForm.wcProductsWizard');
                    }
                } else {
                    if (!focus) {
                        $variationForm.trigger('resetImage.variationForm.wcProductsWizard');
                    }

                    if (!exclude) {
                        // reset price
                        $productItemPrice.html($productItemPrice.data('default'));

                        // reset description
                        $productItemDescription.html($productItemDescription.data('default'));
                    }
                }
            })

            // Reset product image
            .on('resetImage.variationForm.wcProductsWizard', function () {
                return $.fn.wcpwVariationFormUpdateImage($form, false);
            })

            // Disable option fields that are unavaiable for current set of attributes
            .on('updateVariationValues.variationForm.wcProductsWizard', function (event, variations) {
                const $variationForm = $(this).closest('[data-component="wcpw-form-item-variations"]');

                // Loop through selects and disable/enable options based on selections
                $variationForm
                    .find('[data-component="wcpw-form-item-variations-attributes-item-value"]')
                    .each(function () {
                        const $element = $(this);

                        // Reset options
                        if (!$element.data('attribute_options')) {
                            $element.data('attribute_options', $element.find('option:gt(0)').get());
                        }

                        $element.find('option:gt(0)').remove();
                        $element.append($element.data('attribute_options'));
                        $element.find('option:gt(0)').removeClass('active');

                        // Get name
                        const currentAttrName = $element.attr('data-name');

                        // Loop through variations
                        for (let num in variations) {
                            if (!variations.hasOwnProperty(num)) {
                                break;
                            }

                            const attributes = variations[num].attributes;

                            for (let attrName in attributes) {
                                if (!attributes.hasOwnProperty(attrName)) {
                                    break;
                                }

                                let attrVal = attributes[attrName];

                                if (attrName !== currentAttrName) {
                                    break;
                                }

                                if (attrVal) {
                                    // Decode entities
                                    attrVal = $('<div/>').html(attrVal).text();
                                    // Add slashes
                                    attrVal = attrVal.replace(/'/g, "\\'");
                                    attrVal = attrVal.replace(/"/g, '"');
                                    // Compare the meerkat
                                    $element.find(`option[value="${attrVal}"]`).addClass('active');
                                } else {
                                    $element.find('option:gt(0)').addClass('active');
                                }
                            }
                        }

                        // Detach inactive
                        $element.find('option:gt(0):not(.active)').remove();
                    });
                // Custom event for when variations have been updated
                $variationForm.trigger('woocommerceUpdateVariationValues');
            })

            // Show single variation details (price, stock, image)
            .on('foundVariation.variationForm.wcProductsWizard', function (event, variation) {
                const $variationForm = $(this);
                const $product = $variationForm.closest('[data-component="wcpw-form-item"]');
                const $productDescription = $product.find('[data-component="wcpw-form-item-description"]');

                // change price
                if (variation.price_html) {
                    $product
                        .find('[data-component="wcpw-form-item-price"]')
                        .html(variation.price_html);
                }

                // change description
                if (variation.description) {
                    // different versions of woocommerce
                    $productDescription.html(variation.description);
                } else if (variation.variation_description) {
                    // different versions of woocommerce
                    $productDescription.html(variation.variation_description);
                } else {
                    $productDescription.html($productDescription.data('default'));
                }

                return $.fn.wcpwVariationFormUpdateImage($form, variation);
            });

        $form.trigger('launched.variationFrom.wcProductsWizard');

        return $form;
    };

    /**
     * Reset a default attribute for an element so it can be reset later
     */
    $.fn.wcpwVariationFormResetAttribute = function (attr) {
        if (undefined !== this.attr(`data-o_${attr}`)) {
            this.attr(attr, this.attr(`data-o_${attr}`));
        }
    };
    /**
     * Stores a default attribute for an element so it can be reset later
     */
    $.fn.wcpwVariationFormSetAttribute = function (attr, value) {
        if (undefined === this.attr(`data-o_${attr}`)) {
            this.attr(`data-o_${attr}`, (!this.attr(attr)) ? '' : this.attr(attr));
        }

        if (false === value) {
            this.removeAttr(attr);
        } else {
            this.attr(attr, value);
        }
    };

    /**
     * Sets product images for the chosen variation
     */
    $.fn.wcpwVariationFormUpdateImage = function ($variationForm, variation) {
        const $product = $variationForm.closest('[data-component="wcpw-form-item"]');
        const $productImg = $product.find('[data-component="wcpw-form-item-thumbnail-image"]');
        const $productLink = $product.find('[data-component="wcpw-form-item-thumbnail-link"]');

        if (variation && variation.image && variation.image.src && variation.image.src.length > 1) {
            $productImg.wcpwVariationFormSetAttribute('src', variation.image_src || variation.image.src);
            $productImg.wcpwVariationFormSetAttribute('srcset', variation.image_srcset || variation.image.srcset);
            $productImg.wcpwVariationFormSetAttribute('sizes', variation.image_sizes || variation.image.sizes);
            $productImg.wcpwVariationFormSetAttribute('title', variation.image_title || variation.image.title);
            $productImg.wcpwVariationFormSetAttribute('alt', variation.image_alt || variation.image.alt);
            $productLink.wcpwVariationFormSetAttribute('href', variation.image_link || variation.image.full_src);
        } else {
            $productImg.wcpwVariationFormResetAttribute('src');
            $productImg.wcpwVariationFormResetAttribute('srcset');
            $productImg.wcpwVariationFormResetAttribute('sizes');
            $productImg.wcpwVariationFormResetAttribute('alt');
            $productLink.wcpwVariationFormResetAttribute('href');
        }
    };

    /**
     * Get product matching variations
     */
    $.fn.wcpwVariationFormFindMatchingVariations = function (productVariations, settings) {
        const matching = [];

        for (let i = 0; i < productVariations.length; i++) {
            const variation = productVariations[i];

            if ($.fn.wcpwVariationFormVariationsMatch(variation.attributes, settings)) {
                matching.push(variation);
            }
        }

        return matching;
    };

    /**
     * Check is variation match
     */
    $.fn.wcpwVariationFormVariationsMatch = function (attrs1, attrs2) {
        let match = true;

        for (let attrName in attrs1) {
            if (!attrs1.hasOwnProperty(attrName)) {
                break;
            }

            const val1 = attrs1[attrName];
            const val2 = attrs2[attrName];

            if (val1 && val2 && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
                match = false;
            }
        }

        return match;
    };

    const init = function () {
        $('[data-component="wcpw-form-item-variations"]').each(function () {
            return $(this).wcpwVariationForm();
        });

        $('[data-component="wcpw-form-item-variations-attributes-item-value"]').trigger('change');
    };

    $(document)
        .ready(() => init())
        .on('ajaxComplete.wcProductsWizard init.variationForm.wcProductsWizard', () => init());
});
