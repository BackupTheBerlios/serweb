<?
/*
 * $Id: missed_calls.php,v 1.23 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

if (!$sess->is_registered('sess_mc_act_row')) $sess->register('sess_mc_act_row');
if (!isset($sess_mc_act_row)) $sess_mc_act_row=0;

if (isset($HTTP_GET_VARS['act_row'])) $sess_mc_act_row=$HTTP_GET_VARS['act_row'];


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
	if (!$db = connect_to_db($errors)) break;

	if (isset($_GET['delete_calls'])){

		$q="select username, domain from ".$config->table_aliases.
			" where 'sip:".$auth->auth["uname"]."@".$config->default_domain."'=contact";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

		$usernames_ar= Array();
		$domain_ar= Array();

		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			$usernames_ar[]=$row->username;
			$domain_ar[]=$row->domain;
		}
		$res->free();

		$usernames_ar[]=$auth->auth["uname"];
		$domain_ar[]=$config->realm;

		/* $usernames_ar=array_unique($usernames_ar); */

		/* foreach($usernames_ar as $row){ */

		reset($usernames_ar);reset($domain_ar);
		while(list(,$row)=each($usernames_ar) and list(,$dom)=each($domain_ar)) {

			$q="delete from ".$config->table_missed_calls.
				" where username='".$row."' and domain='".$dom."' ".
				" and time<'".gmdate("Y-m-d H:i:s", $page_loaded_timestamp)."'";
			$res=$db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); break;}
		}
		$sess_mc_act_row=0;

		if (isset($errors) and $errors) break;

        Header("Location: ".$sess->url("missed_calls.php?kvrk=".uniqID("")."&message=".RawURLEncode("calls deleted succesfully")));
		page_close();
		exit;
	}

	/* we have here a UNION statement -- that speeds up queries a lot as
	   opposed to having an OR condition; it takes mysql 4.0.0 at least
	*/
	$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
			"FROM ".$config->table_missed_calls." t1 ".
			"WHERE t1.username='".$auth->auth["uname"]."' and t1.domain='".$config->default_domain."' ) ".
		"UNION ".
		"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
			"FROM ".$config->table_missed_calls." t1, ".$config->table_aliases." t2 ".
			"WHERE 'sip:".$auth->auth["uname"]."@".$config->default_domain."'".
				"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ";

	$mc_res=$db->query($q);
	if (DB::isError($mc_res)) {log_errors($mc_res, $errors); break;}

	$num_rows=$mc_res->numRows();
	$mc_res->free();

	if ($sess_mc_act_row >= $num_rows) $sess_mc_act_row=max(0, $num_rows-$config->num_of_showed_items);


	$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
			"FROM ".$config->table_missed_calls." t1 ".
			"WHERE t1.username='".$auth->auth["uname"]."' and t1.domain='".$config->default_domain."' ) ".
		"UNION ".
		"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
			"FROM ".$config->table_missed_calls." t1, ".$config->table_aliases." t2 ".
			"WHERE 'sip:".$auth->auth["uname"]."@".$config->default_domain."'".
				"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ".
		"ORDER BY time DESC ".
		"limit ".$sess_mc_act_row.", ".$config->num_of_showed_items;

	$mc_res=$db->query($q);
	if (DB::isError($mc_res)) {log_errors($mc_res, $errors); break;}

	while ($row=$mc_res->fetchRow(DB_FETCHMODE_OBJECT)){
		$mc_arr[]=new Cmisc($row->from_uri, $row->sip_from, $row->time,
			$row->sip_status, get_status($row->from_uri, $db, $errors));
	}
	$mc_res->free();

        set_timezone($db, $errors);

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=get_user_name($db, $errors);
print_html_body_begin($page_attributes);
?>

<?if (isset($mc_arr)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>calling subscriber</th>
	<th>status</th>
	<th>time</th>
	<th>reply status</th>
	</tr>
	<?$odd=0;
	foreach($mc_arr as $row){
		$odd=$odd?0:1;
		$timestamp=gmmktime(substr($row->time,11,2), 	//hour
							substr($row->time,14,2), 	//minute
							substr($row->time,17,2), 	//second
							substr($row->time,5,2), 	//month
							substr($row->time,8,2), 	//day
							substr($row->time,0,4));	//year
		if ($timestamp <=0 ) $time="";
		else {
			if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
			else $time=date('Y-m-d H:i',$timestamp);
		}

	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left">
		<a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->from_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');">
		<?echo htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1", $row->sip_from));?>
		</a></td>
	<td align="center"><?echo nbsp_if_empty($row->status);?></td>
	<td align="center"><?echo nbsp_if_empty($time);?></td>
	<td align="center"><?echo nbsp_if_empty($row->sip_status);?></td>
	</tr>
	<?}?>
	</table>
<br><div align="center"><a href="<?$sess->purl("missed_calls.php?kvrk=".uniqID("")."&delete_calls=1&page_loaded_timestamp=".time());?>"><img src="<?echo $config->img_src_path;?>butons/b_delete_calls.gif" width="165" height="16" border="0"></a></div>
<?}?>

<? if ($num_rows){?>
<div class="swNumOfFoundRecords">Missed calls <?echo ($sess_mc_act_row+1)." - ".((($sess_mc_act_row+$config->num_of_showed_items)<$num_rows)?($sess_mc_act_row+$config->num_of_showed_items):$num_rows);?> from <?echo $num_rows;?></div>
<?}else{?>
<div class="swNumOfFoundRecords">No missed calls</div>
<?}?><br>

<div class="swSearchLinks">&nbsp;
<?
	$url="missed_calls.php?kvrk=".uniqid("")."&act_row=";
	print_search_links($sess_mc_act_row, $num_rows, $config->num_of_showed_items, $url);
?>
</div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
