{* Smarty *}
{* $Id: a_global_attributes.tpl,v 1.3 2006/05/23 09:13:38 kozlik Exp $ *}

{include file='_head.tpl'}

{if $attributes}
	<div class="swForm">
	{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
{/if}

{include file="_attr_form.tpl" attributes=$attributes form=$form}

{if $attributes}
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>{$form.okey}</td>
	</tr>
	</table>
	{$form.finish}
	</div>
{else}
	<div class="swNumOfFoundRecords">{$lang_str.no_attributes_defined}</div>
{/if}

<br>
{include file='_tail.tpl'}

