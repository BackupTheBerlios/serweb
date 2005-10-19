<?
/*
 * $Id: page_attributes.php,v 1.4 2005/10/19 11:16:11 kozlik Exp $
 */ 

function _disable_unneeded_tabs(&$page_attributes){
	global $perm, $config;

	if (isset($page_attributes['tab_collection']) and is_array($page_attributes['tab_collection'])){
		foreach($page_attributes['tab_collection'] as $key=>$val){

			if ($val->page == "list_of_admins.php"){
				if (is_object($perm) and !$perm->have_perm("change_priv"))
					$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "customers.php") {
				if (!$config->multidomain or 
				    (is_object($perm) and !$perm->have_perm("hostmaster")))
						$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "user_preferences.php") {
				if ($config->multidomain and 
				    (is_object($perm) and !$perm->have_perm("hostmaster")))
						$page_attributes['tab_collection'][$key]->enabled=false;
			}
			elseif ($val->page == "list_of_domains.php") {
				if (!$config->multidomain) $page_attributes['tab_collection'][$key]->enabled=false;
			}


/*
			if (is_object($perm) and !$perm->have_perm("change_priv")){
				if ($val->page == "list_of_admins.php") $page_attributes['tab_collection'][$key]->enabled=false;
			}
		
			if (is_object($perm) and !$perm->have_perm("hostmaster")){
				if ($val->page == "customers.php") $page_attributes['tab_collection'][$key]->enabled=false;
				if ($val->page == "user_preferences.php" and $config->multidomain) $page_attributes['tab_collection'][$key]->enabled=false;
				
			}

			if (!$config->multidomain){
				if ($val->page == "list_of_domains.php" or $val->page == "customers.php") 
					$page_attributes['tab_collection'][$key]->enabled=false;
		
			}
*/
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
