<?php
/**
 *	Delete user's account (after confirmation)
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: account_delete.php,v 1.2 2012/08/29 16:20:50 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('subscribers');

$_required_apu = array('apu_subscribers'); 

/** include all others necessary files */
require "prepend.php";

//if ($controler->come_from_admin_interface){
if (!empty($_GET['admin_interface'])){
	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
}
else{
	$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());
}

$page_attributes['self_account_delete']=false;

$sc = new apu_subscribers();
$sc->set_opt('get_user_list', false);
$sc->set_opt('url_after_self_delete', $sess->url($config->user_pages_path."index.php"));

if (isset($_SERVER["HTTP_REFERER"])) $cancel_url = $_SERVER["HTTP_REFERER"];
else                                 $cancel_url = $sess->url($config->user_pages_path."account_delete.php");


$lang_str['delete_account_description'] = str_replace("<keep_days>", 
                                                      $config->keep_deleted_interval,
                                                      $lang_str['delete_account_description']);

$smarty->assign("cancel_url", $cancel_url);

$controler->add_reqired_javascript("functions.js");
$controler->add_apu($sc);
$controler->set_template_name('u_account_delete.tpl');
$controler->start();

?>
