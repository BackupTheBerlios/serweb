<?php
/*
 * $Id: missed_calls.php,v 1.3 2005/12/14 16:28:37 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('accounting');

$_required_apu = array('apu_accounting'); 

require "prepend.php";

$acc			= new apu_accounting();

$acc->set_opt('display_incoming', false);
$acc->set_opt('display_outgoing', false);
$acc->set_opt('display_missed', true);

/* if you doesn't need this, disable it for perfonmance reasons */
$acc->set_opt('get_user_status', true);
/* if you doesn't need this, disable it for perfonmance reasons */
$acc->set_opt('get_phonebook_names', true);

$acc->set_opt('smarty_result', 'missed_calls');

$page_attributes['user_name'] = get_user_real_name($serweb_auth);


$controler->add_apu($acc);
$controler->set_template_name('u_missed_calls.tpl');
$controler->start();

?>
