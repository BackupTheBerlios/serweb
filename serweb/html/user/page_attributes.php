<?
/*
 * $Id: page_attributes.php,v 1.3 2006/07/20 18:44:39 kozlik Exp $
 */ 

function get_user_real_name($user){
	global $config;

	$at = &$config->attr_names;

	$attrs = &User_Attrs::singleton($user->uuid);
	if (false === $fname = $attrs->get_attribute($at['fname'])) return false;
	if (false === $lname = $attrs->get_attribute($at['lname'])) return false;

	return array(
		'fname'=>$fname,
		'lname'=>$lname,
		'uname'=>$user->uname,
		'realm'=>$user->domain);
}


$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['user_management'] : null,
	'tab_collection' => $config->user_tabs,
	'path_to_pages' => $config->user_pages_path,
	'logout'=>true,
	'css_file' => multidomain_get_file("styles.css")
);

?>
