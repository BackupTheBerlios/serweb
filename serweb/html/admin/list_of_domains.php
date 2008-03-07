<?php
/**
 *	Display list of domains
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: list_of_domains.php,v 1.7 2008/03/07 15:20:02 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain_list', 'apu_filter'); 


/** include all others necessary files */
require "prepend.php";

$perm->check("admin");
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$smarty->assign('hostmaster_actions', $perm->have_perm('hostmaster'));

if (isset($_GET['m_do_deleted'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_domain_deleted_s'],
		'long'  => $lang_str['msg_domain_deleted_l']));
}
if (isset($_GET['m_do_undeleted'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_domain_undeleted_s'],
		'long'  => $lang_str['msg_domain_undeleted_l']));
}
if (isset($_GET['m_do_purged'])){
	$controler->add_message(array(
		'short' => $lang_str['msg_domain_purged_s'],
		'long'  => $lang_str['msg_domain_purged_l']));
}


$dl	= new apu_domain_list();
$filter	= new apu_filter();

$filter->set_opt('partial_match', false);
$filter->set_opt('filter_name', 'list_of_domains');

$dl->set_filter($filter);

if ($perm->have_perm('hostmaster')){
     $dl->set_opt('perm_display_deleted', true);
     $smarty->assign('allow_display_deleted', true);
}
else{
	if (false === $dom = $_SESSION['auth']->get_administrated_domains()) $dom = array();
	$dl->set_opt('only_domains', $dom);
}

$dl->set_opt('script_create', "wiz_new_domain/1_new_domain.php");

$controler->add_apu($dl);
$controler->add_apu($filter);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_list_of_domains.tpl');
$controler->start();


?>
