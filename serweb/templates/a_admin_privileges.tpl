{* Smarty *}
{* $Id: a_admin_privileges.tpl,v 1.2 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.admin_privileges_of} {$uname}</h2>

<div class="swForm">
{$form.start}
	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>{$lang_str.admin_competence}</legend>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="chk_is_admin">{$lang_str.ff_is_admin}</label></td>
	<td>{$form.chk_is_admin}</td>
	</tr>
	<tr>
	<td><label for="chk_change_privileges">{$lang_str.ff_change_privileges}</label></td>
	<td>{$form.chk_change_privileges}</td>
	</tr>
	</table>
	</fieldset>
	</div>

	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>{$lang_str.acl_control}</legend>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
{foreach from=$grp_values item='row' name='grp_values'}
	{* concatenate 'chk_' and $row in order to get name of form element *}
	{assign var='f_element' value="chk_$row"}
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

