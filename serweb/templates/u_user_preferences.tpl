{* Smarty *}
{* $Id: u_user_preferences.tpl,v 1.3 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

{foreach from=$attributes item='row' name='attributes'}
	{if $smarty.foreach.attributes.first}
	<div class="swForm">
	{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	{/if}

		<tr>
		<td align="right" class="f12b"><label for="{$row.att_name}">{$row.att_desc}:</label></td>
		<td>{assign var='att_name' value=$row.att_name}{$form.$att_name}</td>
		</tr>

	{if $smarty.foreach.attributes.last}
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
	{$form.finish}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_attributes_defined}</div>
{/foreach}

<br>
{include file='_tail.tpl'}

