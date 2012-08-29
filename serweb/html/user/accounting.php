<?php
/**
 *	Display incoming and outgoing calls
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: accounting.php,v 1.9 2012/08/29 16:06:43 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('accounting');

$_required_apu = array('apu_accounting'); 

/** include all others necessary files */
require "prepend.php";

/* Display unpaired BYE records? */
$get_unpaired_bye = false;
if ($config->acc->get_unpaired_bye === true){
    $get_unpaired_bye = true;
}

if ($controler->come_from_admin_interface){
	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";
	
    /* Display unpaired BYE records to admins? */
	if (($config->acc->get_unpaired_bye=="hostmaster" and $perm->have_perm("hostmaster")) or
        ($config->acc->get_unpaired_bye=="admin" and $perm->have_perm("admin"))){

        $get_unpaired_bye = true;
    }
}
else{
	$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());
}

$acc			= new apu_accounting();

$acc->set_opt('display_incoming', false);
$acc->set_opt('display_outgoing', true);
$acc->set_opt('display_missed', false);
$acc->set_opt('get_unpaired_bye', $get_unpaired_bye);

/* if you doesn't need this, disable it for perfonmance reasons */
$acc->set_opt('get_user_status', true);
/* if you doesn't need this, disable it for perfonmance reasons */
$acc->set_opt('get_phonebook_names', true);

$acc->set_opt('smarty_result', 'acc');


//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->enable_ctd            = $config->enable_ctd;

$smarty->assign_by_ref("config", $cfg);
$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$controler->add_apu($acc);
$controler->set_template_name('u_accounting.tpl');
$controler->start();

?>
