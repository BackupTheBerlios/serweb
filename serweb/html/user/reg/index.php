<?
/*
 * $Id: index.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_registration');						   

require "prepend.php";
require($_SERWEB["serwebdir"] . "load_apu.php");

$register=new apu_registration();
$register->set_opt('form_name', 'form1');

										
$config->html_headers = array_merge($config->html_headers, 
		array('<style type="text/css">
				body, .swMain{
					background: white;
				}
			   </style>'));
										

$controler->add_apu($register);
$controler->set_template_name('registration.tpl');
$controler->start();


?>
