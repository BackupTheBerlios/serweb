<?php
/**
 *	Set attributes for all pages in registration interface
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: page_attributes.php,v 1.6 2008/01/09 15:26:00 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['user_management'] : null,
	'css_file' => array(multidomain_get_file("styles.css")),
	'ie_selects' => true,
	'author_meta_tag'=>"Karel Kozlik <karel at iptel dot org>"
);

?>
