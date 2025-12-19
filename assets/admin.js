jQuery(document).ready(function ($) {

    if (typeof $.fn.wpColorPicker === 'undefined') {
        console.error('wpColorPicker not loaded');
        return;
    }

    $('.bscp-color-picker').wpColorPicker();

});
