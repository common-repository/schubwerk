(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    function init() {

        // $("#loginForm").on('submit', function (e) {
        //     e.preventDefault();
        //     const submitButton = $('#loginSubmit');
        //     submitButton.attr('disabled', 'disabled');
        //     console.log(e);
        //     $.ajax({
        //         type: "POST",
        //         url: "https://tracker.schubwerk.de/api/login",
        //         dataType: "json",
        //         data: $("#loginForm").serialize(),
        //     })
        //         .done(function (data) {
        //
        //         })
        //         .always(function () {
        //             const token = 'testestest';
        //             $.ajax( window.ajaxurl, {token} ).error(
        //                 function() {
        //                     alert('error');
        //                 }).success( function() {
        //                 alert('success');
        //             }).always(function () {
        //                 submitButton.attr('disabled', false);
        //             });
        //         })
        // })
    }


    $(window).load(function () {
        init();
    });

})(jQuery);
