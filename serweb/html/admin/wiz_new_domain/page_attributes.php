<?
/*
 * $Id: page_attributes.php,v 1.3 2006/07/25 08:41:12 kozlik Exp $
 */ 


$page_attributes=array(
	'title' => $lang_str['create_new_domain'],
//	'tab_collection' => $config->admin_tabs,
//	'path_to_pages' => $config->admin_pages_path,
//	'run_at_html_body_begin' => '_disable_unneeded_tabs',
	'logout'=>false,
	'css_file' => multidomain_get_file("styles.css"),
	'prolog'=>"<body class=\"swWizard\"><h1>",
	'separator'=>"</h1><hr class='separator' />",
	'epilog'=>"</body>"
);

?>
