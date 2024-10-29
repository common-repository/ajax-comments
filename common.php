<?php
/**
 * This file contains common definitions and functions.
 */ 

define('AJAX_COMMENTS_HOME', dirname(__FILE__));
define('AJAX_COMMENTS_FILE', 'ajax-comments.php');
define('AJAX_COMMENTS_WEBPATH', '/wp-content/plugins/ajax-comments/');
define('AJAX_COMMENTS_TEMPLATE', get_option('template'));
define('AJAX_COMMENTS_TEMPLATE_DIR', 'templates');

/**
 * This function is for removing unwanted characters from a string.
 *  
 */
  function ajax_comments_degenerate($s)
  {
/**
 *  Set an intial value for the output ($o)
 *  Get the length of the give string ($l)
 *  Transform every character to lowercase ($s)  
 *   
 */   
    $o = '';
    $l = strlen($s);
    $s = strtolower($s);
    
    if ($l > 0)
    {
      for( $i=0; $i < $l; $i++ )
      {
        $c = ord($s[$i]); // ascii code of the string  
/**
 * We chech here the ASCII code of each character in the string
 * If it is a number (between 48 and 58), we give it to the output buffer alone 
 * If it is a lowercase (between 98 and 122) letter we give it to the buffer as well
 * 
 */
        if ( (($c >= 48) && ($c <= 57)) || (($c >= 97) && ($c <= 122)))
        {
          $o.=$s[$i];
        }
      }
      
      if (strlen($o) > 0)
      {
        return $o;
      }
    }
    
    return false;  
  }
?>