<?
  /*
  * Smarty plugin
  * -------------------------------------------------------------
  * Type:     function
  * Name:     pager
  * Purpose:  create a paging output to be able to browse long lists 
  * Version:  1.0
  * Date:     jun 4, 2004
  * Last Modified:    Jun 4, 2004
  * Install:  Drop into the plugin direadasctory
  * Author:   Karel Kozlik <kozlik@kufr.cz>
  * -------------------------------------------------------------
  *
  * example:
  * <{pager rowcount=$LISTDATA.rowcount limit=$LISTDATA.limit txt_first=$L_MORE class_num="fl" class_numon="fl" class_text="fl"}>
  *
  *
  */
  function smarty_function_pager($params, &$smarty){
      /* displays paging links to be able to browse in bit set of records
      @param    array     $page            - associative array containing next four items:
                int       'pos'            - number of first item on a page 
                int       'items'          - number of all items
                int       'limit'          - number of items on a page 
                string    'url'            - url of pages - number of first item is appended
				
      @param    int       $link_limit      - number of links to other pages
      @param    string    $txt_prev        - script to go to the prev page
      @param    string    $txt_next        - script to go to the next page
      @param    string    $class_num       - class for the page numbers <A> tag!
      @param    string    $class_numon     - class for the aktive page!
      @param    string    $class_text      - class for the texts
      @param    string    $separator       - string to put between the 1 2 3 pages (1 separator 2 separator);
      @param    string    $display         - if is 'always', the pager even if the items are too few
      */

	global $sess;

	// START INIT
	$link_limit	= 10;
	$separator    = '&nbsp;';
	$class_text   = 'nav';
	$class_num    = 'nav';
	$class_numon  = 'navActual';
	$txt_prev     = 'previous';            // previous
	$txt_next     = 'next';                // next
	$display      = '';
	
	foreach($params as $key=>$value) {
		if ($key == 'page') continue;
		$tmps[strtolower($key)] = $value;
		$tmp = strtolower($key);
		if (!(${$tmp} = $value)) {
			${$tmp} = '';
		}
	}    

	foreach($params['page'] as $key=>$value) {
		$tmps[strtolower($key)] = $value;
		$tmp = strtolower($key);
		if (!(${$tmp} = $value)) {
			${$tmp} = '';
		}
	}    
	// START data check
	$minVars = array('pos', 'items', 'limit', 'url');
	foreach($minVars as $tmp)  {
		if (!isset($params['page'][$tmp])) {
			$smarty->trigger_error('plugin "pager": missing or empty parameter: page["'.$tmp.'"]');
		}
	}

	  
	if ($items <= $limit and $display!='always') return "";
	$out="";

	$lfrom=$pos-($link_limit*$limit); if ($lfrom<0) $lfrom=0;
	$lto=$pos+(($link_limit+1)*$limit); if ($lto>$items) $lto=$items;

	if ($pos>0) $out.='<a href="'.$sess->url($url.((($pos-$limit)>0)?($pos-$limit):0)).'" class="'.$class_text.'">'.$txt_prev.'</a>'.$separator;
	elseif($display=='always') $out.='<span class="'.$class_text.'">'.$txt_prev.'</span>'.$separator;

	for ($i=$lfrom; $i<$lto; $i+=$limit){
		if ($i<=$pos and $pos<($i+$limit)) 
			$out.='<span class="'.$class_numon.'">'.(floor($i/$limit)+1).'</span>'.$separator;
		else 
			$out.='<a href="'.$sess->url($url.$i).'" class="'.$class_num.'">'.(floor($i/$limit)+1).'</a>'.$separator;
	}
	
 	if (($pos+$limit)<$items) 
		$out.='<a href="'.$sess->url($url.($pos+$limit)).'" class="'.$class_text.'">'.$txt_next.'</a>';
	elseif ($display=='always') $out.='<span class="'.$class_text.'">'.$txt_next.'</span>'.$separator;
	
	return $out;  
}



?>