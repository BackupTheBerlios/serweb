<?
/*
 * $Id: missed_calls.php,v 1.24 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

if (!$sess->is_registered('sess_mc_act_row')) $sess->register('sess_mc_act_row');
if (!isset($sess_mc_act_row)) $sess_mc_act_row=0;

if (isset($HTTP_GET_VARS['act_row'])) $sess_mc_act_row=$HTTP_GET_VARS['act_row'];

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (isset($_GET['delete_calls'])){

		if (!$data->del_missed_calls($auth->auth["uname"], $config->domain, $page_loaded_timestamp, $errors)) break;
		$sess_mc_act_row=0;

        Header("Location: ".$sess->url("missed_calls.php?kvrk=".uniqID("")."&message=".RawURLEncode("calls deleted succesfully")));
		page_close();
		exit;
	}

	$mc_arr=array();

    $data->set_timezone($errors);
	
	$data->set_act_row($sess_mc_act_row);
	
	if (false === $mc_arr = $data->get_missed_calls($auth->auth["uname"], $config->domain, $errors)) break;


}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<?if (is_array($mc_arr) and count($mc_arr)){?>

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

	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left">
		<a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->from_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');">
		<?echo htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1", $row->sip_from));?>
		</a></td>
	<td align="center"><?echo nbsp_if_empty($row->status);?></td>
	<td align="center"><?echo nbsp_if_empty($row->time);?></td>
	<td align="left"><?echo nbsp_if_empty($row->sip_status);?></td>
	</tr>
	<?}?>
	</table>
<br><div align="center"><a href="<?$sess->purl("missed_calls.php?kvrk=".uniqID("")."&delete_calls=1&page_loaded_timestamp=".time());?>"><img src="<?echo $config->img_src_path;?>butons/b_delete_calls.gif" width="165" height="16" border="0"></a></div>
<?}?>

<? if ($data->get_num_rows()){?>
<div class="swNumOfFoundRecords">Missed calls <?echo $data->get_res_from()." - ".$data->get_res_to();?> from <?echo $data->get_num_rows();?></div>
<?}else{?>
<div class="swNumOfFoundRecords">No missed calls</div>
<?}?><br>

<div class="swSearchLinks">&nbsp;
<?
	$url="missed_calls.php?kvrk=".uniqid("")."&act_row=";
	print_search_links($data->get_act_row(), $data->get_num_rows(), $data->get_showed_rows(), $url);
?>
</div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
