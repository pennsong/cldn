<!--{extends file='defaultPage.tpl'}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="span-64 last pageTop">
	<div class="span-45">
		<img class="logo" src="{base_url()}resource/img/logo.png"/>
	</div>
	<div class="span-16">
		欢迎您,访客|<a href="{site_url('login/')}">注册</a>
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