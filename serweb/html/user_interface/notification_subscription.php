<?
/*
 * $Id: notification_subscription.php,v 1.10 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

function remove_from_events($uri){
	global $config;

	if (is_array($config->sub_not))
		foreach ($config->sub_not as $key => $row) if ($row->uri==$uri) unset ($config->sub_not[$key]);
}

do{
	if (!$db = connect_to_db($errors)) break;

	if (isset($_GET['uri']) and isset($_GET['desc'])){
		$q="insert into ".$config->table_event." (uri, description, username, domain) ".
			"values ('".$_GET['uri']."', '".$_GET['desc']."', '".$auth->auth["uname"]."' , '".$config->realm."')";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['dele_id'])){
		$q="delete from ".$config->table_event.
			" where username='".$auth->auth["uname"]."' and domain='".$config->realm."'  and id=".$_GET['dele_id'];
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

}while (false);

do{
	if ($db){
		$q="select id, uri, description ".
			"from ".$config->table_event." ".
			"where username='".$auth->auth["uname"]."' and domain='".$config->realm."'";

		$ev_res=$db->query($q);
		if (DB::isError($ev_res)) {log_errors($ev_res, $errors); break;}
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=get_user_name($db, $errors);
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">your subscribed events:</h2>

<?if (!DB::isError($ev_res) and $ev_res->numRows()){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>description</th>
	<th width="90">&nbsp;</th>
	</tr>
	<?while ($row=$ev_res->fetchRow(DB_FETCHMODE_OBJECT)){
	remove_from_events($row->uri)
	?>
	<tr valign="top">
	<td align="left"><?echo $row->description;?></td>
	<td align="center"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqid("")."&dele_id=".$row->id);?>">unsubscribe</a></td>
	</tr>
	<?}//while
	$ev_res->free();?>
	</table>

<?}else{?>
<div class="swNumOfFoundRecords">No subscribed events</div>
<?}?>

<h2 class="swTitle">other events:</h2>

<?
if (is_array($config->sub_not) and count($config->sub_not)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>description</th>
	<th width="90">&nbsp;</th>
	</tr>
<?	foreach($config->sub_not as $row){?>
	<tr valign="top">
	<td align="left"><?echo $row->desc;?></td>
	<td align="center"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqid("")."&desc=".RawURLEncode($row->desc)."&uri=".RawURLEncode($row->uri));?>">subscribe</a></td>
	</tr>
<? }//for each?>
	</table>
<?}else{?>
<div class="swNumOfFoundRecords">No other events</div>
<?}//end if?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
