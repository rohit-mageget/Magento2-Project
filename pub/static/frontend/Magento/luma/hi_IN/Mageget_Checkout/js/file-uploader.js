/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global Base64 */
define([
    'jquery',
    'underscore',
    'mageUtils',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/lib/validation/validator',
    'Magento_Ui/js/form/element/file-uploader',
    'mage/adminhtml/browser'
], function ($, _, utils, uiAlert, validator, Element, browser) {
    'use strict';

    return Element.extend({
        /**
         * {@inheritDoc}
         */
        initialize: function () {
            this._super();
            // alert("js testing11");
        },
        uploadAttachment: function () {
            // alert("uploadAttachment");
            var form = $(".checkout-index-index form#co-shipping-form").get(0);
                var data = new FormData(form);
                var img = $('.checkout-index-index form#co-shipping-form #image')[0].files[0];
                data.append('image',img);
    
                $.ajax({
                     url: 'http://mage.magento.com/mageget/index/index',
                     type: "POST",
                     data: data,
                     enctype: 'multipart/form-data',
                     processData: false,
                     contentType: false,
                     showLoader: true,
                     success: function (response) {
                         alert(response.message);
                         $(".checkout-index-index #attachmentFiles").append(response.data.html);
                        //  $('#frm_attachment')[0].reset();
                     },
                    error: function (response) {
                         alert(response.message);
                        //  $('#frm_attachment')[0].reset();
                    }
                 });
           
        },
    });
});
