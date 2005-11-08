<?php
/*
 * $Id: prepend.php,v 1.3 2005/11/08 15:43:14 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = dirname(__FILE__)."/../../html/";
$_PHPLIB["libdir"]     = dirname(__FILE__)."/../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");

	/* copied from load_lang.php */
	/** @todo: rewrite load_lang.php to allow use it in shell scripts */
	$lang_set['charset'] = 			"utf-8";
	$lang_set['date_time_format'] = "Y-m-d H:i";
	$lang_set['date_format'] = 		"Y-m-d";
	$lang_set['time_format'] = 		"H:i";
	
	if (!empty($config->data_sql->set_charset)){
		$data->set_db_charset($lang_set['charset'], null, $errors);
	}
	
	if (!empty($config->data_sql->collation)){
		$data->set_db_collation($config->data_sql->collation, null, $errors);
	}

$page_attributes=array();
?>
