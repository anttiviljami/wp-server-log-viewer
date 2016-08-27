'use strict';

(function($) {
  $(window).load(function() {
    $('.log-table-view').on('scroll', function(e) {
      var $this = $(this);
      if( 0 == $this.scrollTop() ) {
        var $overlay = $('<div class="overlay"><div>');
        $this.append( $overlay );
        // load more lines
        var offset = $('td', this).length;
        $.post(window.ajaxurl, {'action':'fetch_log_rows','offset':offset}, function(response) {
          var oldheight = $('table', $this).height();
          console.log(offset);
          $('tbody', $this).prepend(response);
          $this.scrollTop($('table', $this).height() - oldheight);
          $overlay.remove();
        });
      }
    });

    // auto-scroll to bottom of log viewers on page load
    $('.log-table-view').each(function() {
      $(this).scrollTop($('table', this).height())
    });
  });
})(jQuery);
