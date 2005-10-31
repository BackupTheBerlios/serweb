<?php
/*
 * $Id: new_user.php,v 1.1 2005/10/31 16:35:20 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_registration_a'); 


require "prepend.php";

$perm->check("admin");

$page_attributes['selected_tab']="users.php";

$opt = array('get_domain_names' => true,
             'return_all' => true);
if (!$perm->have_perm('hostmaster')){
	$opt['administrated_by'] = $serweb_auth;
}


$allowed_domains = array();
if (false !== $domains = $data->get_domains($opt, $controler->errors)){
	foreach ($domains as $dom) 
		foreach($dom['names'] as $v)
			$allowed_domains[] = $v['name'];
}



$re	= new apu_registration_a();
$re->set_opt('allowed_domains', $allowed_domains);
$re->set_opt('redirect_on_register', 'users.php');
		

$controler->add_apu($re);
$controler->set_template_name('a_new_user.tpl');
$controler->start();


?>
