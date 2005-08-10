{* Smarty *}
{* $Id: u_voicemail.tpl,v 1.4 2005/08/10 13:56:25 kozlik Exp $ *}

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
	<td align="right"><a href="{$vm_download_url}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_download_greeting.gif" width="165" height="16" border="0" alt="{$lang_str.b_download_greeting}"></a></td>
</tr>
</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}

