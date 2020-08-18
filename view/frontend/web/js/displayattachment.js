define([
    'jquery',
    "mage/template"
], function ($, mageTemplate) {
    'use strict';
    $.widget('htc.DisPlayAttachment', {
        _create: function () {
            var url = this.options.url;
            var product = this.options.product;
            var urlDowload = this.options.urlDowload;
            this._sendAjax(url, product);
            var seft = this;
            $(document).ready(function () {
                $('body').on('click', '.attachment-product-download', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var idAttachment = $(this).attr('data-id-attachment');
                    $.ajax({
                        url: urlDowload,
                        type: "POST",
                        data: {idAttachment: idAttachment},
                        success: function (result) {
                            if (result.notification == 'true') {
                                var link = document.createElement('a');
                                link.href = result.url;
                                link.download = result.file;
                                link.click();
                            } else {
                                alert(result.notification);
                            }
                            seft._sendAjax(url, product);
                        }
                    });
                });

            });


        },
        _sendAjax: function (url, product) {
            $.ajax({
                type: 'post',
                url: url,
                data: {product: product},
                dataType: 'json',
                success: function (result) {
                    var tabAttachment = $('#tab-label-htc-tab-attachment');
                    var elementDetail = $('.product-attachment');
                    var elementAfterCart = $('#after-addtocart-attachment');
                    var elementFooter = $('#after-footer-attachment');
                    elementDetail.html(result.detail);
                    elementAfterCart.html(result.aftercart);
                    elementFooter.html(result.footer);
                    if (result.detail == "") {
                        tabAttachment.hide();
                    }else{
                        tabAttachment.show();
                    }
                }
            });
        }
    });

    return $.htc.DisPlayAttachment;
});
