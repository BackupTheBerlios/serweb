{* Smarty *}
{* $Id: u_caller_screening.tpl,v 1.1 2004/08/09 23:04:57 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="uri_re">{$lang_str.ff_screening_caller_uri}:</label></td>
	<td>{$form.uri_re}</td>
	</tr>
	<tr>
	<td><label for="action_key">{$lang_str.ff_action}:</label></td>
	<td>{$form.action_key}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$caller_uris item='row' name='caller_uris'}
	{if $smarty.foreach.caller_uris.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_caller_uri}</th>
	<th>{$lang_str.th_action}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.uri_re|empty2nbsp}</td>
	<td align="left">{$row.label|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.caller_uris.last}
	</table>
	{/if}
{foreachelse}
	<div class="swNumOfFoundRecords">{$lang_str.no_caller_screenings_defined}</div>
{/foreach}

<br>
{include file='_tail.tpl'}
