<?
/*
 * $Id: page_attributes.php,v 1.2 2004/08/09 12:21:27 kozlik Exp $
 */ 

function _remove_admin_privileges_from_tabs(&$page_attributes){
	global $perm;

	if (is_object($perm) and !$perm->have_perm("change_priv")){
		foreach($page_attributes['tab_collection'] as $key=>$val)
			if ($val->page == "list_of_admins.php") $page_attributes['tab_collection'][$key]->enabled=false;
	}
}

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." admin interface" : null,
	'tab_collection' => $config->admin_tabs,
	'path_to_pages' => $config->admin_pages_path,
	'run_at_html_body_begin' => '_remove_admin_privileges_from_tabs',
	'logout'=>true

);

?>