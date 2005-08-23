<?php
/*
 * $Id: phonebook.php,v 1.2 2005/08/23 10:21:01 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('phonebook');

$_required_apu = array('apu_phonebook'); 

require "prepend.php";

$pb			= new apu_phonebook();

/* if you doesn't need this, disable it for perfonmance reasons */
$pb->set_opt('get_user_status', true);
/* if you doesn't need this, disable it for perfonmance reasons */
$pb->set_opt('get_user_aliases', true);

$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);


$controler->add_apu($pb);
$controler->set_template_name('u_phonebook.tpl');
$controler->start();

?>
