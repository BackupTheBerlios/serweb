<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty CSV modifier plugin
 *
 * Type:     modifier<br>
 * Name:     toCSV<br>
 * Purpose:  modify string to that can be used in CSV output
 * @param string
 * @return string
 */
function smarty_modifier_toCSV($string)
{
	$string=str_replace('"','""',$string);	//first double all quotes
	$pos1=strpos($string,'"');				//if string contains " or , insert them between "
	$pos2=strpos($string,',');
	if (!($pos1===false and $pos2===false)) $string='"'.$string.'"';
	return $string;
}

?>
