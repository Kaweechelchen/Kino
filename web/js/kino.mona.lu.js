var currentlyDisplaying;
!function ($) {

    $(function(){

    });

    $( '.movie' ).on( "click", function() {

        var movieId = $( this ).data('movieid');

        if ( movieId != currentlyDisplaying ) {

            $( "[class^='synopsis-']" ).slideUp( function() {

            });

            $( '.synopsis-' + movieId ).slideDown( "slow", function() {
                currentlyDisplaying = movieId;
            });

        }

    });

}(window.jQuery);