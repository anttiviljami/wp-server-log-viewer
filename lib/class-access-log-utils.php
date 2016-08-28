<?php

if ( ! class_exists('Access_Log_Utils') ) :

class Access_Log_Utils {
  public static function readlines( $filepath, $offset = 0, $lines = 1, $cutoff_bytes = null, $adaptive = true ) {
    // Open file
    $f = @fopen( $filepath, 'rb' );
    $filesize = filesize( $filepath );

    if ($f === false) {
      return false;
    }

    // Sets buffer size
    if ( ! $adaptive ) {
      $buffer = 4096;
    } else {
      $buffer = ( $lines < 2 ? 64 : ( $lines < 10 ? 512 : 4096 ) );
    }

    if( $offset < 0 ) {
      if( is_null( $cutoff_bytes ) ) {
        // Jump to last character
        fseek( $f, -1, SEEK_END );
      }
      else {
        // Jump to cutoff point
        fseek( $f, $cutoff_bytes, SEEK_SET );
      }

      // Read it and adjust line number if necessary
      // (Otherwise the result would be wrong if file doesn't end with a blank line)
      if ( fread( $f, 1 ) != "\n") {
        $lines -= 1;
      }
    }
    else {
      // offset from top of file
      fseek( $f, 0, SEEK_SET );
    }

    // Start reading
    $output = '';
    $chunk = '';

    // save the original number of lines we wanted
    $origlines = $lines;

    // add number of offset lines to $lines so we go far enough in the file
    $lines += $offset < 0 ? abs( $offset + 1 ) : $offset;

    // While we would like more
    while ( $lines >= 0 ) {
      if( $offset < 0 ) {
        // Figure out how far back we should jump
        $seek = min( ftell( $f ), $buffer );

        // Do the jump (backwards, relative to where we are)
        fseek( $f, -$seek, SEEK_CUR );

        // Read a chunk
        $chunk = fread( $f, $seek );

        // Jump back to where we started reading
        fseek( $f, -mb_strlen( $chunk, '8bit' ), SEEK_CUR );

        // prepend it to our output
        $output = $chunk . $output;
      }
      else {
        // Figure out how far back we should jump
        $seek = min( ( $filesize - ftell( $f ) ), $buffer );

        // Read a chunk and append it to our output
        $output = $output . ( $chunk = fread( $f, $seek ));
      }

      // Decrease our line counter
      $lines -= substr_count( $chunk, "\n" );
    }

    // While we have too many lines
    // (Because of buffer size we might have read too many)
    while ( $lines++ < 0 ) {
      if( $offset < 0 ) {
        // Find first newline and remove all text before that
        $output = substr( $output, strpos( $output, "\n" ) + 1 );
      }
      else {
        // Find last newline and remove all text after that
        $output = substr( $output, 0, strrpos( $output, "\n" ) );
      }
    }

    // Close file
    fclose( $f );

    // Strip the offset lines
    $rows = array_filter( explode( "\n", $output ) );
    if( $offset < 0 ) {
      $rows = array_slice( $rows, 0, $origlines );
    }
    else {
      $rows = array_slice( $rows, $offset, $origlines );
    }

    return $rows;
  }
}

endif;
