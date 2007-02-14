<?php
/**
 *	Register new user
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: new_user.php,v 1.3 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('registration');

$_required_apu = array('apu_registration'); 


/** include all others necessary files */
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
