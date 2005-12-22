<?
/*
 * $Id: confirmation.php,v 1.3 2005/12/22 12:54:34 kozlik Exp $
 */

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_reg_confirmation');						   

include "reg_jab.php";
require "prepend.php";


$reg_conf=new apu_reg_confirmation();

$config->html_headers = array_merge($config->html_headers, 
		array('<style type="text/css">
				body, .swMain{
					background: white;
				}
			   </style>'));
										

$controler->add_apu($reg_conf);
$controler->set_template_name('reg_confirmation.tpl');
$controler->start();


?>

