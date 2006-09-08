{* Smarty *}
{* $Id: a_global_attributes.tpl,v 1.4 2006/09/08 12:27:35 kozlik Exp $ *}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

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

