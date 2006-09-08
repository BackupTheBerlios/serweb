<?php
/*
 * $Id: message_store.php,v 1.4 2006/09/08 12:27:32 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('msilo');

$_required_apu = array('apu_msilo'); 

require "prepend.php";

$ms			= new apu_msilo();

$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());

$controler->add_apu($ms);
$controler->set_template_name('u_message_store.tpl');
$controler->start();


?>
