{* Smarty *}
{* $Id: u_send_im.tpl,v 1.2 2004/08/09 23:04:57 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left">
		<table border="0" cellspacing="0" cellpadding="0" align="left" style="margin-left: 0px;">
		<tr>
		<td><label for="sip_address">{$lang_str.ff_sip_address_of_recipient}:</label></td>
		<td>{$form.sip_address}</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td class="swHorizontalForm"><label for="instant_message">{$lang_str.ff_text_of_message}:</label></td>
	</tr>
	<tr>
	<td>{$form.instant_message}</td>
	</tr>
	<tr><td align="center">{$lang_str.im_remaining} {$form.num_chars} {$lang_str.im_characters}</td></tr>
	<tr>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}
