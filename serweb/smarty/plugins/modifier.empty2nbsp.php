<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty empty2nbsp modifier plugin
 *
 * Type:     modifier<br>
 * Name:     empty2nbsp<br>
 * Purpose:  substitue empty string by &amp;nbsp;
 * @param string
 * @return string
 */
function smarty_modifier_empty2nbsp($str)
{
	if (ereg('^[[:space:]]*$', $str)) return "&nbsp;";
	else return $str;
}

/* vim: set expandtab: */

?>
