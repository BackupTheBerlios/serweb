<?
/*
 * $Id: accounting.php,v 1.9 2003/04/05 16:00:35 jiri Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can't connect to sql server"; break;}
	
	$q="select t1.to_uri, t1.sip_to, t1.sip_callid, t1.time, ".
		"t1.fromtag as invft, t2.fromtag as byeft, t2.totag as byett, ".
		"sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) ".
			"as length ".
		"from ".$config->table_accounting." t1, ".
			$config->table_accounting." t2 ".
		"where t1.username='".$auth->auth["uname"]."' and ".
			"t1.sip_callid=t2.sip_callid and ".
			"t1.sip_method='INVITE' and t2.sip_method='BYE' ".
		"order by t1.time desc";
	$mc_res=mySQL_query($q);
	if (!$mc_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
						 
	set_timezone($errors);

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
	<td class="titleT" width="135">destination</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">call id</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">time</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">length of call</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">hang up</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?while ($row=MySQL_Fetch_Object($mc_res)){
		$timestamp=gmmktime(substr($row->time,11,2), 	//hour
							substr($row->time,14,2), 	//minute
							substr($row->time,17,2), 	//second
							substr($row->time,5,2), 	//month
							substr($row->time,8,2), 	//day
							substr($row->time,0,4));	//year
	
		if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
		else $time=date('Y-m-d H:i',$timestamp);

		if ($row->invft==$row->byeft) $hangup="caller";
		else if ($row->invft==$row->byett) $hangup="calleer";
		else $hangup="n/a";

//		if (Substr($row->time,0,10)==date('Y-m-d')) $time="today ".Substr($row->time,11,5);
//		else $time=Substr($row->time,0,16);
	?>
	<tr valign="top">
	<td align="center" class="f12" width="135">
	<a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->to_uri));?>">
	<?echo htmlspecialchars($row->sip_to);?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $row->sip_callid;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $time;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $row->length;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $hangup;?></td>
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
