<?
/*
 * $Id: page_attributes.php,v 1.5 2005/12/22 12:54:32 kozlik Exp $
 */ 

function _disable_unneeded_tabs(&$page_attributes){
	global $perm, $config;

	if (isset($page_attributes['tab_collection']) and is_array($page_attributes['tab_collection'])){
		foreach($page_attributes['tab_collection'] as $key=>$val){

			if ($val->page == "list_of_admins.php"){
				if (is_object($perm) and !$perm->have_perm("hostmaster"))
					$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "customers.php") {
				if (is_object($perm) and !$perm->have_perm("hostmaster"))
					$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "user_preferences.php") {
				if (is_object($perm) and !$perm->have_perm("hostmaster"))
					$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "ser_moni.php") {
				if (is_object($perm) and !$perm->have_perm("hostmaster"))
					$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "global_attributes.php") {
				if (is_object($perm) and !$perm->have_perm("hostmaster"))
					$page_attributes['tab_collection'][$key]->enabled=false;
			}
		}
	}
}

$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['admin_interface'] : null,
	'tab_collection' => $config->admin_tabs,
	'path_to_pages' => $config->admin_pages_path,
	'run_at_html_body_begin' => '_disable_unneeded_tabs',
	'logout'=>true,
	'prolog'=>"<body><h1>",
	'separator'=>"</h1><hr>",
	'epilog'=>"</body>"
);

?>
