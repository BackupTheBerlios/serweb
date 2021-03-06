{* Smarty *}
{* $Id: a_domain_admin.tpl,v 1.5 2007/10/12 08:44:52 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#id,  #lid {width:150px;}
	#name, #lname {width:150px;}
	#customer, #lcustomer {width:150px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="id"  id="lid" >{$lang_str.d_id}</label></td>
<td><label for="name" id="lname">{$lang_str.d_name}</label></td>
<td><label for="customer" id="lcustomer">{$lang_str.owner}</label></td>
</tr>

<tr>
<td>{$form.id}</td>
<td>{$form.name}</td>
<td>{$form.customer}</td>
</tr>
				
<tr><td colspan="3" height="5"></td></tr>
<tr><td colspan="3" class="note">{$lang_str.filter_wildcard_note}</td></tr>
<tr><td colspan="3" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>


<div class="swAssignedDomainList">
<h3>{$lang_str.assigned_domains}</h3>

{foreach from=$assigned_domains item='row' name='domains'}
	{if $smarty.foreach.domains.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr valign="top">
	<th>{$lang_str.d_id}</th>
	<th>{$lang_str.d_name}</th>
	<th>{$lang_str.owner}</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle name='assigned' values='swTrOdd,swTrEven'}">
	<td align="left">{$row.id|escape|empty2nbsp}</td>
	<td align="left">
		{assign var='d_aliases' value=''}
		{foreach from=$row.names item='dom' name='one_domain'}
			{assign var='d_aliases' value="$d_aliases`$dom.name`"}
			{if !$smarty.foreach.one_domain.last}{assign var='d_aliases' value="$d_aliases, "}{/if}
		{/foreach}
		{$d_aliases|escape|truncate:45:"..."}
	</td>
	<td align="left">{$row.customer|escape|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_unassign}" style="white-space: nowrap;" class="actionsrow">{$lang_str.l_unassign}</a></td>
	</tr>
	{if $smarty.foreach.domains.last}
	</table>

	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_domains_found}</div>
{/foreach}

</div>

<div class="swUnassignedDomainList">
<h3>{$lang_str.unassigned_domains}</h3>

{foreach from=$unassigned_domains item='row' name='domains'}
	{if $smarty.foreach.domains.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr valign="top">
	<th>{$lang_str.d_id}</th>
	<th>{$lang_str.d_name}</th>
	<th>{$lang_str.owner}</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle name='unassigned' values='swTrOdd,swTrEven'}">
	<td align="left">{$row.id|escape|empty2nbsp}</td>
	<td align="left">
		{assign var='d_aliases' value=''}
		{foreach from=$row.names item='dom' name='one_domain'}
			{assign var='d_aliases' value="$d_aliases`$dom.name`"}
			{if !$smarty.foreach.one_domain.last}{assign var='d_aliases' value="$d_aliases, "}{/if}
		{/foreach}
		{$d_aliases|escape|truncate:45:"..."}
	</td>
	<td align="left">{$row.customer|escape|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_assign}" style="white-space: nowrap;" class="actionsrow">{$lang_str.l_assign}</a></td>
	</tr>
	{if $smarty.foreach.domains.last}
	</table>

	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_domains_found}</div>
{/foreach}

</div>
	
<br class="swCleaner">&nbsp;

<div class="swBackToMainPage"><a href="{url url='list_of_admins.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>
{include file='_tail.tpl'}
