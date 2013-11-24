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