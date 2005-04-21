<?php
/*
 * $Id: send_im.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_apu = array('apu_send_im'); 

require "prepend.php";

$im			= new apu_send_im();

$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);


$controler->add_apu($im);
$controler->set_template_name('u_send_im.tpl');
$controler->start();

?>
