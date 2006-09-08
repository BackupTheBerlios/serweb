{* Smarty *}
{* $Id: _attr_form.tpl,v 1.5 2006/09/08 12:27:35 kozlik Exp $ *}

{foreach from=$attributes item='row' name='attributes'} 
	{if !$row.edit}
 		<tr>
 		{if $row.att_ldesc}
 	 		<td><label for="{$row.att_name}"><a href="javascript:void(0);" class="swPopupLink" {popup text=$row.att_ldesc|escape|escape|empty2nbsp}>{$row.att_desc|escape}</a>:</label></td>
		{else}
	 		<td><label for="{$row.att_name}">{$row.att_desc|escape}:</label></td>
 		{/if}
 		{if !$row.att_value_f}
			<td><i>&lt; empty &gt;</i></td>
		{else}
			<td>{$row.att_value_f}</td>
		{/if}
 		</tr>
 	{elseif $row.att_type == 'radio'}
 		<tr><td colspan=2>&nbsp;</td></tr>
 		{foreach from=$row.att_spec item='r'}
 			{assign var='f_element' value="`$row.att_name`_`$r.value`"}
 			<tr>
 			<td><label for="{$row.att_name}_{$r.value}">{$r.label|escape}:</label></td>
 			<td>{$form.$f_element}</td>
 			</tr>
 		{/foreach}
 		<tr><td colspan=2>&nbsp;</td></tr>
 	{else}
 		{assign var='f_element' value=$row.att_name}
 		<tr>
 		{if $row.att_ldesc}
 	 		<td><label for="{$row.att_name}"><a href="javascript:void(0);" class="swPopupLink" {popup text=$row.att_ldesc|escape|escape|empty2nbsp}>{$row.att_desc|escape}</a>:</label></td>
		{else}
	 		<td><label for="{$row.att_name}">{$row.att_desc|escape}:</label></td>
 		{/if}
		<td>{$form.$f_element}</td>
 		</tr>
 	{/if}
{/foreach}
