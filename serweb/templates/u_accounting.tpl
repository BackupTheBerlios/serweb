{* Smarty *}
{* $Id: u_accounting.tpl,v 1.3 2004/08/25 10:19:48 kozlik Exp $ *}

{if $come_from_admin_interface}
<div class="swNameOfUser">{$lang_str.user}: {$user_auth->uname}</div>
{/if}

{include file='_head.tpl'}

{foreach from=$acc_res item='row' name='accounting'}
	{if $smarty.foreach.accounting.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_destination}</th>
	<th>{$lang_str.th_time}</th>
	<th>{$lang_str.th_length_of_call}</th>
	<th>{$lang_str.th_hangup}</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">
	<a href="{$row.url_ctd}">{$row.sip_to|empty2nbsp}</a></td>
	<td align="left">{$row.time|empty2nbsp}</td>
	<td align="left">{$row.length|empty2nbsp}</td>
	<td align="center">{$row.hangup}</td>
	</tr>
	{if $smarty.foreach.accounting.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.calls_count} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_calls}</div>
{/foreach}

{if $come_from_admin_interface}
	<br>
	<div class="swBackToMainPage"><a href="{$url_admin}">{$lang_str.l_back_to_main}</a></div>
{/if}

<br>
{include file='_tail.tpl'}
