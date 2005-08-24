<?php
/*
 * $Id: message_store.php,v 1.2 2005/08/24 10:42:33 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('msilo');

$_required_apu = array('apu_msilo'); 

require "prepend.php";

$ms			= new apu_msilo();

$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);

$controler->add_apu($ms);
$controler->set_template_name('u_message_store.tpl');
$controler->start();


?>
