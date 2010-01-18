<?php
/**
 *  Display and provision URIs
 * 
 *  @author     Karel Kozlik
 *  @version    $Id: uri.php,v 1.1 2010/01/18 15:02:13 kozlik Exp $
 *  @package    serweb
 *  @subpackage user_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
                           "auth" => "phplib_Auth");

$_required_modules = array('uri');

$_required_apu = array('apu_uri_user', 'apu_sorter'); 

/** include all others necessary files */
require "prepend.php";

$apu = new apu_uri_user();
$sr = new apu_sorter();

$apu->set_sorter($sr);

// set allowed domain
if (false === $dom = $_SESSION['auth']->get_did()) $dom = array();
else $dom = array($dom);
$apu->set_opt('allowed_domains', $dom);


// set max URIs per user
$an = &$config->attr_names;
$o = array('uid' => $_SESSION['auth']->get_uid(),
           'did' => $_SESSION['auth']->get_did());
if (false === $max_uris = Attributes::get_attribute($an['max_uri_user'], $o)) return false;

if (!is_null($max_uris)){
    $apu->set_opt('max_uris', $max_uris);
}
else{
    $apu->set_opt('max_uris', 0);
}


$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());


$controler->add_apu($apu);
$controler->add_apu($sr);
$controler->set_template_name('u_uri.tpl');
$controler->start();

?>
