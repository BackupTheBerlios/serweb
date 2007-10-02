<?php
/**
 *	New domain wizard - choose admin
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: 2_existing_admin.php,v 1.5 2007/10/02 13:44:35 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers', 'apu_sorter', 'apu_filter'); 


/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['title'] .= " - ".$lang_str['step']." 2/3";

$sc	= new apu_subscribers();
$sr = new apu_sorter();
$filter	= new apu_filter();

$filter->set_opt('partial_match', false);
$filter->set_opt('filter_name', 'existing_admin');

$sc->set_filter($filter);
$sc->set_sorter($sr);

$smarty->assign('xxl_support', isModuleLoaded('xxl'));
$smarty->assign('finish_url', '3_finish.php?da_assign=1&pr_set_admin_privilege=1');

$sc->set_opt('use_chk_adminsonly', true);
$sc->set_opt('def_chk_adminsonly', true);


$controler->add_apu($sc);
$controler->add_apu($sr);
$controler->add_apu($filter);
$controler->set_template_name('a_wiz_new_domain/2_existing_admin.tpl');
$controler->start();


?>
