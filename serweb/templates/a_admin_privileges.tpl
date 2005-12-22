{* Smarty *}
{* $Id: a_admin_privileges.tpl,v 1.8 2005/12/22 13:39:33 kozlik Exp $ *}

{include file='_head.tpl'}

{if $allow_change_privileges}
	<h2 class="swTitle">{$lang_str.admin_privileges_of} {$uname}</h2>
	
	<div class="swForm">
	{$form.start}
		<div class="swFieldset">
		<fieldset class="swWidthAsTitle">
		<legend>{$lang_str.admin_competence}</legend>
		<table border="0" cellspacing="0" cellpadding="0" align="center">
	{if $en_priv.is_admin}
		<tr>
		<td><label for="pr_chk_is_admin">{$lang_str.ff_is_admin}</label></td>
		<td>{$form.pr_chk_is_admin}</td>
		</tr>
	{/if}
	{if $en_priv.hostmaster}
		<tr>
		<td><label for="pr_chk_hostmaster">{$lang_str.ff_is_hostmaster}</label></td>
		<td>{$form.pr_chk_hostmaster}</td>
		</tr>
	{/if}
		</table>
		</fieldset>
		</div>
	
	{if $en_priv.acl_control}
		<div class="swFieldset">
		<fieldset class="swWidthAsTitle">
		<legend>{$lang_str.acl_control}</legend>
		<table border="0" cellspacing="0" cellpadding="0" align="center">
	{foreach from=$grp item='row' name='grp_values'}
		{* concatenate 'chk_' and $row in order to get name of form element *}
		{assign var='f_element' value="pr_chk_$row"}
		<tr>
		<td><label for="{$f_element}">{$row}</label></td>
		<td>{$form.$f_element}</td>
		</tr>
	{/foreach}
		</table>
		</fieldset>
		</div>
	{/if}
	
		<br />
		<div align="center">{$form.okey}</div>
	
	{$form.finish}
	</div>
{/if}

<div class="swBackToMainPage"><a href="{url url='list_of_admins.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>
{include file='_tail.tpl'}

