<?
/*
 * $Id: accounting.php,v 1.17 2004/03/11 22:30:00 kozlik Exp $
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
			"t1.domain='".$config->realm."' and ".
			"t1.sip_callid=t2.sip_callid and ".
			"t1.sip_method='INVITE' and t2.sip_method='BYE' ".
		"order by t1.time desc";
	$mc_res=mySQL_query($q);
	if (!$mc_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	set_timezone($errors);

}while (false);

/* ----------------------- HTML begin ---------------------- */ 
print_html_head();?>
<script language="JavaScript" src="ctd.js"></script>
<?
$page_attributes['user_name']=get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<?if ($mc_res and MySQL_num_rows($mc_res)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>destination</th>
	<th>call id</th>
	<th>time</th>
	<th>length of call</th>
	<th>hang up</th>
	</tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($mc_res)){
		$odd=$odd?0:1;

		$timestamp=gmmktime(substr($row->time,11,2), 	//hour
							substr($row->time,14,2), 	//minute
							substr($row->time,17,2), 	//second
							substr($row->time,5,2), 	//month
							substr($row->time,8,2), 	//day
							substr($row->time,0,4));	//year

		if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
		else $time=date('Y-m-d H:i',$timestamp);

		if ($row->invft==$row->byeft) $hangup="caller";
		else if ($row->invft==$row->byett) $hangup="callee";
		else $hangup="n/a";

//		if (Substr($row->time,0,10)==date('Y-m-d')) $time="today ".Substr($row->time,11,5);
//		else $time=Substr($row->time,0,16);
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left">
	<a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->to_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');">
	<?echo htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1",$row->sip_to));?></a></td>
	<td align="left"><?echo nbsp_if_empty($row->sip_callid);?></td>
	<td align="left"><?echo nbsp_if_empty($time);?></td>
	<td align="left"><?echo nbsp_if_empty($row->length);?></td>
	<td align="center"><?echo nbsp_if_empty($hangup);?></td>
	</tr>
	<?}?>
	</table>

<?}else{?>
<div class="swNumOfFoundRecords">No calls</div>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
