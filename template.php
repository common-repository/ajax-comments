<?php
/**
 *
 * This is just for processing the template's comments.php
 * and checkout if we can use it for displaying the comments or not.
 */
 
 
/**
 * ajax_comments_template() - Get the comment display part, from comments.php
 * 
 * This function processes the default theme's comments.php and tries to
 * grab out, the loop that display's comments.
 * If it is sucessful, it will write it to a file, by deafult. 
 *
 * @package WordPress
 * @since version 2.3.1
 *
 * @param    string    $action     Options: write, checktheme, checktemplate 
 */
function ajax_comments_template($action='write')
{
	$handle = @fopen(TEMPLATEPATH . DIRECTORY_SEPARATOR . 'comments.php',"r");
	$f=array();
	$end = array();
	if ($handle) 
	{
	    while (!feof($handle)) 
	    {
    		$buffer = fgets($handle, 4096);
    		$b = trim($buffer);
    		if (strlen($b)>0) $f[]=$buffer;
/**
 * Here I check out if it only contains tabs, and spaces. 
 * if not we save the original value. To have a nice DHTML code... ;)  
 */ 		
    		if (preg_match('/foreach(.*?)(.*?)comments(.*?)as(.*?)comment(.*?):/',$buffer)) $s=count($f)-1;
    		if (preg_match('/(.*?)endforeach;(.*?)/',$buffer)) $end[]=count($f);
	    }
	    fclose($handle);
	}
	
	sort($end);
	
	foreach ($end as $k=>$v) if ($v<$s) unset($end[$k]);	     
/**
 * we unset every impossible value
 * in this case the first value will be the correct endforeach :) 
 *  
 */

	$e = array_shift($end);
	unset($end);
/**
  *    We have the closest element, so we can free up the array.     
  */
	$o = ""; // output buffer
	for ($i = $s; $i < $e; $i ++)
	{
	    $o.=$f[$i];
	}
	
	switch ($action)
	{
    case "checktheme":
      if ($o == "") return false;
      else return true;    
    break;

    case "checktemplate":
      return ajax_comments_template_manage();
    break;
    
    case "write":
      ajax_comments_template_save($o);
    break;
    
    default:
    
    break;
  }
}

/**
 * ajax_comments_template_manage() - Template file management
 * 
 * This function checks whether if the template file, for the default theme
 * could be created.  
 *
 * @package WordPress
 * @since version 2.3.1
 *
 * @param    string    $action     Options: test, template, write
 * @param    string    $template    It's empty by default.  
 */
function ajax_comments_template_manage($action='test',$template=AJAX_COMMENTS_TEMPLATE)
{
   /**
   * In the theme variable we got a "cleaned" version of the template name.
   * So it is safe for use to a filename.
   *   
   */
  $theme = ajax_comments_degenerate($template);
  $template_file = AJAX_COMMENTS_HOME . DIRECTORY_SEPARATOR . AJAX_COMMENTS_TEMPLATE_DIR . DIRECTORY_SEPARATOR . $theme . '-comments.php';
  $template_dir = AJAX_COMMENTS_HOME . DIRECTORY_SEPARATOR . AJAX_COMMENTS_TEMPLATE_DIR;

  if ($action == 'template')
  {
    return $template_file;
  }
  
  if (!is_writable($template_file))
  {
    if (!is_writable($template_dir))
    {
      if (is_writable(AJAX_COMMENTS_HOME))
      {
        mkdir($template_dir);
      }
      else
      {
        if ($action == 'test')
        {
          return FALSE;
        }
        
        wp_die(__("Sorry, " . AJAX_COMMENTS_HOME . "directory is not writable. Can't create template files for comment listing."));
        die; 
      }
    }
    elseif (is_writable($template_dir) && (is_file($template_file) && !is_writable($template_file)))
    {
        if ($action == 'test')
        {
          return FALSE;
        }
        
        wp_die(__("Sorry, " . $template_file . " file is not writable. Can't create template files for comment listing."));
        die;    
    }
  }
  
  if ($action == 'test')
  {
    if ( is_readable($template_file) && (filesize($template_file) > 100) )
    /**
     * This checks, that this template exists. Or it just can be created.
     */         
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  
  return $template_file;
}

/**
 * ajax_comments_template_save() - Saves the template file
 * 
 * This function saves, the data grabbed by ajax_comments_template() as a file
 * that name was generated by ajax_comments_template_manage().  
 *
 * @package WordPress
 * @since version 2.3.1
 *
 * @param    string    $template_data    The output of ajax_comments_template()
 */  
function ajax_comments_template_save($template_data)
{
/**
 * ajax_comments_template_manage() returns with an error, or simply dies.
 * It's no need to double check it here. 
 */
 
  $fn = ajax_comments_template_manage('write'); 
  
  $file_handle = fopen($fn,"w+");
  
  $ret = fwrite($file_handle,$template_data);
  fclose($file_handle);
  
  if ($ret === FALSE)
  {
    wp_die(__("Sorry, can't write to this file: " .$fn . "."));
    die;
  }
  else
  {
    return TRUE;
  }  
}
?>