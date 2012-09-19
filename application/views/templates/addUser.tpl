<!--{extends file='uploaderPage.tpl'}-->
<!--{block name=title}-->
<title>{$title}</title>
<!--{/block}-->
<!--{block name=style}-->
<!--{foreach $css_files as $file}-->
<link type="text/css" rel="stylesheet" href="{$file}" />
<!--{/foreach}-->
<!--{/block}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<!--{foreach $js_files as $file}-->
<script src="{$file}"></script>
<!--{/foreach}-->
<!--{/block}-->
<!--{/block}-->
<!--{block name=subBody}-->
<div class="span-64 last">
	<div class="span-64 last">
		<div class="span-5">
			<a href="{site_url('addUser/user')}">普通用户</a>
		</div>
		<div class="prepend-1 span-5">
			<a href="{site_url('addUser/uploader')}">管理员</a>
		</div>
		<div class="prepend-1 span-5">
			<a href="{site_url('addUser/admin')}">超级管理员</a>
		</div>
		<div class="prepend-1 span-5">
			<a href="{site_url('addUser/notice')}">通知</a>
		</div>
	</div>
</div>
<div class="span-64 last">
	{if $title != '用户管理'}
	<div class="ok1">
		管理'{$title}'
	</div>
	{/if}
</div>
<div class="span-64 last">
	<!--{$output}-->
</div>
<!--{/block}-->
