<?php
/**
 *	Confirm registration
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: confirmation.php,v 1.6 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array('get_did_by_realm');
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_reg_confirmation');						   

/** include function for registration in jabber */
include "reg_jab.php";
/** include all others necessary files */
require "prepend.php";


$reg_conf=new apu_reg_confirmation();

do {
	$opt = array('check_disabled_flag' => false);
	$did = $data->get_did_by_realm($config->domain, $opt);
	if (false === $did) break;

	if (is_null($did)) break;
		
	$opt = array("did"=>$did);
	if (false === $addr = Attributes::get_attribute($config->attr_names['contact_email'], $opt)) break;
			
	if (!$addr) $addr = $config->mail_header_from;
	$smarty->assign('infomail', $addr);

} while (false);


$controler->add_apu($reg_conf);
$controler->set_template_name('reg_confirmation.tpl');
$controler->start();


?>

