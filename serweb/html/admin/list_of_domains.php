<?php
/*
 * $Id: list_of_domains.php,v 1.4 2005/12/22 12:54:32 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain_list'); 


require "prepend.php";

$perm->check("admin");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$smarty->assign('hostmaster_actions', $perm->have_perm('hostmaster'));

if (isset($_GET['m_do_deleted'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_domain_deleted_s'],
		'long'  => $lang_str['msg_domain_deleted_l']));
}


$dl	= new apu_domain_list();
if (!$perm->have_perm('hostmaster')){
	if (false === $dom = $_SESSION['auth']->get_administrated_domains()) $dom = array();
	$dl->set_opt('only_domains', $dom);
}

$dl->set_opt('script_create', "wiz_new_domain/1_new_domain.php");

$controler->add_apu($dl);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_list_of_domains.tpl');
$controler->start();


?>
