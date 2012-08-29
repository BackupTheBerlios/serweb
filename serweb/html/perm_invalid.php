<?php
/**
 *	File displayed when permissions are invalid
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: perm_invalid.php,v 1.5 2012/08/29 16:06:42 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

	global $config, $page_attributes, $smarty, $lang_str, $sess;
	
	print_html_head($page_attributes);
	unset ($page_attributes['tab_collection']);
	$page_attributes['logout']=false;
    $page_attributes['self_account_delete']=false;

	print_html_body_begin($page_attributes);

	//create copy of some options from config in order to sensitive options will not accessible via templates
	$cfg=new stdclass();
	$cfg->img_src_path = 		$config->img_src_path;
	$cfg->js_src_path =    		$config->js_src_path;
	$cfg->style_src_path = 		$config->style_src_path;
	$cfg->user_pages_path = 	$config->user_pages_path;
	$cfg->admin_pages_path =	$config->admin_pages_path;
	$cfg->domains_path =		$config->domains_path;


	$eh = &ErrorHandler::singleton();

	$page_attributes['errors'] = &$eh->get_errors_array();
//	$page_attributes['message']=&$message;

	$smarty->assign_by_ref('parameters', $page_attributes);

	$smarty->assign_by_ref("cfg", $cfg);		
	$smarty->assign_by_ref('lang_str', $lang_str);

	$perm_have 		= func_get_arg(0);
	$perm_required 	= func_get_arg(1);

	$smarty->assign('sess_id', 		$sess->id);
	$smarty->assign('auth_uid', 	$_SESSION['auth']->auth["uid"]);
	$smarty->assign('auth_uname', 	$_SESSION['auth']->auth["uname"]);
	$smarty->assign('auth_realm', 	$_SESSION['auth']->auth["realm"]);
	$smarty->assign('perm_have', 	$perm_have);
	$smarty->assign('perm_reqiured', $perm_required);


	$smarty->display('perm_invalid.tpl');

	print_html_body_end($page_attributes);

?>
</html>
