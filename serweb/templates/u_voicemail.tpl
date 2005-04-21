{* Smarty *}
{* $Id: u_voicemail.tpl,v 1.3 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.customize_greetings}:</h2>

<div class="swForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center" class="swWidthAsTitle">
<tr>
	<td align="left">{$form.vm_greeting}</td>
	<td align="right">{$form.okey}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right"><a href="{$vm_download_url}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_download_greeting.gif" width="165" height="16" border="0"></a></td>
</tr>
</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}

