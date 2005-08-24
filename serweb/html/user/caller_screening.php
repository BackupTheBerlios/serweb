<?php
/*
 * $Id: caller_screening.php,v 1.2 2005/08/24 11:44:11 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('caller_screening');

$_required_apu = array('apu_caller_screening');

require "prepend.php";

$sc=new apu_caller_screening();



$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);


$controler->add_apu($sc);
$controler->set_template_name('u_caller_screening.tpl');
$controler->start();


?>
