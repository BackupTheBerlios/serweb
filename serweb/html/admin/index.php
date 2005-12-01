<?php
/*
 * $Id: index.php,v 1.22 2005/12/01 12:06:10 kozlik Exp $
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_login', 'apu_lang_select'); 

require "prepend.php";

$login	= new apu_login();

unset ($page_attributes['tab_collection']);
$page_attributes['logout']=false;
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
