<?
/*
 * $Id: accounting.php,v 1.20 2004/03/24 21:39:46 kozlik Exp $
 */

require "prepend.php";


put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can't connect to sql server"; break;}

	/*
		select calls from accounting table
		first SELECT selects pairs INVITE,BYE and unpaired INVITE records
		second SELECT selects unpaired BYE records
	*/
	
	$q="(select t1.to_uri as inv_to_uri, t1.sip_to as inv_sip_to, t1.sip_callid as inv_callid, t1.time as inv_time, t1.fromtag as inv_fromtag, 
			t2.to_uri as bye_to_uri, t2.sip_to as bye_sip_to, t2.sip_callid as bye_callid, t2.time as bye_time, t2.fromtag as bye_fromtag, t2.totag as bye_totag, 
			t2.from_uri as bye_from_uri, t2.sip_from as bye_sip_from, 
 			sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length, ifnull(t1.time, t2.time) as ttime 
	 from ".$config->table_accounting." t1 left outer join ".$config->table_accounting." t2 on 
			t1.sip_callid=t2.sip_callid and
			((t1.totag=t2.totag and t1.fromtag=t2.fromtag) or
			 (t1.totag=t2.fromtag and t1.fromtag=t2.totag)) and 
			t2.sip_method='BYE' 
	 where t1.username='".$auth->auth["uname"]."' and t1.domain='".$config->realm."' and t1.sip_method='INVITE' )
	
	union

	(select t1.to_uri as inv_to_uri, t1.sip_to as inv_sip_to, t1.sip_callid as inv_callid, t1.time as inv_time, t1.fromtag as inv_fromtag, 
			t2.to_uri as bye_to_uri, t2.sip_to as bye_sip_to, t2.sip_callid as bye_callid, t2.time as bye_time, t2.fromtag as bye_fromtag, t2.totag as bye_totag, 
			t2.from_uri as bye_from_uri, t2.sip_from as bye_sip_from, 
 			sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length, ifnull(t1.time, t2.time) as ttime 
	from ".$config->table_accounting." t1 right outer join ".$config->table_accounting." t2 on
			t1.sip_callid=t2.sip_callid and
			((t1.totag=t2.totag and t1.fromtag=t2.fromtag) or
			 (t1.totag=t2.fromtag and t1.fromtag=t2.totag)) and 
			t1.sip_method='INVITE'
	where t2.username='".$auth->auth["uname"]."' and t2.domain='".$config->realm."' and t2.sip_method='BYE' and isnull(t1.username) )

   order by ttime desc";

   
	$mc_res=mySQL_query($q);
	if (!$mc_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	set_timezone($errors);

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
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

		$timestamp=gmmktime(substr($row->ttime,11,2), 	//hour
							substr($row->ttime,14,2), 	//minute
							substr($row->ttime,17,2), 	//second
							substr($row->ttime,5,2), 	//month
							substr($row->ttime,8,2), 	//day
							substr($row->ttime,0,4));	//year

		if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
		else $time=date('Y-m-d H:i',$timestamp);


		if (!$row->bye_callid){ //unpaired record, only INVITE
			$sip_callid=$row->inv_callid;
			$to_uri=$row->inv_to_uri;
			$sip_to=$row->inv_sip_to;

			$length="n/a";
			$hangup="n/a";
		}
		elseif (!$row->inv_callid){ //unpaired record, only BYE
			$sip_callid=$row->bye_callid;
			$to_uri=$row->bye_from_uri;
			$sip_to=$row->bye_sip_from;

			$length="n/a";
			$hangup="n/a";
		}
		else{
			$sip_callid=$row->inv_callid;
			$to_uri=$row->inv_to_uri;
			$sip_to=$row->inv_sip_to;

			if ($row->inv_fromtag==$row->bye_fromtag) $hangup="caller";
			else if ($row->inv_fromtag==$row->bye_totag) $hangup="callee";
			else $hangup="n/a";
			
			$length=$row->length;
		}

//		if (Substr($row->time,0,10)==date('Y-m-d')) $time="today ".Substr($row->time,11,5);
//		else $time=Substr($row->time,0,16);
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left">
	<a href="javascript: open_ctd_win2('<?echo rawURLEncode($to_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');">
	<?echo htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1",$sip_to));?></a></td>
	<td align="left"><?echo nbsp_if_empty($sip_callid);?></td>
	<td align="left"><?echo nbsp_if_empty($time);?></td>
	<td align="left"><?echo nbsp_if_empty($length);?></td>
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
