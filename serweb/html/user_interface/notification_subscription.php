<?
/*
 * $Id: notification_subscription.php,v 1.11 2004/04/14 20:51:31 kozlik Exp $
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
	if (!$data = CData_Layer::create($errors)) break;

	if (isset($_GET['uri']) and isset($_GET['desc'])){
		if (!$data->subscribe_event($auth->auth["uname"], $config->domain, $_GET['uri'], $_GET['desc'], $errors)) break;

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['dele_id'])){
		if (!$data->unsubscribe_event($auth->auth["uname"], $config->domain, $_GET['dele_id'], $errors)) break;

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

}while (false);

do{
	$events=array();
	if ($data){
		if (false === $events = $data->get_events($auth->auth["uname"], $config->domain, $errors)) break;
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">your subscribed events:</h2>

<?if (is_array($events) and count($events)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>description</th>
	<th width="90">&nbsp;</th>
	</tr>
	<?foreach ($events as $row){
	remove_from_events($row->uri)
	?>
	<tr valign="top">
	<td align="left"><?echo $row->description;?></td>
	<td align="center"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqid("")."&dele_id=".$row->id);?>">unsubscribe</a></td>
	</tr>
	<?}//while?>
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
