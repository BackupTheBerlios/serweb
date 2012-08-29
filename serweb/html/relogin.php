<?php
/**
 *	File displayed when login session expire and user have to relogin
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: relogin.php,v 1.6 2012/08/29 16:06:42 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

	global $config, $page_attributes, $smarty, $lang_str, $_SERWEB;
	
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
//	$page_attributes['message'] = &$message;

	$smarty->assign_by_ref('parameters', $page_attributes);

	$smarty->assign_by_ref("cfg", $cfg);		
	$smarty->assign_by_ref('lang_str', $lang_str);

	$f_uname = $config->fully_qualified_name_on_login ?
				($this->auth['uname'].'@'.$this->auth['realm']) :
				$this->auth['uname'];

	/* create html form */

	$form = array();
	$form['start']    = '<form name="login_form" action="'.$this->url().'" method=post>';
	$form['username'] = '<input type="text" name="username" id="username" value="'.$f_uname.'" autocomplete="off" size=32 disabled>';
	$form['password'] = '<input type="password" name="password" id="password" size=32 maxlength=128>';
	$form['okey']     = '<input type="submit" name="okey_x" id="okey" value="'.$lang_str['b_login'].'">';
	$form['finish']   = '</form>';

	$smarty->assign_by_ref("form", $form);		

	/* 
	 * assign deprecated smarty variables - 
	 * this is only for backward compatibility with older templates 
	 */
	$smarty->assign('form_action', $this->url());	//deprecated from 30.11.2005
	$smarty->assign('form_username', $f_uname);		//deprecated from 30.11.2005
	$smarty->assign_by_ref('config', $cfg);			//deprecated from 7.12.2004

	
	$smarty->display('relogin.tpl');

	print_html_body_end($page_attributes);

?>
<script language="JavaScript">
<!--
	document.forms['login_form']['password'].focus();
// -->
</script>
</html>

