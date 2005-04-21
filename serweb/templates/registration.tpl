{* Smarty *}
{* $Id: registration.tpl,v 1.1 2005/04/21 15:09:46 kozlik Exp $ *}

<div style="background:white;">

{include file='_head.tpl'}

{if $action != 'finished'}

	<div class="rbroundboxB" style="width: 50%; margin: 1em auto;">
	<div class="rbtopB"><div><div><span>VoIP SerWeb</span></div></div></div>
	<div class="rbcontentwrapB"><div class="rbcontentB">
		<br>
		<div align="left">{$lang_str.registration_introduction}</div>
		<br>
		<br>

		<div class="swForm swRegForm">
		{$form.start}

		<div class="rbroundbox">
		<div class="rbtop"><div><div></div></div></div>
		<div class="rbcontentwrap"><div class="rbcontent">
				<table border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
				<td><label for="fname">{$lang_str.ff_first_name}:</label></td>
				<td >{$form.fname}</td>
				</tr>
				<tr>
				<td><label for="lname">{$lang_str.ff_last_name}:</label></td>
				<td>{$form.lname}</td>
				</tr>
				<tr>
				<td><label for="email">{$lang_str.ff_email}:</label></td>
				<td>{$form.email}</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td><div class="swRegFormDesc">{$lang_str.reg_email_desc}</div></td>
				</tr>
				<tr>
				<td><label for="phone">{$lang_str.ff_phone}:</label></td>
				<td>{$form.phone}</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td><div class="swRegFormDesc">{$lang_str.reg_phone_desc}</div></td>
				</tr>
				<tr>
				<td><label for="timezone">{$lang_str.ff_your_timezone}:</label></td>
				<td>{$form.timezone}</td>
				</tr>
				<tr>
				<td><label for="uname">{$lang_str.ff_pick_username}:</label></td>
				<td>{$form.uname}</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td><div class="swRegFormDesc">{$lang_str.reg_username_desc}</div></td>
				</tr>
				<tr>
				<td><label for="passwd">{$lang_str.ff_pick_password}:</label></td>
				<td>{$form.passwd}</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td><div class="swRegFormDesc">{$lang_str.reg_password_desc}</div></td>
				</tr>
				<tr>
				<td><label for="passwd_r">{$lang_str.ff_confirm_password}:</label></td>
				<td>{$form.passwd_r}</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td colspan="2" class="swHorizontalForm"><label for="terms">{$lang_str.ff_terms_and_conditions}:</label></td></tr>
				<tr><td colspan="2">{$form.terms}</td></tr>
				<tr><td colspan="2" class="swHorizontalForm">{$form.accept} <label for="accept" style="display:inline;">{$lang_str.ff_i_accept}</label></td></tr>
				<tr><td colspan="2" align="right">&nbsp;&nbsp;&nbsp;</td></tr>

				<tr><td colspan="2">
					<div style="text-align:center;">
						<div style="float: left; width:48%;">{$form.okey}</div>
						<div style="float: left; width:48%;"><a href="{url url='../index.php'}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_back.gif" border="0" alt="{$lang_str.l_back}"></a></div>
					</div><br>&nbsp;
				</td></tr>
				</table>



		</div><!-- /rbcontent -->
		</div><!-- /rbcontentwrap -->
		<div class="rbbot"><div><div></div></div></div>
		</div><!-- /rbroundbox -->

		<br class="swCleaner">&nbsp;

		{$form.finish}
		</div>

	</div><!-- /rbcontentB -->
	</div><!-- /rbcontentwrapB -->
	<div class="rbbotB"><div><div></div></div></div>
	</div><!-- /rbroundboxB -->
{else}

	<div class="rbroundboxB" style="width: 50%; margin: 1em auto;">
	<div class="rbtopB"><div><div><span>VoIP SerWeb</span></div></div></div>
	<div class="rbcontentwrapB"><div class="rbcontentB">

		<br>

		<p>{$lang_str.reg_finish_thanks}.<br>
		<br>
		{$lang_str.reg_finish_app_forwarded}<br>
		{$lang_str.reg_finish_confirm_msg}<br>
		<br>
		{$lang_str.reg_finish_sip_address} {$reg_sip_address}.<br>
		<br>
		{$lang_str.reg_finish_questions}<br>
		{$lang_str.reg_finish_infomail}<br>
		<p>
		<br>

		<div><a href="{url url='../index.php'}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_back.gif" border="0"></a></div>

	</div><!-- /rbcontentB -->
	</div><!-- /rbcontentwrapB -->
	<div class="rbbotB"><div><div></div></div></div>
	</div><!-- /rbroundboxB -->

{/if}

{include file='_tail.tpl'}

</div><!-- background:white -->

