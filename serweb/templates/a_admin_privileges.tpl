{* Smarty *}
{* $Id: a_admin_privileges.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">Admin privileges of {$uname}</h2>

<div class="swForm">
{$form.start}
	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>admin competence</legend>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="chk_is_admin">is admin</label></td>
	<td>{$form.chk_is_admin}</td>
	</tr>
	<tr>
	<td><label for="chk_change_privileges">changes privileges of admins</label></td>
	<td>{$form.chk_change_privileges}</td>
	</tr>
	</table>
	</fieldset>
	</div>

	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>ACL control</legend>
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

<div class="swBackToMainPage"><a href="{url url='list_of_admins.php' uniq=1}">back to main page</a></div>

<br>
{include file='_tail.tpl'}

