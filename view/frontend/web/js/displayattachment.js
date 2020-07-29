define([
    'jquery',
    "mage/template"
], function ($, mageTemplate) {
    'use strict';
    $.widget('htc.DisPlayAttachment', {
        _create: function () {
            var url = this.options.url;
            var product = this.options.product;
            this._sendAjax(url, product);
        },
        _sendAjax: function (url, product) {
            var seft = this;
            $.ajax({
                type: 'post',
                url: url,
                data: {product: product},
                dataType: 'json',
                success: function (result) {
                    var elementDetail = $('.product-attachment');
                    var elementAfterCart = $('#after-addtocart-attachment');
                    var elementFooter = $('#after-footer-attachment');
                    elementDetail.html(result.detail);
                    elementAfterCart.html(result.aftercart);
                    elementFooter.html(result.footer);
                }
            });
        }
    });

    return $.htc.DisPlayAttachment;
});
