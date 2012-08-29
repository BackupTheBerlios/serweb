<?php
/**
 *	Set attributes for all pages in user interface
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: page_attributes.php,v 1.9 2012/08/29 16:06:43 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

/**
 *	Get real name of the user
 */
function get_user_real_name($user){
	global $config;

	$at = &$config->attr_names;

	$attrs = &User_Attrs::singleton($user->get_uid());
	if (false === $fname = $attrs->get_attribute($at['fname'])) return false;
	if (false === $lname = $attrs->get_attribute($at['lname'])) return false;

	return array(
		'fname'=>$fname,
		'lname'=>$lname,
		'uname'=>$user->get_username(),
		'realm'=>$user->get_domainname());
}


$page_attributes=array(
	'title' => $config->display_page_heading ? $config->domain." ".$lang_str['user_management'] : null,
	'tab_collection' => $config->user_tabs,
	'path_to_pages' => $config->user_pages_path,
	'logout'=>true,
	'self_account_delete'=> $sess->url($config->user_pages_path."account_delete.php"),
	'css_file' => array(multidomain_get_file("styles.css")),
	'ie_selects' => true,
	'author_meta_tag'=>"Karel Kozlik <karel at iptel dot org>"
);

?>
