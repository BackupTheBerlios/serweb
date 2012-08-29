<?php
/**
 *	Select admin
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: admin_select.php,v 1.6 2012/08/29 16:06:42 kozlik Exp $
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

$sc	= new apu_subscribers();
$sr = new apu_sorter();
$filter	= new apu_filter();

$filter->set_opt('partial_match', false);
$filter->set_opt('filter_name', 'admin_select');

$sc->set_filter($filter);
$sc->set_sorter($sr);

$smarty->assign('xxl_support', isModuleLoaded('xxl'));
$smarty->assign('finish_url', 'admin_assign.php?da_assign=1&pr_set_admin_privilege=1');


$page_attributes['tab_collection'] = array();
$page_attributes['logout']	 = false;
$page_attributes['self_account_delete']=false;
$page_attributes['prolog']	 ="<body class=\"swWizard\"><h1>";
$page_attributes['separator']="</h1><hr class='separator' />";
$page_attributes['epilog']	 ="</body>";


$sc->set_opt('use_chk_adminsonly', true);
$sc->set_opt('def_chk_adminsonly', true);


$controler->add_apu($sc);
$controler->add_apu($sr);
$controler->add_apu($filter);
$controler->set_template_name('a_admin_select.tpl');
$controler->start();


?>

