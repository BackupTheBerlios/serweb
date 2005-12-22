<?
/*
 * $Id: ser_moni.php,v 1.13 2005/12/22 12:54:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('ser_moni');

$_required_apu = array('apu_ser_moni'); 

require "prepend.php";

$perm->check("admin,hostmaster");

$sm	= new apu_ser_moni();

$smarty->assign('uname', $controler->user_id->uname);
$smarty->assign('domain',$config->domain);



$controler->add_apu($sm);
$controler->set_template_name('a_ser_moni.tpl');
$controler->start();

?>
