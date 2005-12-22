{* Smarty *}
{* $Id: a_global_attributes.tpl,v 1.1 2005/12/22 12:56:06 kozlik Exp $ *}

{include file='_head.tpl'}

{foreach from=$attributes item='row' name='attributes'}
	{if $smarty.foreach.attributes.first}
	<div class="swForm">
	{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	{/if}

 	{if $row.att_type == 'radio'}
 		<tr><td colspan=2>&nbsp;</td></tr>
 		{foreach from=$row.att_spec item='r'}
 			{assign var='f_element' value="`$row.att_name`_`$r.value`"}
 			<tr>
 			<td><label for="{$row.att_name}_{$r.value}">{$r.label}:</label></td>
 			<td>{$form.$f_element}</td>
 			</tr>
 		{/foreach}
 		<tr><td colspan=2>&nbsp;</td></tr>
 	{else}
 		{assign var='f_element' value=$row.att_name}
 		<tr>
 		<td><label for="{$row.att_name}">{$row.att_desc}:</label></td>
 		<td>{$form.$f_element}</td>
 		</tr>
 	{/if}

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

