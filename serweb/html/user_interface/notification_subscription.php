<?
/*
 * $Id: notification_subscription.php,v 1.5 2003/10/13 19:56:43 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

function remove_from_events($uri){
	global $config;

	if (is_array($config->sub_not))
		foreach ($config->sub_not as $key => $row) if ($row->uri==$uri) unset ($config->sub_not[$key]);
}

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	if ($uri and $desc){
		$q="insert into ".$config->table_event." (uri, description, username) values ('$uri', '$desc', '".$auth->auth["uname"]."')";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($dele_id)){
		$q="delete from ".$config->table_event." where username='".$auth->auth["uname"]."' and id=".$dele_id;
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

}while (false);

do{
	if ($db){
		$q="select id, uri, description ".
			"from ".$config->table_event." ".
			"where username='".$auth->auth["uname"]."'";

		$ev_res=mySQL_query($q);
		if (!$ev_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	}

}while (false);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin(6, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="0" align="left">
	<tr><td class="title" width="534">your subscribed events:</td></tr>
	</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>

<?if ($ev_res and MySQL_num_rows($ev_res)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="450">description</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="80">&nbsp;</td>
	</tr>
	<tr><td colspan="3" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?while ($row=MySQL_Fetch_Object($ev_res)){
	remove_from_events($row->uri)
	?>
	<tr valign="top">
	<td align="left" class="f12" width="450"><?echo $row->description;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="80"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqid("")."&dele_id=".$row->id);?>">unsubscribe</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}else{?>
<div align="center">No subscribed events</div>
<?}?>
</td></tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="0" align="left">
	<tr><td class="title" width="534">other events:</td></tr>
	</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<?
if (is_array($config->sub_not) and count($config->sub_not)){?>
<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="450">description</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="80">&nbsp;</td>
	</tr>
	<tr><td colspan="3" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
<?	foreach($config->sub_not as $row){?>
	<tr valign="top">
	<td align="left" class="f12" width="450"><?echo $row->desc;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="80"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqid("")."&desc=".RawURLEncode($row->desc)."&uri=".RawURLEncode($row->uri));?>">subscribe</a></td>
	</tr>
<? }//for each?>
	</table>
</td></tr>
</table>
<?}else{?>
<div align="center">No other events</div>
<?}//end if?>
</td></tr>
</table>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
