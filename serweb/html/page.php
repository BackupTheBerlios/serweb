<?
/**
 * Functions for output basic page layout
 * 
 * @author    Karel Kozlik
 * @version   $Id: page.php,v 1.32 2005/10/19 10:00:46 kozlik Exp $
 * @package   serweb
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
 *	Started by &lt;!DOCTYPE....&gt; <br>
 *	flowed &lt;html&gt;&lt;head&gt;.....
 *	and ending &lt;/head&gt;
 *	
 *	@param string $title Content of html &lt;title&gt; tag
 */	

function print_html_head($title=""){
	global $config, $lang_set;	
	
	if (!$title) $title=$config->html_title;

	header("Content-Type: text/html; charset=".$lang_set['charset']);

	if ($config->html_doctype=='strict'){
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?	}elseif ($config->html_doctype=='transitional'){
		?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?	}?>
<html>
<head>
<?if ($title){?><title><?echo $title;?></title><?}?>

	<meta http-equiv="Content-Type" content="text/html; charset=<?echo $lang_set['charset'];?>">
	<meta name="Author" content="Karel Kozlik <karel at iptel dot org>">
	<meta http-equiv="PRAGMA" content="no-cache"> 
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="<?echo GMDate("D, d M Y H:i:s")." GMT";?>"> 

	<LINK REL="StyleSheet" HREF="<?echo multidomain_get_file("styles.css");?>" TYPE="text/css">

<?	if (is_array($config->html_headers)) foreach($config->html_headers as $v) echo $v."\n"; ?>
</head>

<?} //end function print_html_head()



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

?>
<div class="swMain">
<?  	
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
	global $config, $_page_tab;	?>
	</div>

<?
	if (isset($parameters['epilog'])) echo $parameters['epilog'];
	else virtual(multidomain_get_file($config->html_epilog));
}


?>
