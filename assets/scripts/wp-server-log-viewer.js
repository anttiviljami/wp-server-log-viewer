'use strict';

(function($) {
  $(window).load(function() {
    $('.log-table-view').on('scroll', function(e) {
      var $this = $(this);
      if( 0 == $this.scrollTop() && 0 == $this.find('.overlay').length ) {
        var $overlay = $('<div class="overlay"><div>');
        $this.append( $overlay );
        // load more lines
        var offset = $('td', this).length;
        var payload = {
          'action': 'fetch_log_rows',
          'logfile': $this.data('logfile'),
          'offset': $('td', this).length,
          'regex': $this.data('regex'),
          'cutoff_bytes': $this.data('logbytes')
        };
        console.log(payload);
        $.post(window.ajaxurl, payload, function(response) {
          var oldheight = $('table', $this).height();
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
