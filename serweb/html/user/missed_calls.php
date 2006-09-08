<?php
/*
 * $Id: missed_calls.php,v 1.5 2006/09/08 12:27:32 kozlik Exp $
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

$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());

//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->enable_ctd            = $config->enable_ctd;

$smarty->assign_by_ref("config", $cfg);


$controler->add_apu($acc);
$controler->set_template_name('u_missed_calls.tpl');
$controler->start();

?>
