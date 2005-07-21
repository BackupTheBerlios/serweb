{* Smarty *}
{* $Id: a_list_of_admins.tpl,v 1.4 2005/07/21 16:23:04 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#usrnm,  #lusrnm {width:120px;}
	#domain, #ldomain {width:120px;}
	#fname,  #lfname {width:120px;}
	#lname,  #llname {width:120px;}
	#email,  #lemail {width:120px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="usrnm"  id="lusrnm" >{$lang_str.ff_username}</label></td>
<td><label for="domain" id="ldomain">{$lang_str.ff_domain}</label></td>
<td><label for="fname"  id="lfname" >{$lang_str.ff_first_name}</label></td>
<td><label for="lname"  id="llname" >{$lang_str.ff_last_name}</label></td>
<td><label for="email"  id="lemail" >{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.usrnm}</td>
<td>{$form.domain}</td>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
</tr>

<tr><td colspan="5"><label for="adminsonly" style="display: inline;">{$lang_str.ff_show_admins_only}:</label>{$form.adminsonly}</td></tr>
				
<tr><td colspan="5" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

<h2 class="swTitle">{$lang_str.list_of_users}</h2>

{foreach from=$users item='row' name='admins'}
	{if $smarty.foreach.admins.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_username}</th>
	<th>{$lang_str.th_domain}</th>
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_email}</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.username|empty2nbsp}</td>
	<td align="left">{$row.domain|empty2nbsp}</td>
	<td align="left">{$row.name|empty2nbsp}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{url url='admin_privileges.php' uniq=1}&{$row.get_param}">{$lang_str.l_change_privileges}</a></td>
	</tr>
	{if $smarty.foreach.admins.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.showed_users} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_users_found}</div>
{/foreach}
	
<br>
{include file='_tail.tpl'}
