{* Smarty *}
{* $Id: u_notification_subscription.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">your subscribed events:</h2>

{foreach from=$subs_events item='row' name='subs_events'}
	{if $smarty.foreach.subs_events.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>description</th>
	<th width="90">&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top">
	<td align="left">{$row.description}</td>
	<td align="center"><a href="{$row.url_unsubsc}">unsubscribe</a></td>
	</tr>
	{if $smarty.foreach.subs_events.last}
	</table>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No subscribed events</div>
{/foreach}

<h2 class="swTitle">other events:</h2>

{foreach from=$other_events item='row' name='other_events'}
	{if $smarty.foreach.other_events.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable swWidthAsTitle">
	<tr>
	<th>description</th>
	<th width="90">&nbsp;</th>
	</tr>
	{/if}
	
	<tr valign="top">
	<td align="left">{$row.description}</td>
	<td align="center"><a href="{$row.url_subsc}">subscribe</a></td>
	</tr>
	
	{if $smarty.foreach.other_events.last}
	</table>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No other events</div>
{/foreach}

<br>
{include file='_tail.tpl'}
