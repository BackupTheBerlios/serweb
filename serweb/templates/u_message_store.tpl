{* Smarty *}
{* $Id: u_message_store.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">Instant messages store:</h2>

{foreach from=$im_res item='row' name='inst_msgs'}
	<div class="swInstantMessage">
		<div class="swIMtitle">
			<a href="{$row.url_reply}" class="msgtitle">reply</a>
			<a href="{$row.url_dele}" class="msgtitle">delete</a>
			<span class="swIMtime">{$row.time}</span>
			<span class="swIMSrcAdr">{$row.src_addr}</span>
		</div>
		<div class="swIMbody">{$row.body|empty2nbsp}</div>
	</div>
{foreachelse}
	<div class="swNumOfFoundRecords">No stored instant messages</div>
	<br>
{/foreach}


{if $show_voice_silo}

<h2 class="swTitle">Voicemail messages store:</h2>

{foreach from=$vm_res item='row' name='voice_msgs'}
	{if $smarty.foreach.voice_msgs.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>calling subscriber</td>
	<th>subject</td>
	<th>time</td>
	<th>&nbsp;</td>
	</tr>
	{/if}

	<tr valign="top">
	<td align="left"><a href="{$row.url_reply}">{$row.src_addr}</a></td>
	<td align="left"><a href="{$row.url_get}">{$row.subject|empty2nbsp}</a></td>
	<td align="center">{$row.time|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_dele}" class="msgtitle">delete</a></td>
	</tr>

	{if $smarty.foreach.voice_msgs.last}
	</table>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No stored voicemail messages</div>
{/foreach}

{/if}

<br>
{include file='_tail.tpl'}


{*

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

*}