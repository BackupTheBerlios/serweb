<?php
/*
 * $Id: whitepages.php,v 1.6 2007/02/05 15:10:37 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers', 'apu_sorter'); 

require "prepend.php";

if (!$config->enable_whitepages) die("Whitepages are disabled in config");

$wp			= new apu_subscribers();
$sr         = new apu_sorter();

$wp->set_sorter($sr);

$wp->set_opt('get_user_sip_uri', true);
$wp->set_opt('get_user_aliases', true);
$wp->set_opt('get_timezones', true);
$wp->set_opt('get_only_agreeing', true);
$wp->set_opt('get_disabled', false);
$wp->set_opt('use_chk_onlineonly', true);
$wp->set_opt('sess_seed', 'wp');
$wp->set_opt('script_phonebook', 'phonebook.php');

$sr->set_opt('default_sort_col', 'name');


$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());
$page_attributes['selected_tab']="phonebook.php";


$controler->add_apu($wp);
$controler->add_apu($sr);
$controler->set_template_name('u_whitepages.tpl');
$controler->start();

?>
