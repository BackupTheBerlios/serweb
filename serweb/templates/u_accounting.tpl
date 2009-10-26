{* Smarty *}
{* $Id: u_accounting.tpl,v 1.12 2009/10/26 11:12:42 kozlik Exp $ *}


{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

{if $come_from_admin_interface}
<h2 class="swTitle">{$lang_str.user}: {$user_auth.uname|escape}@{$user_auth.domain|escape}</h2>
{/if}

{foreach from=$acc item='row' name='accounting'}
	{if $smarty.foreach.accounting.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_destination}</th>
	<th>{$lang_str.th_status}</th>
	<th>{$lang_str.th_time}</th>
	<th>{$lang_str.th_length_of_call}</th>
	<th>{$lang_str.th_hangup}</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">
		{if $config->enable_ctd}<a href="{$row.url_ctd|escape}">{/if}
			{if $row.name}<span class="swPopupLink" {popup text=$row.sip_to|escape|escape|empty2nbsp}>{$row.name|escape|empty2nbsp}</span>
			{else} {$row.sip_to|escape|empty2nbsp}
			{/if}
		{if $config->enable_ctd}</a>{/if}
	</td>
	<td align="center">{$row.status|empty2nbsp|user_status}</td>
	<td align="left">{$row.timestamp|my_date_format:$lang_set.date_time_format|empty2nbsp}</td>
	<td align="left">{$row.length|empty2nbsp}</td>
	<td align="center">{$row.hangup}</td>
	</tr>
	{if $smarty.foreach.accounting.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.calls_count} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>

	<div id="orphanlinks">
	<div class="swLinkToTabExtension"><a href="{$url_delete|escape}">{$lang_str.b_delete_calls}</a></div>
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_calls}</div>
{/foreach}


{if $come_from_admin_interface}
	<br />
	<div class="swBackToMainPage"><a href="{$url_admin|escape}">{$lang_str.l_back_to_main}</a></div>
{/if}

<br />
{include file='_tail.tpl'}
