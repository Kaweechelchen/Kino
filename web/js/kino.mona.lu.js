!function ($) {

    $(function(){

        $(document).ready(function() {
            $(".ellipsis").dotdotdot();
        });

        $('.screeningTime').each(function(idx){
            $(this).html(
                moment.unix(
                    $(this).html()
                ).format(
                    "HH"
                )
                + "<span class=\"minutes\">"
                + moment.unix(
                    $(this).html()
                ).format(
                    "mm"
                )
                + "</span>"
            );
        });

    });

    $( '.movie' ).on( "click", function() {
        $( "[class^='synopsis-']" ).slideUp( function() {
        });
        $( '.synopsis-' + $( this ).data('movieid') ).slideDown( "slow", function() {
        });
    });

}(window.jQuery);