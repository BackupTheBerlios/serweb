<?
/*
 * $Id: confirmation.php,v 1.4 2006/05/23 09:13:37 kozlik Exp $
 */

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_reg_confirmation');						   

include "reg_jab.php";
require "prepend.php";


$reg_conf=new apu_reg_confirmation();

										

$controler->add_apu($reg_conf);
$controler->set_template_name('reg_confirmation.tpl');
$controler->start();


?>

