{* Smarty *}
{* $Id: u_message_store.tpl,v 1.2 2004/08/09 23:04:57 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.instant_messages_store}:</h2>

{foreach from=$im_res item='row' name='inst_msgs'}
	<div class="swInstantMessage">
		<div class="swIMtitle">
			<a href="{$row.url_reply}" class="msgtitle">{$lang_str.l_reply}</a>
			<a href="{$row.url_dele}" class="msgtitle">{$lang_str.l_delete}</a>
			<span class="swIMtime">{$row.time}</span>
			<span class="swIMSrcAdr">{$row.src_addr}</span>
		</div>
		<div class="swIMbody">{$row.body|empty2nbsp}</div>
	</div>
{foreachelse}
	<div class="swNumOfFoundRecords">{$lang_str.no_stored_instant_messages}</div>
	<br>
{/foreach}


{if $show_voice_silo}

<h2 class="swTitle">{$lang_str.voicemail_messages_store}:</h2>

{foreach from=$vm_res item='row' name='voice_msgs'}
	{if $smarty.foreach.voice_msgs.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>{$lang_str.th_calling_subscriber}</td>
	<th>{$lang_str.th_subject}</td>
	<th>{$lang_str.th_time}</td>
	<th>&nbsp;</td>
	</tr>
	{/if}

	<tr valign="top">
	<td align="left"><a href="{$row.url_reply}">{$row.src_addr}</a></td>
	<td align="left"><a href="{$row.url_get}">{$row.subject|empty2nbsp}</a></td>
	<td align="center">{$row.time|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_dele}" class="msgtitle">{$lang_str.l_delete}</a></td>
	</tr>

	{if $smarty.foreach.voice_msgs.last}
	</table>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_stored_voicemail_messages}</div>
{/foreach}

{/if}

<br>
{include file='_tail.tpl'}

