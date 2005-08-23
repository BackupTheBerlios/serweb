<?php
/*
 * $Id: voicemail.php,v 1.2 2005/08/23 12:41:18 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('voicemail');

$_required_apu = array('apu_voicemail'); 

require "prepend.php";

$vm			= new apu_voicemail();

$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);


$controler->add_apu($vm);
$controler->set_template_name('u_voicemail.tpl');
$controler->start();

?>
