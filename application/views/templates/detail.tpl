<!--{extends file='defaultPage.tpl'}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="prepend-top span-64 last">
	<div>
		<div>
			<b>课程名称</b>
		</div>
		<div>
			{$course['name']}
		</div>
	</div>
	<hr>
	<div>
		<div>
			<b>目录</b>
		</div>
		<div>
			{$course['list']}
		</div>
	</div>
	<hr>
	<div>
		<div>
			<b>概要</b>
		</div>
		<div>
			{$course['introduction']}
		</div>
	</div>
</div>
<!--{/block}-->