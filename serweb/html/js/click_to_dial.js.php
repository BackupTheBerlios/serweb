<?
/*
 * $Id: click_to_dial.js.php,v 1.5 2005/09/19 13:46:32 kozlik Exp $
 */

Header("content-type: text/js");

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "../";

require ($_SERWEB["serwebdir"] . "../config/config_paths.php");
?>

var ctd_win=null;

function open_ctd_win(target){
	if (ctd_win != null) ctd_win.close();
	ctd_win=window.open("<?echo $config->js_src_path;?>click_to_dial.php?target="+target+"&kvrk="+Date.parse(new Date()),"ctd_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=20,left=20,width=350,height=100");
	ctd_win.window.focus();
	return;
}

function open_ctd_win_default(target){
	if (ctd_win != null) ctd_win.close();
	ctd_win=window.open("<?echo $config->js_src_path;?>click_to_dial.php?target="+target+"&default_caller=1&kvrk="+Date.parse(new Date()),"ctd_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=20,left=20,width=350,height=100");
	ctd_win.window.focus();
	return;
}

