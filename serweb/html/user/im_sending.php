<?php
/**
 *	Display window informing that serweb sending instant message
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: im_sending.php,v 1.2 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

/** include all others necessary files */
require "prepend.php";

$cfg=new stdclass();
$cfg->img_src_path                     = $config->img_src_path;

$smarty->assign_by_ref("cfg", $cfg);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_sending_im.tpl');

?>
