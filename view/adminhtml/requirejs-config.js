/**
 * Adds custom JS dependencies such as validation rules.
 * This ensures the `label-validator` script is loaded on all pages
 * where Magento's UI components or forms may need it.
 */
var config = {
    deps: [
        'Ethnic_ProductRoleImage/js/validation/label-validator'
    ]
};
