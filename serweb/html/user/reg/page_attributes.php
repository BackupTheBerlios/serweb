<?php
/**
 *	Set attributes for all pages in registration interface
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: page_attributes.php,v 1.4 2007/02/19 09:36:07 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['user_management'] : null,
	'css_file' => multidomain_get_file("styles.css"),
	'author_meta_tag'=>"Karel Kozlik <karel at iptel dot org>"
);

?>
