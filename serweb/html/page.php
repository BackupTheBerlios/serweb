<?
/*
 * $Id: page.php,v 1.24 2004/03/24 21:39:46 kozlik Exp $
 */

function put_headers(){
	Header("Pragma:  no-cache");
	Header("Cache-Control: no-cache");
	Header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");
}
	
function print_html_head($title=""){
	global $config;	
	
	if (!$title) $title=$config->html_title;
		
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<?if ($title){?><title><?echo $title;?></title><?}?>

	<meta http-equiv="Content-Type" content="text/html; charset=<?echo $config->charset;?>">
	<meta name="Author" content="Karel Kozlik <kozlik@kufr.cz>">
	<meta http-equiv="PRAGMA" content="no-cache"> 
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="<?echo GMDate("D, d M Y H:i:s")." GMT";?>"> 

	<LINK REL="StyleSheet" HREF="<?echo multidomain_get_file("styles.css");?>" TYPE="text/css">

<?	if (is_array($config->html_headers)) foreach($config->html_headers as $v) echo $v."\n"; ?>
</head>

<?} //end function print_html_head()



function print_html_body_begin($parameters=null){
	global $config, $sess, $auth, $errors, $message;

	if (!$parameters) $parameters=null;

	// call user defined function at html body begin
	if (isset($parameters['run_at_html_body_begin']) and function_exists($parameters['run_at_html_body_begin']))
		$parameters['run_at_html_body_begin']($parameters);
	
	virtual(multidomain_get_file($config->html_prolog));
	if ($parameters['title']) echo $parameters['title'];
	virtual(multidomain_get_file($config->html_separator));

?>
<div class="swMain">

	<?if ($parameters['user_name']){?>
	<div class="swHeadingUser"><?echo $parameters['user_name'];?></div>
	<?}?>

	<?if ($auth->auth["uname"]){?>
	<div class="swHeadingLogout"><a href=logout.php>Logout</a></div>
	<?}?>

	<br class="cleaner" /><br />

	<?if ($parameters['tab_collection']) { 
		print_tabs($parameters['tab_collection'], $parameters['path_to_pages'], $parameters['selected_tab']);?>
	<div id="swContent">
	<?}else{?>
	<div id="swContentNoTabs">
	<?}?>

<?	
	print_errors($errors);                    // Display error
	print_message($message);

	if ($errors or $message) echo "<br />";
} //end function print_html_body_begin

	
function print_html_body_end(){
	global $config, $_page_tab;	?>
	</div></div>
<?
	virtual(multidomain_get_file($config->html_epilog));
}

function print_tabs($tabs, $path="", $selected=null){
	global $config, $sess, $PATH_TRANSLATED;

	if (!$selected){
		$selected=basename($PATH_TRANSLATED);
	}
?>
	<div id="swTabs">
		<ul>
<? 

foreach($tabs as $i => $value){
	if ($value->enabled){
		if ($value->page==$selected){?>
		<li id="swActiveTab"><div class="swTabsL"></div><strong><?echo $value->name;?></strong><div class="swTabsR"></div></li>
<?		}
		else{?>
			<li><div class="swTabsL"></div><a href="<?$sess->purl($path.$value->page."?kvrk=".uniqID(""));?>" class="tabl"><?echo $value->name;?></a><div class="swTabsR"></div></li>
<?		}//if ($value->page==$selected)
	}// if ($value->enabled)
} //foreach		?>
		</ul>
	</div>

<?
} //end function print_tabs


	function print_resell_html_body_begin($tab_selected=null){
		global $config, $_page_tab, $sess, $auth;

		virtual($config->prolog);
		echo $config->realm." Reseller Management";
		virtual($config->separator);

		?>
			<div class="f12">
				<table width="200" align="right">
				<td align="right">&nbsp;<a href=logout.php>Logout</a></td>
				<td align="right">&nbsp;<a href=/phpBB/>FAQ</a></td>
				</tr>
				</table>
			</div>
			<br clear="all">
		<?
		
		if ($auth->auth["uname"]==$config->ppaid->operator_id)
			$tabs=$config->ppaid->op_tabs;
		else
			$tabs=$config->ppaid->r_tabs;
		
		print_tabs_new($tabs, $config->ppaid->resell_pages_path, $tab_selected); $_page_tab=1;?>	

 		<table bgcolor="#B1C9DC" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr><td>
			<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="20">
			<tr valign="top"><td>
			
<?	} //print_resell_html_body_begin

?>
