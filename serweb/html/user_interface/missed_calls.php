<?
/*
 * $Id: missed_calls.php,v 1.7 2003/03/17 20:01:25 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

class Cmisc{
	var $sip_from, $time, $sip_status, $status;
	function Cmisc($sip_from, $time, $sip_status, $status){
		$this->sip_from=$sip_from;
		$this->time=$time;
		$this->sip_status=$sip_status;
		$this->status=$status;
	}
}

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
	$q="select sip_from, time, sip_status from ".$config->table_missed_calls." where username='".$auth->auth["uname"]."' order by time desc";
	$mc_res=mySQL_query($q);
	if (!$mc_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($mc_res)){
		$mc_arr[]=new Cmisc($row->sip_from, $row->time, $row->sip_status, get_status($row->sip_from, $errors));
	}

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
	print_html_body_begin(3, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?if (isset($mc_arr)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="135">calling subscriber</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="85">status</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">time</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">reply status</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?foreach($mc_arr as $row){
		$timestamp=gmmktime(substr($row->time,11,2), 	//hour
							substr($row->time,14,2), 	//minute
							substr($row->time,17,2), 	//second
							substr($row->time,5,2), 	//month
							substr($row->time,8,2), 	//day
							substr($row->time,0,4));	//year
	
		if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
		else $time=date('Y-m-d H:i',$timestamp)
		
//		if (Substr($row->time,0,10)==date('Y-m-d')) $time="today ".Substr($row->time,11,5);
//		else $time=Substr($row->time,0,16);
	?>
	<tr valign="top">
	<td align="left" class="f12" width="135"><a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->sip_from));?>"><?echo htmlspecialchars($row->sip_from);?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="85"><?echo $row->status;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $time;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $row->sip_status;?></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}else{?>
<div align="center">No missed calls</div>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
