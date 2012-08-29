<?php
/**
 *	Login page for admin interface
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: index.php,v 1.24 2012/08/29 16:06:42 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_login', 'apu_lang_select'); 

/** include all others necessary files */
require "prepend.php";

$login	= new apu_login();

unset ($page_attributes['tab_collection']);
$page_attributes['logout']=false;
$page_attributes['self_account_delete']=false;
$smarty->assign('domain',$config->domain);

$login->set_opt("auth_class", "phplib_Auth");
$login->set_opt('check_admin_privilege', true);
$login->set_opt('redirect_on_login', 'users.php');

$controler->add_apu($login);

if ($config->allow_change_language_on_login){
	$ls	    = new apu_lang_select();
	$ls->set_opt("smarty_form", "form_ls");
	$controler->add_apu($ls);
}

$controler->set_template_name('a_index.tpl');
$controler->start();


?>
