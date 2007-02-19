<?php
/**
 *	New domain wizard - page attributes
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: page_attributes.php,v 1.5 2007/02/19 09:36:06 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
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
	'epilog'=>"</body>",
	'author_meta_tag'=>"Karel Kozlik <karel at iptel dot org>"
);

?>
