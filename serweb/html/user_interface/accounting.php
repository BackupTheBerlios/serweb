<?
/*
 * $Id: accounting.php,v 1.22 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";


put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$res=null;
do{
	if (!$data = CData_Layer::create($errors)) break;
	
	$data->set_timezone($errors);

	if (false === $res=$data->select_calls_from_acc($auth->auth["uname"], $config->domain, $errors)) break;
	
}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<?if (is_array($res) and count($res)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>destination</th>
	<th>call id</th>
	<th>time</th>
	<th>length of call</th>
	<th>hang up</th>
	</tr>
	<?$odd=0;
	foreach($res as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left">
	<a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->to_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->domain); ?>');">
	<?echo htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1",$row->sip_to));?></a></td>
	<td align="left"><?echo nbsp_if_empty($row->sip_callid);?></td>
	<td align="left"><?echo nbsp_if_empty($row->time);?></td>
	<td align="left"><?echo nbsp_if_empty($row->length);?></td>
	<td align="center"><?echo nbsp_if_empty($row->hangup);?></td>
	</tr>
	<?}//while?>
	</table>

<?}else{?>
<div class="swNumOfFoundRecords">No calls</div>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
