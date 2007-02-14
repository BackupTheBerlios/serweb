<?php
/**
 *	Display incoming instant messages
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: message_store.php,v 1.5 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('msilo');

$_required_apu = array('apu_msilo'); 

/** include all others necessary files */
require "prepend.php";

$ms			= new apu_msilo();

$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());

$controler->add_apu($ms);
$controler->set_template_name('u_message_store.tpl');
$controler->start();


?>
