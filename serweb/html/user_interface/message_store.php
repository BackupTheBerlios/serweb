<?
/*
 * $Id: message_store.php,v 1.2 2003/04/26 17:57:21 jiri Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

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
	$db = connect_to_db();
	if (!$db){ $errors[]="can't connect to sql server"; break;}
	
	if (isset($dele_im)){
		$q="delete from ".$config->table_message_silo." where mid=$dele_im and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__;}

		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($dele_vm)){
		$q="select file from ".$config->table_voice_silo." where mid=".$dele_vm." and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		if (!MySQL_num_rows($res)) {$errors[]="Message not found or you haven't access to message"; break;}
		$row=MySQL_Fetch_Object($res);		

		@unlink($config->voice_silo_dir.$row->file);
		
		$q="delete from ".$config->table_voice_silo." where mid=$dele_vm";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__;}

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
		$im_res=mySQL_query($q);
		if (!$im_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	
		while ($row=MySQL_Fetch_Object($im_res)){
			$im_arr[]=new Cinst_mess($row->mid, $row->src_addr, 
				$row->inc_time, $row->body);
		}

		if ($config->show_voice_silo) {	
			$q="select mid, src_addr, inc_time, subject, file from ".
				$config->table_voice_silo." where r_uri like 'sip:".
				$auth->auth["uname"]."@".$config->default_domain."%'";
			$vm_res=mySQL_query($q);
			if (!$vm_res) {
				$errors[]="error in SQL query, line: ".__LINE__; 
				break;
			}
			while ($row=MySQL_Fetch_Object($vm_res)){
				$vm_arr[]=new Cvoice_mess($row->mid, $row->src_addr, 
					$row->inc_time, $row->subject, $row->file);
			}
		}
		set_timezone($errors);
	}
						 
}while (false);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>

	<style type="text/css">
	<!--
	.msgtitle{
		color: black;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 13px;
		font-style: normal;
		font-weight: bold ;
	}
	.msgtitle:visited{
		color: black;
	}
	.msgtitle:active{
		color: black;
	}
	.msgtitle:hover{
		color: black;
	}
	-->
	</style>
</head>
<?
	print_html_body_begin(7, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>


<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="title" width="502">Instant messages store:</td></tr>
</table><br>

<?if (isset($im_arr)){?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
<?foreach($im_arr as $row){
	if (date('Y-m-d',$row->time)==date('Y-m-d')) $time="today ".date('H:i',$row->time);
	else $time=date('Y-m-d H:i',$row->time)
?>
<tr><td>
	<table border="0" cellspacing="0" cellpadding="2" bgcolor="#C1D773">
	<tr><td>
		<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
		<tr bgcolor="#B1C9DC" valign="top">
		<td width="245" class="msgtitle"><?echo $row->src_addr;?></td>
		<td align="right" width="135" class="msgtitle"><?echo $time;?></td>
		<td align="center" width="60" class="msgtitle"><a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));?>" class="msgtitle">reply</a></td>
		<td align="center" width="60" class="msgtitle"><a href="<?$sess->purl("message_store.php?kvrk=".uniqid("")."&dele_im=".rawURLEncode($row->mid));?>" class="msgtitle">delete</a></td>
		</tr>

		<tr>
		<td colspan="4" width="500"><?echo $row->body;?></td>
		</tr>		
		</table>
	</td></tr>
	</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
<?}?>
</table>

<?}else{?>
<div align="center">No stored instant messages</div>
<br>
<?}?>

<?if ($default->show_voice_silo) {?>

<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="title" width="502">Voicemail messages store:</td></tr>
</table><br>

<?if (isset($vm_arr)){?>
<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="139">calling subscriber</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="160">subject</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="135">time</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="60">&nbsp;</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?foreach($vm_arr as $row){
		if (date('Y-m-d',$row->time)==date('Y-m-d')) $time="today ".date('H:i',$row->time);
		else $time=date('Y-m-d H:i',$row->time)
	?>
	<tr valign="top">
	<td align="left" class="f12" width="139"><a href="<?$sess->purl("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));?>"><?echo htmlspecialchars($row->src_addr);?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>

	<td align="left" class="f12" width="160"><a href="<?$sess->purl("ms_get_v_msg.php?kvrk=".uniqid("")."&mid=".rawURLEncode($row->mid));?>"><?echo $row->subject;?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="135"><?echo $time;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="60"><a href="<?$sess->purl("message_store.php?kvrk=".uniqid("")."&dele_vm=".rawURLEncode($row->mid));?>" class="msgtitle">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}else{?>
<div align="center">No stored voicemail messages</div>
<?}?>
<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
