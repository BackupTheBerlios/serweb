<?php
/*
 * $Id: admin_select.php,v 1.2 2006/11/28 14:48:54 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers'); 


require "prepend.php";

$perm->check("admin,hostmaster");

$sc	= new apu_subscribers();

$smarty->assign('xxl_support', isModuleLoaded('xxl'));
$smarty->assign('finish_url', 'admin_assign.php?da_assign=1&pr_set_admin_privilege=1');


$page_attributes['tab_collection'] = array();
$page_attributes['logout']	 = false;
$page_attributes['prolog']	 ="<body class=\"swWizard\"><h1>";
$page_attributes['separator']="</h1><hr class='separator' />";
$page_attributes['epilog']	 ="</body>";


$sc->set_opt('use_chk_adminsonly', true);
$sc->set_opt('def_chk_adminsonly', true);
$sc->set_opt('sess_seed', 1);


$controler->add_apu($sc);
$controler->set_template_name('a_admin_select.tpl');
$controler->start();


?>

