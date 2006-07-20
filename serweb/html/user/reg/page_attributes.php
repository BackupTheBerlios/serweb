<?
/*
 * $Id: page_attributes.php,v 1.2 2006/07/20 18:44:40 kozlik Exp $
 */ 

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['user_management'] : null,
	'css_file' => multidomain_get_file("styles.css")
);

?>
