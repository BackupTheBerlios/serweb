<?php
/*
 * $Id: caller_screening.php,v 1.3 2005/12/14 16:28:37 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('caller_screening');

$_required_apu = array('apu_caller_screening');

require "prepend.php";

$sc=new apu_caller_screening();



$page_attributes['user_name'] = get_user_real_name($serweb_auth);


$controler->add_apu($sc);
$controler->set_template_name('u_caller_screening.tpl');
$controler->start();


?>
