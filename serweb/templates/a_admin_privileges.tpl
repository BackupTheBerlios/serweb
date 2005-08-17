{* Smarty *}
{* $Id: a_admin_privileges.tpl,v 1.4 2005/08/17 12:59:55 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.admin_privileges_of} {$uname}</h2>

<div class="swForm">
{$form.start}
	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>{$lang_str.admin_competence}</legend>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="pr_chk_is_admin">{$lang_str.ff_is_admin}</label></td>
	<td>{$form.pr_chk_is_admin}</td>
	</tr>
	<tr>
	<td><label for="pr_chk_change_privileges">{$lang_str.ff_change_privileges}</label></td>
	<td>{$form.pr_chk_change_privileges}</td>
	</tr>
	</table>
	</fieldset>
	</div>

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

	<br />
	<div align="center">{$form.okey}</div>

{$form.finish}
</div>

<div class="swBackToMainPage"><a href="{url url='list_of_admins.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>
{include file='_tail.tpl'}

