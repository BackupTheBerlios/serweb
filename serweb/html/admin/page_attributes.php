<?
/*
 * $Id: page_attributes.php,v 1.12 2006/09/12 12:05:44 kozlik Exp $
 */ 

function _disable_unneeded_tabs(&$page_attributes){
	global $perm, $config;

	if (isset($page_attributes['tab_collection']) and is_array($page_attributes['tab_collection'])){
		foreach($page_attributes['tab_collection'] as $key=>$val){

			if ($val->page == "customers.php") {
				if ((is_object($perm) and !$perm->have_perm("hostmaster")) or
				    !$config->multidomain)
					$page_attributes['tab_collection'][$key]->disable();
			}
			elseif ($val->page == "list_of_domains.php") {
				if (!$config->multidomain)
					$page_attributes['tab_collection'][$key]->disable();
			}
			/* disable tabs for hostmaster only if logged user has not hostmaster privilege */
			elseif(in_array($val->page, $config->hostmaster_only_tabs)){
				if (is_object($perm) and !$perm->have_perm("hostmaster"))
					$page_attributes['tab_collection'][$key]->disable();
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
	'css_file' => multidomain_get_file("styles.css"),
	'prolog'=>"<body><h1>",
	'separator'=>"</h1><hr class='separator' />",
	'epilog'=>"</body>"
);

?>
