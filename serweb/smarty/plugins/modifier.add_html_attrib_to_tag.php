<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty add_html_attrib_to_tag modifier plugin
 *
 * Type:     modifier<br>
 * Name:     add_html_attrib_to_tag<br>
 * Date:     Feb 24, 2003
 * Purpose:  for variables that contain html tag, add new attribute to them
 * Input:    html tag to which should be attrib added
 * Example:  {$var|cat:"onclick='foo();'"}
 * @author   Karel Kozlik <kozlik@kufr.cz>
 * @version 1.0
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_add_html_attrib_to_tag($string, $cat)
{
	$pos = strrpos($string, ">");
	/* if string isn't html tag, return it unchanged */
	if ($pos === 'false') return $string;
	
	$str1 = substr($string, 0, $pos);
	$str2 = substr($string, $pos);
	
    return $str1.' '.$cat.$str2;
}

/* vim: set expandtab: */

?>
