<?
/*
 * $Id: message_store.php,v 1.6 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

class Cinst_mess{
	var $mid, $src_addr, $time, $body;
	function Cinst_mess($mid, $src_addr, $time, $body){
		$this->mid=$mid;
		$this->src_addr=$src_addr;
		$this->time=$time;
		$this->body=$body;
	}
}

class Cvoice_mess{
	var $mid, $src_addr, $time, $subject, $file;
	function Cvoice_mess($mid, $src_addr, $time, $subject, $file){
		$this->mid=$mid;
		$this->src_addr=$src_addr;
		$this->time=$time;
		$this->subject=$subject;
		$this->file=$file;
	}
}

do{
	if (!$db = connect_to_db($errors)) break;

	if (isset($dele_im)){
		$q="delete from ".$config->table_message_silo." where mid=$dele_im and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($dele_vm)){
		$q="select file from ".$config->table_voice_silo." where mid=".$dele_vm." and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}
		if (!$res->numRows()) {$errors[]="Message not found or you haven't access to message"; break;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		@$unl=unlink($config->voice_silo_dir.$row->file);
		if (!$unl and file_exists($config->voice_silo_dir.$row->file)) {$errors[]="Error when deleting message"; break;}

		$q="delete from ".$config->table_voice_silo." where mid=$dele_vm";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while(false);

do{
	if ($db){
		$q="select mid, src_addr, inc_time, body from ".
			$config->table_message_silo.
			" where r_uri like 'sip:".$auth->auth["uname"].
			"@".$config->default_domain."%'";
		$im_res=$db->query($q);
		if (DB::isError($im_res)) {log_errors($im_res, $errors); break;}

		while ($row=$im_res->fetchRow(DB_FETCHMODE_OBJECT)){
			$im_arr[]=new Cinst_mess($row->mid, $row->src_addr,
				$row->inc_time, $row->body);
		}
		$im_res->free();

		if ($config->show_voice_silo) {
			$q="select mid, src_addr, inc_time, subject, file from ".
				$config->table_voice_silo." where r_uri like 'sip:".
				$auth->auth["uname"]."@".$config->default_domain."%'";
			$vm_res=$db->query($q);
			if (DB::isError($vm_res)) {log_errors($vm_res, $errors); break;}

			while ($row=$vm_res->fetchRow(DB_FETCHMODE_OBJECT)){
				$vm_arr[]=new Cvoice_mess($row->mid, $row->src_addr,
					$row->inc_time, $row->subject, $row->file);
			}
			$vm_res->free();
		}
                set_timezone($db, $errors);
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=get_user_name($db, $errors);
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">Instant messages store:</h2>

<?if (isset($im_arr)){?>
<?foreach($im_arr as $row){
	if (date('Y-m-d',$row->time)==date('Y-m-d')) $time="today ".date('H:i',$row->time);
	else $time=date('Y-m-d H:i',$row->time)
?>
	<div class="swInstantMessage">
		<div class="swIMtitle">
			<a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));?>" class="msgtitle">reply</a>
			<a href="<?$sess->purl("message_store.php?kvrk=".uniqid("")."&dele_im=".rawURLEncode($row->mid));?>" class="msgtitle">delete</a>
			<span class="swIMtime"><?echo $time;?></span>
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

<?if (isset($vm_arr)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>calling subscriber</td>
	<th>subject</td>
	<th>time</td>
	<th>&nbsp;</td>
	</tr>
	<?foreach($vm_arr as $row){
		if (date('Y-m-d',$row->time)==date('Y-m-d')) $time="today ".date('H:i',$row->time);
		else $time=date('Y-m-d H:i',$row->time)
	?>
	<tr valign="top">
	<td align="left"><a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));?>"><?echo htmlspecialchars($row->src_addr);?></a></td>
	<td align="left"><a href="<?$sess->purl("ms_get_v_msg.php?kvrk=".uniqid("")."&mid=".rawURLEncode($row->mid));?>"><?echo nbsp_if_empty($row->subject);?></a></td>
	<td align="center"><?echo nbsp_if_empty($time);?></td>
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
