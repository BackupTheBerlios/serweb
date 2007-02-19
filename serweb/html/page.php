<?
/**
 *	Functions for output basic page layout
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: page.php,v 1.35 2007/02/19 09:36:06 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

/**
 *	Put HTTP headers
 */

function put_headers(){
	Header("Pragma:  no-cache");
	Header("Cache-Control: no-cache");
	Header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");
}

/**
 *	Print begin of html document
 *	
 *	Started by <!DOCTYPE....>
 *	flowed <html><head>.....
 *	and ending </head>
 *	
 *	@param	array	$parameters associative array containing info about page
 */	

function print_html_head($parameters=array()){
	global $config, $lang_set;	
	
	if (empty($parameters['html_title'])) $title = $config->html_title;
	else                                  $title = $parameters['html_title'];

	header("Content-Type: text/html; charset=".$lang_set['charset']);

	if ($config->html_doctype=='strict'){
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?	}elseif ($config->html_doctype=='transitional'){
		?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?	}

	echo "<html>\n<head>\n";

	if ($title) echo "<title>".$title."</title>\n"; 
?>

	<meta http-equiv="Content-Type" content="text/html; charset=<?echo $lang_set['charset'];?>">
<? if (!empty($parameters['author_meta_tag'])) { 
	echo "	<meta name=\"Author\" content=\"".$parameters['author_meta_tag']."\">";
	} ?>
	<meta http-equiv="PRAGMA" content="no-cache"> 
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="<?echo GMDate("D, d M Y H:i:s")." GMT";?>"> 

<?
	if (!empty($parameters['css_file'])){
		echo '	<LINK REL="StyleSheet" HREF="'.$parameters['css_file'].'" TYPE="text/css">';
	}

	if (isset($config->html_headers) and is_array($config->html_headers)){
		foreach($config->html_headers as $v) echo $v."\n"; 
	} 
	
	echo "</head>\n";

} //end function print_html_head()



/**
 *	Print begin of html body
 * 
 *  This function should be replaced by smarty template
 *	
 *	@param array $parameters associative array containing info about page
 *  @deprecated
 */	

function print_html_body_begin(&$parameters){
	global $config, $sess, $auth, $errors, $message;

	if (!$parameters) $parameters=null;

	// call user defined function at html body begin
	if (isset($parameters['run_at_html_body_begin']) and function_exists($parameters['run_at_html_body_begin']))
		$parameters['run_at_html_body_begin']($parameters);
	
	if (isset($parameters['prolog'])) echo $parameters['prolog'];
	else virtual(multidomain_get_file($config->html_prolog));

	if (isset($parameters['title']) and $parameters['title']) echo $parameters['title'];

	if (isset($parameters['separator'])) echo $parameters['separator'];
	else virtual(multidomain_get_file($config->html_separator));

	echo "\n<div class=\"swMain\">\n";

	return;

} //end function print_html_body_begin

	
/**
 *	Print end of html body
 * 
 *  This function should be replaced by smarty template
 *	
 *  @deprecated
 */	

function print_html_body_end(&$parameters){
	global $config, $_page_tab;	

	echo "</div><!-- swMain -->\n";

	if (isset($parameters['epilog'])) echo $parameters['epilog'];
	else virtual(multidomain_get_file($config->html_epilog));
}


?>
