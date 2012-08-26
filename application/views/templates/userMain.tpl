<!--{extends file='userPage.tpl'}-->
<!--{block name=title}-->
<title>普通用户首页</title>
<!--{/block}-->
<!--{block name=style}-->
<style type="text/css" media="screen">
	.locHMiddle {
		text-align: center;
	}
	.locHolderDiv {
		position: relative;
	}
	.locNoteDiv {
		position: absolute;
		top: 15px;
		left: 20px;
		width: 500px;
		border: 1px solid black;
		background-color: #FFFFFF;
		display: none;
		z-index: 3000;
		overflow: auto;
	}
</style>
<!--{/block}-->
<!--{block name=subScript}-->
<script>
	$(document).ready(function()
	{
		$(".locHolderDiv").hover(function()
		{
			$(".locNoteDiv", this).show();
		}, function()
		{
			$(".locNoteDiv", this).hide();
		});
	}); 
</script>
<!--{/block}-->
<!--{block name=subBody}-->
<div class="prepend-1 span-38">
	<div class="span-38">
		{$msg|default:""}
	</div>
	<div class="span-38">
		<span class="cldnH1">课程板块:</span>
	</div>
	<div class="span-38">
		<ul id="navtabs">
			<li>
				<a class="{if $sortType=='area'} currentMenu {else} {/if}" href="{site_url('userMain/index/area')}">按板块分类</a>
			</li>
			<li>
				<a class="{if $sortType=='mark'} currentMenu {else} {/if}" href="{site_url('userMain/index/mark')}">按标签分类</a>
			</li>
		</ul>
	</div>
	<div class="span-38">
		课程列表
	</div>
	<div class="span-38 rightBorder">
		{if $sortType == 'mark'}
		{foreach $courseMarkSortList as $mark}
		<div class="span-38">
			<img src="{base_url()}resource/img/orange.png"/>&nbsp;<b>{$mark['name']}</b>
		</div>
		<div class="prepend-1 span-37">
			{foreach $mark['courseArray'] as $item}
			<div class="span-14">
				<!--{if $item['bought'] == 'no'}-->
				<!--{$item['name']}-->
				<!--{elseif $item['bought'] == 'yes'}-->
				<a class="normal" href="{site_url('FPView/viewAll')}/{$item['path']}" target="_blank">{$item['name']}</a>
				<!--{/if}-->
			</div>
			<div class="span-8">
				{$item['bigAreaName']}|{$item['areaName']}
			</div>
			<div class="span-3">
				{$item['cost']}积分
			</div>
			<div class="span-3 locHolderDiv">
				<a class="normal" href="#">目录</a>
				<div class="locNoteDiv">
					{$item['list']}
				</div>
			</div>
			<div class="span-3 locHolderDiv">
				<a class="normal" href="#">概述</a>
				<div class="locNoteDiv">
					{$item['introduction']}
				</div>
			</div>
			<div class="span-3">
				<a class="normal" href="{site_url('FPView/noLogin_preview')}/{$item['path']}" target="_blank">预览</a>
			</div>
			<div class="span-3">
				<!--{if $item['bought'] == 'yes'}-->
				<span>已购买</span>
				<!--{elseif $item['bought'] == 'no'}-->
				<a class="normal" href="{site_url('userMain/buyCourse')}/{$item['id']}/{$sortType}">购买</a>
				<!--{/if}-->
			</div>
			{/foreach}
		</div>
		{/foreach}
		{else if $sortType == 'area'}
		{foreach $courseAreaSortList as $area}
		<div class="span-38">
			<img src="{base_url()}resource/img/orange.png"/>&nbsp;<b>{$area['areaName']}</b>
		</div>
		<div class="prepend-1 span-37">
			{foreach $area['courseArray'] as $item}
			<div class="span-14">
				<!--{if $item['bought'] == 'no'}-->
				<!--{$item['name']}-->
				<!--{elseif $item['bought'] == 'yes'}-->
				<a class="normal" href="{site_url('FPView/viewAll')}/{$item['path']}" target="_blank">{$item['name']}</a>
				<!--{/if}-->
			</div>
			<div class="span-8">
				{$item['markName']}
			</div>
			<div class="span-3">
				{$item['cost']}积分
			</div>
			<div class="span-3 locHolderDiv">
				<a class="normal" href="#">目录</a>
				<div class="locNoteDiv">
					{$item['list']}
				</div>
			</div>
			<div class="span-3 locHolderDiv">
				<a class="normal" href="#">概述</a>
				<div class="locNoteDiv">
					{$item['introduction']}
				</div>
			</div>
			<div class="span-3">
				<a class="normal" href="{site_url('FPView/noLogin_preview')}/{$item['path']}" target="_blank">预览</a>
			</div>
			<div class="span-3">
				<!--{if $item['bought'] == 'yes'}-->
				<span>已购买</span>
				<!--{elseif $item['bought'] == 'no'}-->
				<a class="normal" href="{site_url('userMain/buyCourse')}/{$item['id']}/{$sortType}">购买</a>
				<!--{/if}-->
			</div>
			{/foreach}
		</div>
		{/foreach}
		{/if}
	</div>
</div>
<div class="prepend-1 span-23 last">
	<div class="span-23">
		<span class="cldnH1">已购买课程:</span>
	</div>
	<!--{foreach $boughtCourseList as $course}-->
	<div class="prepend-1 span-22">
		{$course['bigAreaName']}|{$course['areaName']}({$course['markName']})
	</div>
	<div class="prepend-1 span-22">
		{$course['expiration']}|{$course['updated']}
	</div>
	<div class="prepend-2 span-21">
		<a class="normal" href="{site_url('FPView/viewAll')}/{$course['path']}" target="_blank">{$course['courseName']}</a>
	</div>
	<!--{/foreach}-->
</div>
<!--{/block}-->
