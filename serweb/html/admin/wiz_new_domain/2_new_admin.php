<?php
/*
 * $Id: 2_new_admin.php,v 1.2 2005/12/22 12:54:33 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('registration');

$_required_apu = array('apu_registration'); 


require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['title'] .= " - ".$lang_str['step']." 2/3";

if (isset($_GET['save_domain_id'])){
	/* store ID of domain to the own session variable for the case the domain_id 
	   in the controler will be changed in another window */
	if (!$sess->is_registered('sess_wiz_new_domain')) $sess->register('sess_wiz_new_domain');
	$sess_wiz_new_domain['domain_id'] = $controler->domain_id;
}
if (empty($sess_wiz_new_domain['domain_id'])) $sess_wiz_new_domain['domain_id'] = $controler->domain_id;



$re	= new apu_registration();
$re->set_opt('choose_passw', false);
$re->set_opt('require_confirmation', false);
$re->set_opt('redirect_on_register', '3_finish.php?da_assign=1&pr_set_admin_privilege=1');
$re->set_opt('pre_selected_domain', $sess_wiz_new_domain['domain_id']);

if (!$perm->have_perm('hostmaster')){
	$allowed_domains = $_SESSION['auth']->get_administrated_domains();
	if (false === $allowed_domains) $allowed_domains = array();
	
	$re->set_opt('allowed_domains', $allowed_domains);
}
		

$controler->add_apu($re);
$controler->set_template_name('a_wiz_new_domain/2_new_admin.tpl');
$controler->start();


?>
