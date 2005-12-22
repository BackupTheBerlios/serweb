{* Smarty *}
{* $Id: _attr_form.tpl,v 1.1 2005/12/22 13:42:45 kozlik Exp $ *}

{foreach from=$attributes item='row' name='attributes'}

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
{/foreach}
