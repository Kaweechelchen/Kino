!function ($) {

   $(function(){

      function formatDay( date ){
         return moment.utc(date, "MM/DD/YY").format("dddd");
      }

      function formatTime( time ){
         return moment.utc(time, "hh:mm a").format("HH:mm");
      }

      function formatDayTime( time ){
         return moment.utc(time, "MM/DD/YY HH:mm").format("dddd MMM Do, HH:mm");
      }

      $('.date-format').each(function(idx){
        $(this).html( moment($(this).html(), "MM/DD/YY").format($(this).data('dateformat')));
      });

      $('.fromnow-format').each(function(idx){
        $(this).html( moment().utc($(this).html(), "MM/DD/YY HH:mm").fromNow());
      });

      $('.time-format').each(function(idx){
        $(this).html( moment($(this).data('date'), "YYYY-MM-DD hh:mm:ss").format($(this).data('dateformat')));
      });

      $('.endtime-format').each(function(idx){
        $(this).html( moment($(this).data('date'), "YYYY-MM-DD hh:mm:ss").add('minutes', $(this).data('runtime')).format($(this).data('dateformat')));
      });

      $('.timeleft').each(function(idx){
        $(this).html( moment($(this).data('date'), "YYYY-MM-DD hh:mm:ss").fromNow() );
      });

      $('.format-time').each(function(index){
         $(this).html( formatTime( $(this).html() ) ) ;
      });

      $('.format-day').each(function(index){
         $(this).html( formatDay( $(this).html() ) ) ;
      });

      $('.format-day-time').each(function(index){
         $(this).html( formatDayTime( $(this).html() ) ) ;
      });

      setInterval(function() {
        $('.timeleft').each(function(idx){
                $(this).html( moment($(this).data('date'), "YYYY-MM-DD hh:mm:ss").fromNow() );
              });
      }, 1000);
   });
}(window.jQuery);

function stickyHeaders(stickies) {

    this.load = function() {

        stickies.each(function(){

            var thisSticky = jQuery(this).wrap('<div class="sticky-header" />');
            thisSticky.parent().height(thisSticky.outerHeight());

            jQuery.data(thisSticky[0], 'pos', thisSticky.offset().top);

        });
    }

    this.scroll = function() {

        stickies.each(function(i){


            var thisSticky = jQuery(this),
                nextSticky = stickies.eq(i+1),
                prevSticky = stickies.eq(i-1),
                pos = jQuery.data(thisSticky[0], 'pos');

            if (pos <= ( jQuery(window).scrollTop() + 50 )) {

                thisSticky.addClass("fixed");

                if (nextSticky.length > 0 && thisSticky.offset().top >= jQuery.data(nextSticky[0], 'pos') - ( thisSticky.outerHeight() + 50 ) ) {

                    thisSticky.addClass("absolute").css("top", jQuery.data(nextSticky[0], 'pos') - thisSticky.outerHeight());

                }

            } else {

                thisSticky.removeClass("fixed");

                if (prevSticky.length > 0 && jQuery(window).scrollTop() <= jQuery.data(thisSticky[0], 'pos')  - ( prevSticky.outerHeight() + 50) ) {

                    prevSticky.removeClass("absolute").removeAttr("style");

                }

            }
        });
    }
}

jQuery(document).ready(function(){

    var newStickies = new stickyHeaders(jQuery(".sticky-header"));

    newStickies.load();

    jQuery(window).on("scroll", function() {

        newStickies.scroll();

    });
});