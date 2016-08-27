'use strict';

(function($) {
  $(window).load(function() {
    $('.log-table-view').on('scroll', function(e) {
      if( 0 == $(this).scrollTop() ) {
        // load more lines
      }
    });

    // auto-scroll to bottom of log viewers on page load
    $('.log-table-view').each(function() {
      $(this).scrollTop($('table', this).height())
    });
  });
})(jQuery);
