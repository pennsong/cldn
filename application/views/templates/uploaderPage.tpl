<!--{extends file='defaultPage.tpl'}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="span-64 last pageTop">
	<div class="span-47">
		<img class="logo" src="{base_url()}resource/img/logo.png"/>
	</div>
	<div class="span-14">
		欢迎您,管理员用户:{$CI->session->userdata('userName')}|<a href="{site_url('uploaderMain/changePassword')}">修改密码</a>
	</div>
	<div class="span-3">
		<a href="{site_url('login/help')}" target="_blank">帮助</a>|<a href="{site_url('login/logout')}">退出</a>
	</div>
</div>
<div class="prepend-top span-64 last">
	<!--{block name=subBody}-->
	<!--{/block}-->
</div>
<!--{/block}-->