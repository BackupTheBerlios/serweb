<?
/*
 * $Id: page_attributes.php,v 1.2 2004/08/09 12:21:27 kozlik Exp $
 */ 

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." user management" : null,
	'tab_collection' => $config->user_tabs,
	'path_to_pages' => $config->user_pages_path,
	'logout'=>true
);

?>