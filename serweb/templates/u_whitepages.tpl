{* Smarty *}
{* $Id: u_whitepages.tpl,v 1.5 2006/04/18 14:53:04 kozlik Exp $ *}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

<h2 class="swTitle">{$lang_str.find_user}</h2>

{literal}
<style type="text/css">
	#fname, #lname, #sipuri, #alias {width:180px;}
</style>	
{/literal}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">{$lang_str.ff_first_name}:</label></td>
	<td>{$form.fname}</td>
	</tr>
	<tr>
	<td><label for="lname">{$lang_str.ff_last_name}:</label></td>
	<td>{$form.lname}</td>
	</tr>
	<tr>
	<td><label for="sipuri">{$lang_str.ff_sip_address}:</label></td>
	<td>{$form.sipuri}</td>
	</tr>
	<tr>
	<td><label for="alias">{$lang_str.ff_alias}:</label></td>
	<td>{$form.alias}</td>
	</tr>
	<tr>
	<td><label for="onlineonly">{$lang_str.ff_show_online_only}:</label></td>
	<td>{$form.onlineonly}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$users item='row' name='users'}
	{if $smarty.foreach.users.first}
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
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="left">{$row.sip_uri|escape|empty2nbsp}</td>
	<td align="left">{include file="includes/inline_aliases.tpl" uris=$row.uris no_flags=1}</td>
	<td align="left">{$row.timezone|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_add_to_pb}">{$lang_str.l_add_to_phonebook}</a></td>
	</tr>
	{if $smarty.foreach.users.last}
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
