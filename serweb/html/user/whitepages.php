<?php
/*
 * $Id: whitepages.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_apu = array('apu_whitepages'); 

require "prepend.php";

$wp			= new apu_whitepages();


$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);
$page_attributes['selected_tab']="phonebook.php";


$controler->add_apu($wp);
$controler->set_template_name('u_whitepages.tpl');
$controler->start();

?>