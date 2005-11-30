<?php
/*
 * $Id: index.php,v 1.3 2005/11/30 09:58:16 kozlik Exp $
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_login', 'apu_lang_select'); 

require "prepend.php";

$login	= new apu_login();
$login->set_opt("auth_class", "phplib_Auth");


unset ($page_attributes['tab_collection']);
$page_attributes['logout']=false;
$smarty->assign('domain',$config->domain);


$controler->add_apu($login);

if ($config->allow_change_language_on_login){
	$ls	    = new apu_lang_select();
	$ls->set_opt("smarty_form", "form_ls");
	$controler->add_apu($ls);
}

$controler->set_template_name('u_index.tpl');
$controler->start();


?>
