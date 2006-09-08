<?php
/*
 * $Id: phonebook.php,v 1.7 2006/09/08 12:27:32 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

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

$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());


//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->enable_whitepages 	= $config->enable_whitepages;
$cfg->enable_ctd 			= $config->enable_ctd;
$smarty->assign_by_ref("config", $cfg);


$controler->add_apu($pb);
$controler->set_template_name('u_phonebook.tpl');
$controler->start();

?>
