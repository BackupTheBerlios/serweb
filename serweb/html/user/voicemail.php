<?php
/*
 * $Id: voicemail.php,v 1.4 2006/09/08 12:27:32 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('voicemail');

$_required_apu = array('apu_voicemail'); 

require "prepend.php";

$vm			= new apu_voicemail();

$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());


$controler->add_apu($vm);
$controler->set_template_name('u_voicemail.tpl');
$controler->start();

?>
