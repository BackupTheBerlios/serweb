<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_tabs} function plugin
 *
 * Type:     function<br>
 * Name:     html_tabs<br>
 * Date:     Jun 6, 2004<br>
 * Purpose:  return tabs<br>
 * Input:<br>
 *         - tabs = array of tab objects (required)
 *         - path = path to pages (optional, default "")
 *         - selected = selected tab (optional, default actual page)
 *         - no_select = no tab is selected (optional, default false)
 *
 * Examples: {html_tabs tabs=$tabs}
 * @author   Karel Kozlik <kozlik@kufr.cz>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_html_tabs($params, &$smarty){
	global $sess, $_SERVER;
    
    $path = '';
    $selected = NULL;
    $no_select = false;

    extract($params);
	
    if (empty($tabs)) {
        $smarty->trigger_error("html_tabs: missing 'tabs' parameter", E_USER_NOTICE);
        return;
    }
	
	if (!$selected){
		$selected=basename($_SERVER['PATH_TRANSLATED']);
	}

	$out='<div id="swTabs"><ul>';

	foreach($tabs as $i => $value){
		if ($value->enabled){
			if ($value->page==$selected and !$no_select){
				$out.='<li id="swActiveTab"><div class="swTabsL"></div><strong>'.$value->name.'</strong><div class="swTabsR"></div></li>';
			}
			else{
				$out.='<li><div class="swTabsL"></div><a href="'.$sess->url($path.$value->page."?kvrk=".uniqID("")).'" class="tabl">'.$value->name.'</a><div class="swTabsR"></div></li>';
			}//if ($value->page==$selected)
		}// if ($value->enabled)
	} //foreach		
	
	$out.='</ul></div>';

	return $out;
}

/* vim: set expandtab: */

?>
