<?
require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
	$q="select t1.sip_to, t1.sip_callid, t1.time, sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length ".
		"from ".$config->table_accounting." t1, ".$config->table_accounting." t2 ".
		"where t1.user='".$auth->auth["uname"]."' and t2.user='".$auth->auth["uname"]."' and ".
			"t1.sip_callid=t2.sip_callid and t1.sip_method='INVITE' and t2.sip_method='BYE' ".
		"order by t1.time desc";
	$mc_res=mySQL_query($q);
	if (!$mc_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
						 
}while (false);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin(4, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?if ($mc_res and MySQL_num_rows($mc_res)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="135">call subscriber</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">call id</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">time</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">length of call</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?while ($row=MySQL_Fetch_Object($mc_res)){
		if (Substr($row->time,0,10)==date('Y-m-d')) $time="today ".Substr($row->time,11,5);
		else $time=Substr($row->time,0,16);
	?>
	<tr valign="top">
	<td align="center" class="f12" width="135"><a href="javascript: alert('click to dial not implemented');"><?echo $row->sip_to;?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $row->sip_callid;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $time;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $row->length;?></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}else{?>
<div align="center">No calls</div>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
