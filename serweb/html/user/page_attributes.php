<?
/*
 * $Id: page_attributes.php,v 1.2 2005/12/14 16:21:50 kozlik Exp $
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
	'logout'=>true
);

?>
