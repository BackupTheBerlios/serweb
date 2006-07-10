<?
/*
 * $Id: confirmation.php,v 1.5 2006/07/10 13:45:05 kozlik Exp $
 */

$_data_layer_required_methods=array('get_did_by_realm');
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_reg_confirmation');						   

include "reg_jab.php";
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

