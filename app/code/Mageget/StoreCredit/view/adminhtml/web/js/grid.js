
define([
    'jquery',
   'underscore',
    'mageUtils',
], function ($, _, utils, uiAlert, validator, Element, browser) {
    'use strict';

    return Element.extend({
        /**
         * {@inheritDoc}
         */
        initialize: function () {
            this._super();
            // Listen for file deletions from the media browser
            $(window).on(function(){

               var data = $(".admin__data-grid-wrap-static .data-grid tr td.col-credit_change").text();

               alert(data);



            });
        },
        
    });
});
