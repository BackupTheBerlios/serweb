<?php
/*
 * $Id: speed_dial.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_apu = array('apu_speed_dial');

require "prepend.php";

$sd=new apu_speed_dial();



$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);


$controler->add_apu($sd);
$controler->set_template_name('u_speed_dial.tpl');
$controler->start();


?>
