{* Smarty *}
{* $Id: u_find_user.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">Find user</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">first name:</label></td>
	<td>{$form.fname}</td>
	</tr>
	<tr>
	<td><label for="lname">last name:</label></td>
	<td>{$form.lname}</td>
	</tr>
	<tr>
	<td><label for="uname">user name:</label></td>
	<td>{$form.uname}</td>
	</tr>
	<tr>
	<td><label for="onlineonly">show on-line users only:</label></td>
	<td>{$form.onlineonly}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$found_users item='row' name='found_users'}
	{if $smarty.foreach.found_users.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>name</th>
	<th>sip address</th>
	<th>aliases</th>
	<th>timezone</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="left">{$row.sip_uri|empty2nbsp}</td>
	<td align="left">{$row.aliases|empty2nbsp}</td>
	<td align="left">{$row.timezone|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_add}">add to phonebook</a></td>
	</tr>
	{if $smarty.foreach.found_users.last}
	</table>
		{if $smarty.foreach.found_users.iteration >= $config->max_showed_rows}
		<div class="swNumOfFoundRecords">The search generated too many matches, please be more specific</div>
		{else}
		<br />
		{/if}
	{/if}
{foreachelse}
	<div class="swNumOfFoundRecords">No users found</div>
{/foreach}

<div class="swBackToMainPage"><a href="{url url='phonebook.php' uniq=1}">back to phonebook</a></div>

<br>
{include file='_tail.tpl'}
