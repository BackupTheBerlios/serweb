{* Smarty *}
{* $Id: u_missed_calls.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

{foreach from=$mc_res item='row' name='missed_calls'}
	{if $smarty.foreach.missed_calls.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>calling subscriber</th>
	<th>status</th>
	<th>time</th>
	<th>reply status</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left"><a href="{$row.url_ctd}">{$row.sip_from}</a></td>
	<td align="center">{$row.status|empty2nbsp}</td>
	<td align="center">{$row.time|empty2nbsp}</td>
	<td align="left">{$row.sip_status|empty2nbsp}</td>
	</tr>
	
	{if $smarty.foreach.missed_calls.last}
	</table>

	<div class="swNumOfFoundRecords">Missed calls {$pager.from} - {$pager.to} from {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No missed calls</div>
{/foreach}

<br><div align="center"><a href="{$url_dele}"><img src="{$config->img_src_path}butons/b_delete_calls.gif" width="165" height="16" border="0"></a></div>

<br>
{include file='_tail.tpl'}
