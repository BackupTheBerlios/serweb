<?
/*
 * $Id: page_attributes.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $
 */ 

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['user_management'] : null,
	'tab_collection' => $config->user_tabs,
	'path_to_pages' => $config->user_pages_path,
	'logout'=>true
);

?>