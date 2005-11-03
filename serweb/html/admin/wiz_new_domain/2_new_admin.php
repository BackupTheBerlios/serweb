<?php
/*
 * $Id: 2_new_admin.php,v 1.1 2005/11/03 11:02:08 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_registration_a'); 


require "prepend.php";

$perm->check("admin");

$page_attributes['title'] .= " - ".$lang_str['step']." 2/3";

if (isset($_GET['save_domain_id'])){
	/* store ID of domain to the own session variable for the case the domain_id 
	   in the controler will be changed in another window */
	if (!$sess->is_registered('sess_wiz_new_domain')) $sess->register('sess_wiz_new_domain');
	$sess_wiz_new_domain['domain_id'] = $controler->domain_id;
}
if (empty($sess_wiz_new_domain['domain_id'])) $sess_wiz_new_domain['domain_id'] = $controler->domain_id;

$opt = array('get_domain_names' => true,
             'order_names' => false,
             'return_all' => true);
if (!$perm->have_perm('hostmaster')){
	$opt['administrated_by'] = $serweb_auth;
}


$allowed_domains = array();
$preselected_domain = null;
if (false !== $domains = $data->get_domains($opt, $controler->errors)){
	foreach ($domains as $dom) 
		foreach($dom['names'] as $v){
			$allowed_domains[] = $v['name'];

			if (!$preselected_domain and $v['id']==$sess_wiz_new_domain['domain_id'])
				$preselected_domain = $v['name'];
		}
}



$re	= new apu_registration_a();
$re->set_opt('allowed_domains', $allowed_domains);
$re->set_opt('redirect_on_register', '3_finish.php?da_assign=1&pr_set_admin_privilege=1');
$re->set_opt('pre_selected_domain', $preselected_domain);
		

$controler->add_apu($re);
$controler->set_template_name('a_wiz_new_domain/2_new_admin.tpl');
$controler->start();


?>
