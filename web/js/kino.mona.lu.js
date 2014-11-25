!function ($) {

    $(function(){

    });

    $( '.movie' ).on( "click", function() {
        $( "[class^='synopsis-']" ).slideUp( function() {
        });
        $( '.synopsis-' + $( this ).data('movieid') ).slideDown( "slow", function() {
        });
    });

}(window.jQuery);