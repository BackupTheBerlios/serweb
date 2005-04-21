{* Smarty *}
{* $Id: u_whitepages.tpl,v 1.1 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.find_user}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="wp_fname">{$lang_str.ff_first_name}:</label></td>
	<td>{$form.wp_fname}</td>
	</tr>
	<tr>
	<td><label for="wp_lname">{$lang_str.ff_last_name}:</label></td>
	<td>{$form.wp_lname}</td>
	</tr>
	<tr>
	<td><label for="wp_uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.wp_uname}</td>
	</tr>
	<tr>
	<td><label for="wp_sip_uri">{$lang_str.ff_sip_address}:</label></td>
	<td>{$form.wp_sip_uri}</td>
	</tr>
	<tr>
	<td><label for="wp_alias">{$lang_str.ff_alias}:</label></td>
	<td>{$form.wp_alias}</td>
	</tr>
	<tr>
	<td><label for="wp_onlineonly">{$lang_str.ff_show_online_only}:</label></td>
	<td>{$form.wp_onlineonly}</td>
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
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_sip_address}</th>
	<th>{$lang_str.th_aliases}</th>
	<th>{$lang_str.th_timezone}</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="left">{$row.sip_uri|empty2nbsp}</td>
	<td align="left">{$row.aliases|empty2nbsp}</td>
	<td align="left">{$row.timezone|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_add}">{$lang_str.l_add_to_phonebook}</a></td>
	</tr>
	{if $smarty.foreach.found_users.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.found_users} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
	<div class="swNumOfFoundRecords">{$lang_str.no_users_found}</div>
{/foreach}

<br>
<div class="swBackToMainPage"><a href="{url url='phonebook.php' uniq=1}">{$lang_str.l_back_to_phonebook}</a></div>

<br>
{include file='_tail.tpl'}
