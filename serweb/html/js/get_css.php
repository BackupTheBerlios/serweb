<?php
/**
 *	This file providing access to the stylesheet files specific for a module
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: get_css.php,v 1.2 2008/01/09 15:26:00 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

Header("content-type: text/css");

/**
 *  Do not allow to get file with ".." in their name
 *  This is for security reasons
 */
if (false !== strpos($_GET['css'], "..")) die ("Prohibited file name");

/** Include the css file */
if (!empty($_GET['mod'])){
    if (false !== strpos($_GET['mod'], "..")) die ("Prohibited module name");
    require("../../modules/".$_GET['mod']."/".$_GET['css']);
}
else{
    require("../../templates/".$_GET['css']);
}

?>
