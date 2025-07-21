/**
 * Custom validation rule: validate-label-format
 * 
 * This rule ensures the input contains only letters (A-Z, a-z) and spaces,
 * with a maximum length of 7 characters.
 * 
 * Usage: Add `data-validate="{ 'validate-label-format': true }"` to your input field.
 */

define([
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/lib/validation/validator'
], function ($, ui, validator) {
    'use strict';

    /**
     * Add custom validator rule to check for only letters and spaces (1–7 characters)
     */
    validator.addRule(
        'validate-label-format',
        /**
         * @param {String} value - The field value to validate
         * @returns {Boolean} - True if valid, false otherwise
         */
        function (value) {
            return /^[A-Za-z ]{1,7}$/.test(value);
        },
        $.mage.__('Only letters and spaces (max 7 characters) are allowed.')
    );

    return validator;
});
