<?php

if ( ! class_exists('WP_Log_Utils') ) :

class WP_Log_Utils {

  /**
   * WP_Log_Utils::read_log_lines_backwards()
   *
   * Reads $lines of $filename starting from negative $offset backwards.
   * Returns an array of lines in forwards order
   *
   * @param mixed $filepath
   * @param mixed $offset
   * @param int $lines
   * @param mixed $cutoff_bytes
   * @static
   * @access public
   * @return array
   */
  public static function read_log_lines_backwards( $filepath, $offset = -1, $lines = 1, $regex = null, $cutoff_bytes = null ) {
    // Open file
    $f = @fopen( $filepath, 'rb' );
    $filesize = filesize( $filepath );

    if ($f === false) {
      return false;
    }

    // buffer size is 4096 bytes
    $buffer = 4096;

    if( is_null( $cutoff_bytes ) ) {
      // Jump to last character
      fseek( $f, -1, SEEK_END );
    }
    else {
      // Jump to cutoff point
      fseek( $f, $cutoff_bytes - 1, SEEK_SET );
    }

    // Start reading
    $output = [];
    $linebuffer = '';

    // start with a newline if the last character of the file isn't one
    if ( fread( $f, 1 ) != "\n") {
      $linebuffer = "\n";
    }

    // the newline in the end accouts for an extra line
    $lines--;

    // While we would like more
    while ( $lines > 0 ) {
      // Figure out how far back we should jump
      $seek = min( ftell( $f ), $buffer );

      // if this is the last buffer we're looking at we need to take the first
      // line without leading newline into account
      $last_buffer = ( ftell( $f ) <= $buffer );

      // file has ended
      if( $seek <= 0 ) {
        break;
      }

      // Do the jump (backwards, relative to where we are)
      fseek( $f, -$seek, SEEK_CUR );

      // Read a chunk
      $chunk = fread( $f, $seek );

      // Jump back to where we started reading
      fseek( $f, -mb_strlen( $chunk, '8bit' ), SEEK_CUR );

      // prepend it to our line buffer
      $linebuffer = $chunk . $linebuffer;

      // see if there are any complete lines in the line buffer
      $complete_lines = [];

      if( $last_buffer ) {
        // last line is whatever is in the line buffer before the second line
        $complete_lines []= rtrim( substr( $linebuffer, 0, strpos( $linebuffer, "\n" ) ) );
      }

      while( preg_match( '/\n(.*?\n)/s', $linebuffer, $matched ) ) {
        // get the $1 match
        $match = $matched[1];

        // remove matched line from line buffer
        $linebuffer = substr_replace( $linebuffer, '', strpos( $linebuffer, $match ), strlen( $match ) );

        // add the line
        $complete_lines []= rtrim( $match );
      }

      // remove any offset lines off the end
      while( $offset < -1 && count( $complete_lines ) > 0 ) {
        array_pop( $complete_lines );
        $offset++;
      }

      // apply a regex filter
      if( ! is_null( $regex ) ) {
        $complete_lines = preg_grep( $regex, $complete_lines );

        // wrap regex match part in <span class="highlight">
        foreach( $complete_lines as &$line ) {
          $line = preg_replace( $regex, '<span class="highlight">$0</span>', $line );
        }
      }

      // decrement lines needed
      $lines -= count( $complete_lines );

      // prepend complete lines to our output
      $output = array_merge( $complete_lines, $output );
    }

    // remove any lines that might have gone over due to the chunk size
    while( ++$lines < 0 ) {
      array_shift( $output );
    }

    // Close file
    fclose( $f );

    return $output;
  }

}

endif;
