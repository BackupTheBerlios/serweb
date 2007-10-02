<?php
/**
 *	Search for other users
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: whitepages.php,v 1.8 2007/10/02 13:44:35 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers', 'apu_sorter', 'apu_filter'); 

/** include all others necessary files */
require "prepend.php";

if (!$config->enable_whitepages) die("Whitepages are disabled in config");

$wp			= new apu_subscribers();
$sr         = new apu_sorter();
$filter     = new apu_filter();

$filter->set_opt('partial_match', false);
$filter->set_opt('filter_name', 'white_pages');

$wp->set_filter($filter);
$wp->set_sorter($sr);

$wp->set_opt('get_user_sip_uri', true);
$wp->set_opt('get_user_aliases', true);
$wp->set_opt('get_timezones', true);
$wp->set_opt('get_only_agreeing', true);
$wp->set_opt('get_disabled', false);
$wp->set_opt('use_chk_onlineonly', true);
$wp->set_opt('script_phonebook', 'phonebook.php');

$sr->set_opt('default_sort_col', 'name');


$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());
$page_attributes['selected_tab']="phonebook.php";


$controler->add_apu($wp);
$controler->add_apu($sr);
$controler->add_apu($filter);
$controler->set_template_name('u_whitepages.tpl');
$controler->start();

?>
