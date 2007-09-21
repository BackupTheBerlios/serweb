<?php
/**
 *	Login screen
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: index.php,v 1.5 2007/09/21 14:21:20 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array('get_did_by_realm');
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_login', 'apu_lang_select'); 

/** include all others necessary files */
require "prepend.php";


if (!$config->multidomain) {
	$did = $config->default_did;
}
else{
	$did = $data->get_did_by_realm($config->domain, null);

	if ((false === $did) or is_null($did)) {
		ErrorHandler::add_error("Can't obtain domain ID of domain you want log in (".$config->domain."). See the serweb log for more info.");

		$controler->set_template_name('_default.tpl');
		$controler->start();
		exit;
	}
}

do {
	$opt = array("did"=>$did);
	if (false === $uname_assign_mode = Attributes::get_attribute($config->attr_names['uname_asign_mode'], $opt)) break;
			
	$smarty->assign('allow_register', $uname_assign_mode != 'adminonly');
} while (false);



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
