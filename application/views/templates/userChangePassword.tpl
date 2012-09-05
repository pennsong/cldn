<!--{extends file='userPage.tpl'}-->
<!--{block name=title}-->
<title>修改密码</title>
<!--{/block}-->
<!--{block name=style}-->
<!--{/block}-->
<!--{block name=subScript}-->
<!--{/block}-->
<!--{block name=subBody}-->
<div class="prepend-19 span-20">
	<span class="error1"> {validation_errors()} </span>
	<form method="post" action="{site_url('userMain/updatePassword')}">
		<div class="span-5">
			旧密码:
		</div>
		<div class="span-15 last">
			<input name="oldPassword" />
		</div>
		<div class="span-5">
			新密码:
		</div>
		<div class="span-15 last">
			<input name="newPassword" />
		</div>
		<div class="span-5">
			确认新密码:
		</div>
		<div class="span-15 last">
			<input name="newPasswordConfirm" />
		</div>
		<div class="span-10">
			<input type="submit" value="保存">
		</div>
		<div class="span-10 last">
			<a href="{site_url('userMain')}">取消</a>
		</div>
	</form>
</div>
<!--{/block}-->
