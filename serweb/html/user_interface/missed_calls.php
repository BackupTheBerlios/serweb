<?
/*
 * $Id: missed_calls.php,v 1.15 2003/08/15 00:36:36 jiri Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

class Cmisc{
	var $sip_from, $time, $sip_status, $status;
	function Cmisc($from_uri, $sip_from, $time, $sip_status, $status){
		$this->from_uri=$from_uri;
		$this->sip_from=$sip_from;
		$this->time=$time;
		$this->sip_status=$sip_status;
		$this->status=$status;
	}
}

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can't connect to sql server"; break;}
	
	if ($delete_calls==1){
	
		$q="select username from ".$config->table_aliases.
			" where 'sip:".$auth->auth["uname"]."@".$config->default_domain."'=contact";

		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$usernames_arr= Array();
		
		while ($row=MySQL_Fetch_Object($res)){
			$usernames_ar[]=$row->username;
		}
		$usernames_ar[]=$auth->auth["uname"];
		
		$usernames_ar=array_unique($usernames_ar);
		
		foreach($usernames_ar as $row){
			$q="delete from ".$config->table_missed_calls." where username='".$row."' and time<'".gmdate("Y-m-d H:i:s", $page_loaded_timestamp)."'";
			$res=mySQL_query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; }
		}
	
	}

	/* we have here a UNION statement -- that speeds up queries a lot as
	   opposed to having an OR condition; it takes mysql 4.0.0 at least
	*/
	$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
			"FROM missed_calls t1 ".
			"WHERE t1.username='".$auth->auth["uname"]."') ".
		"UNION ".
		"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
			"FROM missed_calls t1, aliases t2 ".
			"WHERE 'sip:".$auth->auth["uname"]."@".$config->default_domain."'".
				"=t2.contact AND t2.username=t1.username  ) ".
		"ORDER BY time DESC";
	
	$mc_res=mySQL_query($q);
	if (!$mc_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($mc_res)){
		$mc_arr[]=new Cmisc($row->from_uri, $row->sip_from, $row->time, 
			$row->sip_status, get_status($row->from_uri, $errors));
	}

	set_timezone($errors);
						 
}while (false);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
<script language="JavaScript" src="ctd.js"></script>
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
		else $time=date('Y-m-d H:i',$timestamp);
		
//		if (Substr($row->time,0,10)==date('Y-m-d')) $time="today ".Substr($row->time,11,5);
//		else $time=Substr($row->time,0,16);

		if ($flag==1) {
			$flag=0; $bgc="bgcolor=\"#FFFFFF\"";
		} else {
			$flag = 1; $bgc = "bgcolor=\"#EEEEEE\"";
		}


		echo "<tr valign=top ".$bgc.">";
	?>
	<td align="left" class="f12" width="135"><a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->from_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');"><?echo htmlspecialchars($row->sip_from);?></a></td>
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
<br>
<div align="center"><a href="<?$sess->purl("missed_calls.php?kvrk=".uniqID("")."&delete_calls=1&page_loaded_timestamp=".time());?>"><img src="<?echo $config->img_src_path;?>butons/b_delete_calls.gif" width="165" height="16" border="0"></a></div>

<?}else{?>
<div align="center">No missed calls</div>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
