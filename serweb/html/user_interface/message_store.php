<?
/*
 * $Id: message_store.php,v 1.7 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (isset($_GET['dele_im'])){
		if (!$data->del_IM($auth->auth["uname"], $config->domain, $_GET['dele_im'], $errors)) break;
		
		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['dele_vm'])){
		if (!$data->del_VM($auth->auth["uname"], $config->domain, $_GET['dele_vm'], $errors)) break;

		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while(false);

do{
	$im_arr=array();
	$vm_arr=array();

	if ($data){
		$data->set_timezone($errors);

		if(false === $im_arr = $data->get_IMs($auth->auth["uname"], $config->default_domain, $errors)) break;

		if ($config->show_voice_silo) {
			if(false === $vm_arr = $data->get_VMs($auth->auth["uname"], $config->default_domain, $errors)) break;
		}
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">Instant messages store:</h2>

<?if (is_array($im_arr) and count($im_arr)){?>
<?foreach($im_arr as $row){?>
	<div class="swInstantMessage">
		<div class="swIMtitle">
			<a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));?>" class="msgtitle">reply</a>
			<a href="<?$sess->purl("message_store.php?kvrk=".uniqid("")."&dele_im=".rawURLEncode($row->mid));?>" class="msgtitle">delete</a>
			<span class="swIMtime"><?echo $row->time;?></span>
			<span class="swIMSrcAdr"><?echo $row->src_addr;?></span>
		</div>
		<div class="swIMbody"><?echo nbsp_if_empty($row->body);?></div>
	</div>
<?}?>

<?}else{?>
<div class="swNumOfFoundRecords">No stored instant messages</div>
<br>
<?}?>

<?if ($config->show_voice_silo) {?>

<h2 class="swTitle">Voicemail messages store:</h2>

<?if (is_array($vm_arr) and count($vm_arr)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>calling subscriber</td>
	<th>subject</td>
	<th>time</td>
	<th>&nbsp;</td>
	</tr>
	<?foreach($vm_arr as $row){	?>
	<tr valign="top">
	<td align="left"><a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));?>"><?echo htmlspecialchars($row->src_addr);?></a></td>
	<td align="left"><a href="<?$sess->purl("ms_get_v_msg.php?kvrk=".uniqid("")."&mid=".rawURLEncode($row->mid));?>"><?echo nbsp_if_empty($row->subject);?></a></td>
	<td align="center"><?echo nbsp_if_empty($row->time);?></td>
	<td align="center"><a href="<?$sess->purl("message_store.php?kvrk=".uniqid("")."&dele_vm=".rawURLEncode($row->mid));?>" class="msgtitle">delete</a></td>
	</tr>
	<?}?>
	</table>

<?}else{?>
<div class="swNumOfFoundRecords">No stored voicemail messages</div>
<?}?>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
