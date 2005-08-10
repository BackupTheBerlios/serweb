{* Smarty *}
{* $Id: u_missed_calls.tpl,v 1.4 2005/08/10 13:56:25 kozlik Exp $ *}

{include file='_head.tpl'}

{foreach from=$missed_calls item='row' name='missed_calls'}
	{if $smarty.foreach.missed_calls.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_calling_subscriber}</th>
	<th>{$lang_str.th_status}</th>
	<th>{$lang_str.th_time}</th>
	<th>{$lang_str.th_reply_status}</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left"><a href="{$row.url_ctd}">
		{if $row.name}{$row.name|empty2nbsp}
		{else} {$row.sip_to|empty2nbsp}
		{/if}
	</a></td>
	<td align="center">{$row.status|empty2nbsp}</td>
	<td align="center">{$row.timestamp|my_date_format:$lang_set.date_time_format|empty2nbsp}</td>
	<td align="left">{$row.sip_status|empty2nbsp}</td>
	</tr>
	
	{if $smarty.foreach.missed_calls.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.missed_calls} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_missed_calls}</div>
{/foreach}

<br><div align="center"><a href="{$url_delete}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_delete_calls.gif" width="165" height="16" border="0" alt="{$lang_str.b_delete_calls}"></a></div>

<br>
{include file='_tail.tpl'}
