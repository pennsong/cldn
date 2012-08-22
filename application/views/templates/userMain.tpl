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
		border: 1px solid gray;
		background-color: #E5ECF9;
		display: none;
		z-index: 3000;
		overflow: auto;
	}
</style>
<!--{/block}-->
<!--{block name=subScript}-->
<script>
	$(document).ready(function() {
		$(".locHolderDiv").hover(function() {
			$(".locNoteDiv", this).show();
		}, function() {
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
		课程板块:
	</div>
	<div class="span-38">
		<div class="span-5">
			<a href="{site_url('userMain/index/area')}">按板块分类</a>
		</div>
		<div class="prepend-1 span-5">
			<a href="{site_url('userMain/index/mark')}">按标签分类</a>
		</div>
	</div>
	<div class="span-38">
		课程列表
	</div>
	<div class="span-38">
		<!--{foreach $courseList as $course}-->
		<div class="span-38">
			{$course['name']}
		</div>
		<div class="prepend-1 span-37">
			{foreach $course['courseArray'] as $item}
			<div class="span-22">
				{$item['name']}
			</div>
			<div class="span-3">
				{$item['cost']}积分
			</div>
			<div class="span-3 locHolderDiv">
				<a href="#">目录</a>
				<div class="locNoteDiv">
					{$item['list']}
				</div>
			</div>
			<div class="span-3 locHolderDiv">
				<a href="#">概述</a>
				<div class="locNoteDiv">
					{$item['introduction']}
				</div>
			</div>
			<div class="span-3">
				<a href="#">预览</a>
			</div>
			<div class="span-3">
				<!--{if $item['bought'] == 'yes'}-->
				<span>已购买</span>
				<!--{else if $item['bought'] == 'no'}-->
				<a href="{site_url('userMain/buyCourse')}/{$item['id']}/{$sortType}">购买</a>
				<!--{/if}-->
			</div>
			{/foreach}
		</div>
		<!--{/foreach}-->
	</div>
</div>
<div class="prepend-1 span-23 last">
	<div class="span-23">
		已购买课程
	</div>
	<!--{foreach $boughtCourseList as $course}-->
	<div class="prepend-1 span-22">
		<div class="span-3">
			<!--{$course['areaName']}-->
		</div>
		<div class="span-6">
			<!--{foreach $course['markList'] as $mark}-->
			<div class="span-3">
				<!--{$mark['markName']}-->
			</div>
			<!--{/foreach}-->
		</div>
		<div class="span-5">
			{$course['expiration']}
		</div>
		<div class="span-7">
			{$course['courseName']}
		</div>
	</div>
	<!--{/foreach}-->
</div>
<!--{/block}-->
