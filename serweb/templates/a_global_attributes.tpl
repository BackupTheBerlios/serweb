{* Smarty *}
{* $Id: a_global_attributes.tpl,v 1.5 2009/12/17 17:12:19 kozlik Exp $ *}

{include file='_head.tpl'}
{include file='_popup_init.tpl'}

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

