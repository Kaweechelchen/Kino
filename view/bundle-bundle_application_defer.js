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
        $(this).html( moment($(this).html(), "MM/DD/YY").format($(this).attr('data-dateformat')));
      });

      $('.fromnow-format').each(function(idx){
        $(this).html( moment().utc($(this).html(), "MM/DD/YY HH:mm").fromNow());
      });

      $('.time-format').each(function(idx){
        $(this).html( moment($(this).html(), "hh:mm a").format($(this).attr('data-dateformat')));
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
   });
}(window.jQuery);