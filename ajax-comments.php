<?php
/*
Plugin Name: AJAX Comments
Version: 2.1
Plugin URI: http://ajax-comments.dev.rain.hu/
Description: Post comments quickly without leaving or refreshing the page.
Author: DjZoNe
Author URI: http://www.djz.hu
*/

/**
 * We don't need constants, and libraryes for JavaScript display.
 * So I handle it first
 */ 
 

if(strstr($_SERVER['PHP_SELF'], AJAX_COMMENTS_WEBPATH.AJAX_COMMENTS_FILE) && isset($_GET['js']))
{
  header("Content-Type:text/javascript; charset=utf-8");
  require_once('js.inc');
    
  exit;
}

//if(!function_exists('get_option')) require_once('../../../wp-config.php');

require_once('common.php');
require_once('template.php');

if( isset($_POST['ajax-comments-submit']) )
{    
    add_action('comment_post',  'ajax_comments_comment_process');
}
else
{
    // The following block of code deals with the problem of the actual
    // displayed number of comments conflicting with the stored value.
    // it's being increased by 1 every time a comment is being displayed
    // utilizing the 'comment_text' filter as action-hook and finally
    // being output as javascript-variable inside a <script>-block using
    // the hook 'wp_footer'
    
    // start very clever comment count block
    //global $ajax_comments_count;
    
    //$ajax_comments_count = 0;
    //add_action('wp_footer',    'ajax_comments_getcount');
    //add_action('comment_text', 'ajax_comments_countdisplay');
    //add_action('wp_head',      'ajax_comments_js'); 
}


//ajax_comments_template();





$settings = array();
$settings['basic'] = array(
      'loading'=>'Do you want to change the loading symbol for darker one?<br /> (You only need this, if you have a theme with dark background, and dark colors)'
);
$settings['authimg'] = array(
      'authimage'=>'Do you want to integrate <a href="http://wordpress.org/extend/plugins/authimage/">AuthImage</a> to Ajax Comments?',
      'ec3'=>'Do you want to display Event Calendar default category in the <a href="'.$_SERVER['PHP_SELF'].'?page=ace_page_main&amp;subpage=2">Categories</a> tab?'
);


// Receive AJAX requests
// and return a new comment LI element

function ajax_comments_js() { if(is_single()): ?>
<script type="text/javascript" src="<?=get_settings('siteurl').PLUGIN_AJAXCOMMENTS_PATH?>scriptaculous/prototype.js"></script>
<script type="text/javascript" src="<?=get_settings('siteurl').PLUGIN_AJAXCOMMENTS_PATH?>scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="<?=get_settings('siteurl').PLUGIN_AJAXCOMMENTS_PATH.PLUGIN_AJAXCOMMENTS_FILE?>?js"></script>
<?php endif; }

function ajax_comments_inline_js() {
?>
<script type="text/javascript"><!--
$('commentform').onsubmit = ajax_comments_submit;
//--></script>
<?php }
 
function ajax_comments_page_main()
{
  global $wpdb, $targets, $settings;
  
  $subpage = 1;
	if (!empty($_GET['subpage'])) $subpage = intval($_GET['subpage']);
  $current_tab[$subpage] = "class=\"current\"";
  
?>
<ul id="submenu">
   <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ajax_comments_page_main&amp;subpage=1" <?php echo $current_tab[1] ?>>Basic settings</a></li>
   <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ajax_comments_page_main&amp;subpage=2" <?php echo $current_tab[2] ?>>Language settings</a></li>
   <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ajax_comments_page_main&amp;subpage=3" <?php echo $current_tab[3] ?>>Comment display</a></li>
   <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ajax_comments_page_main&amp;subpage=4" <?php echo $current_tab[4] ?>>AuthImage integration</a></li>
   <li><a href="http://ajax-comments.dev.rain.hu/" target="_blank">Plugin homepage</a></li>   
</ul>
<div class="wrap">
    <h2>Ajax Comments</h2><br />
    <?php
      switch ($subpage) 
      {
        case 1: ajax_comments_page_basic(); break;
        case 2: ajax_comments_page_lang(); break;
        case 3: ajax_comments_page_comment(); break;
        case 4: ajax_comments_page_authimg(); break;
      }
    ?>
</div>
<?php
}

function ajax_comments_page_basic()
{
  global $settings;
  
  foreach ($settings['basic'] as $k=>$v)
  {
    $$k = get_option("ajax_comments_settings_".$k);
  }
  reset($settings);
	foreach ($settings['basic'] as $key=>$val): ?> 
	<label>
	    <input type="checkbox" name="<?php echo $key; ?>" <?php if (${$key} == '1') echo "checked"; ?> />
	    <?php echo $val; ?><br /><br /> 
	</label>
	<?php endforeach;
}

function ajax_comments_page_lang() 
{ 
  global $settings;
  print_r($settings); 
}
function ajax_comments_page_comment() { }
function ajax_comments_page_authimg() { }

function ajax_comments_adminmenu()
{
    if ( function_exists('add_submenu_page') ) add_submenu_page('plugins.php', __('ACE Dashboard'), __('Ajax Comments'), 'manage_options', 'ajax_comments_page_main', 'ajax_comments_page_main');
}

add_action('comment_form','ajax_comments_inline_js');
add_action('wp_head','ajax_comments_js'); // Set Hook for outputting JavaScript
add_action('admin_menu', 'ajax_comments_adminmenu');

?>