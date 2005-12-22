<?php
/*
 * $Id: new_user.php,v 1.2 2005/12/22 12:54:32 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('registration');

$_required_apu = array('apu_registration'); 


require "prepend.php";

$perm->check("admin");

$page_attributes['selected_tab']="users.php";



$re	= new apu_registration();
$re->set_opt('redirect_on_register', 'users.php');
$re->set_opt('choose_passw', false);
$re->set_opt('require_confirmation', false);

if (!$perm->have_perm('hostmaster')){
	$allowed_domains = $_SESSION['auth']->get_administrated_domains();
	if (false === $allowed_domains) $allowed_domains = array();
	
	$re->set_opt('allowed_domains', $allowed_domains);
}
		

$controler->add_apu($re);
$controler->set_template_name('a_new_user.tpl');
$controler->start();


?>
