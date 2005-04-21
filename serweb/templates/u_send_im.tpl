{* Smarty *}
{* $Id: u_send_im.tpl,v 1.3 2005/04/21 15:09:46 kozlik Exp $ *}

{literal}
<style>
	#im_num_chars{width:33px;}	
</style>
{/literal}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left">
		<table border="0" cellspacing="0" cellpadding="0" align="left" style="margin-left: 0px;">
		<tr>
		<td><label for="sip_address">{$lang_str.ff_sip_address_of_recipient}:</label></td>
		<td>{$form.im_sip_address}</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td class="swHorizontalForm"><label for="instant_message">{$lang_str.ff_text_of_message}:</label></td>
	</tr>
	<tr>
	<td>{$form.im_instant_message}</td>
	</tr>
	<tr><td align="center">{$lang_str.im_remaining} {$form.im_num_chars} {$lang_str.im_characters}</td></tr>
	<tr>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}
