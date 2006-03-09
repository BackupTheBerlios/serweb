{* Smarty *}
{* $Id: a_acl.tpl,v 1.4 2006/03/09 09:17:53 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.access_control_list_of_user}: {$uname|escape}</h2>

{foreach from=$acl_control item='row' name='acl_control'}
	{if $smarty.foreach.acl_control.first}
	<div class="swForm">
	{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	{/if}

	{* concatenate 'chk_' and $row in order to get name of form element *}
	{assign var='f_element' value="acl_chk_$row"}
	
	<tr>
	<td><label for="{$f_element}">{$row}</label></td>
	<td>{$form.$f_element}</td>
	</tr>

	{if $smarty.foreach.acl_control.last}
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
	{$form.finish}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.have_not_privileges_to_acl}</div>
{/foreach}

<div class="swBackToMainPage"><a href="{url url='users.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

{include file='_tail.tpl'}
