<?php
/*
 * $Id: domain_layout.php,v 1.4 2006/05/23 09:13:30 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain_layout'); 

require "prepend.php";

$perm->check("admin");


$dl	= new apu_domain_layout();

$page_attributes['selected_tab']="list_of_domains.php";

$layout_files = array();
$layout_files[] = array('filename' => "prolog.html",
						'html' => true);
$layout_files[] = array('filename' => "separator.html",
						'html' => true);
$layout_files[] = array('filename' => "epilog.html",
						'html' => true);
$layout_files[] = array('filename' => "styles.css",
						'html' => false);
$layout_files[] = array('filename' => "layout.css",
						'html' => false);
$layout_files[] = array('filename' => "blue.css",
						'html' => false);
$layout_files[] = array('filename' => "green.css",
						'html' => false);
$layout_files[] = array('filename' => "custom.css",
						'html' => false);
$layout_files[] = array('filename' => "config.ini.php",
						'desc' => $lang_str['lf_config'],
						'html' => false,
						'ini' => true);

$text_files = array();
$text_files[] = array('filename' => "terms.txt",
                      'desc' => $lang_str['lf_terms_and_conditions']);
$text_files[] = array('filename' => "mail_register.txt",
                      'desc' => $lang_str['lf_mail_register']);
$text_files[] = array('filename' => "mail_registered_by_admin.txt",
                      'desc' => $lang_str['lf_mail_register_by_admin']);
$text_files[] = array('filename' => "mail_forgot_password_pass.txt",
                      'desc' => $lang_str['lf_mail_fp_pass']);
$text_files[] = array('filename' => "mail_forgot_password_conf.txt",
                      'desc' => $lang_str['lf_mail_fp_conf']);


$dl->set_opt('layout_files', $layout_files);
$dl->set_opt('text_files', $text_files);

$controler->add_apu($dl);
$controler->add_reqired_javascript('tinymce.js.php');
$controler->set_template_name('a_domain_layout.tpl');
$controler->start();

?>
