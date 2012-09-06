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
		z-index: 1000;
	}
	.locNoteDiv {
		position: absolute;
		top: 15px;
		left: 25px;
		width: 500px;
		border: 1px solid black;
		background-color: #FFFFFF;
		display: none;
		z-index: 3000;
		overflow: auto;
	}
	.draw {
		cursor: pointer;
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
		$(".draw").click(function()
		{
			$(this).siblings().toggle();
			if ($(this).children().html() == '&gt;')
			{
				$(this).children().html('&lt;');
			}
			else
			{
				$(this).children().html('&gt;');
			}
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
		<span class="cldnH1">培训课程:</span>
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
		<div class="span-5">
			课程列表
		</div>
		<div class="span-5">
			<a href="{site_url('userMain/index/')}/{$sortType}">All</a>
		</div>
		<div class="span-5">
			<a href="{site_url('userMain/index/')}/{$sortType}/ch">中文</a>
		</div>
		<div class="span-5">
			<a href="{site_url('userMain/index/')}/{$sortType}/en">En</a>
		</div>
	</div>
	<div class="span-38 rightBorder">
		{if $sortType == 'area'}
		{foreach $courseAreaSortList as $bigArea}
		<div class="prepend-1 span-37">
			<div class="draw">
				{$bigArea['name']}<span>></span>
			</div>
			{foreach $bigArea['areaArray'] as $area}
			<div class="prepend-1 span-36">
				<div class="draw">
					{$area['name']} <span>></span>
				</div>
				{foreach $area['markArray'] as $mark}
				<div class="prepend-1 span-35">
					<div class="draw">
						{$mark['name']} <span>></span>
					</div>
					{foreach $mark['courseList'] as $course}
					<div class="prepend-1 span-34">
						<div class="span-23">
							<!--{if $course['bought'] == 'no'}-->
							<!--{$course['name']}-->
							<!--{elseif $course['bought'] == 'yes'}-->
							<a class="normal" href="{site_url('FPView/viewAll')}/{$course['id']}" target="_blank">{$course['name']}</a>
							<!--{/if}-->
						</div>
						<div class="span-5 point">
							{$course['cost']}点积分
						</div>
						<div class="span-3">
							<a class="normal" href="{site_url('FPView/noLogin_preview')}/{$course['id']}" target="_blank">预览</a>
						</div>
						<div class="span-3">
							<!--{if $course['bought'] == 'yes'}-->
							<span>已购买</span>
							<!--{elseif $course['bought'] == 'no'}-->
							<a class="normal" href="{site_url('userMain/buyCourse')}/{$course['id']}/{$sortType}">购买</a>
							<!--{/if}-->
						</div>
						<div class="prepend-31 span-6">
							<div class="span-30 locHolderDiv">
								<a class="normal" href="#">目录</a>
								<div class="locNoteDiv">
									{$course['list']}
								</div>
							</div>
						</div>
						<div class="prepend-31 span-6">
							<div class="span-30 locHolderDiv">
								<a class="normal" href="#">概述</a>
								<div class="locNoteDiv">
									{$course['introduction']}
								</div>
							</div>
						</div>
						<hr>
					</div>
					{/foreach}
				</div>
				{/foreach}
			</div>
			{/foreach}
		</div>
		{/foreach}
		{else if $sortType == 'mark'}
		{foreach $courseMarkSortList as $mark}
		<div class="prepend-1 span-37">
			<div class="draw">
				{$mark['name']}<span>></span>
			</div>
			{foreach $mark['bigAreaArray'] as $bigArea}
			<div class="prepend-1 span-36">
				<div class="draw">
					{$bigArea['name']}<span>></span>
				</div>
				{foreach $bigArea['areaArray'] as $area}
				<div class="prepend-1 span-35">
					<div class="draw">
						{$area['name']}<span>></span>
					</div>
					{foreach $area['courseList'] as $course}
					<div class="prepend-1 span-34">
						<div class="span-23">
							<!--{if $course['bought'] == 'no'}-->
							<!--{$course['name']}-->
							<!--{elseif $course['bought'] == 'yes'}-->
							<a class="normal" href="{site_url('FPView/viewAll')}/{$course['id']}" target="_blank">{$course['name']}</a>
							<!--{/if}-->
						</div>
						<div class="span-5 point">
							{$course['cost']}点积分
						</div>
						<div class="span-3">
							<a class="normal" href="{site_url('FPView/noLogin_preview')}/{$course['id']}" target="_blank">预览</a>
						</div>
						<div class="span-3">
							<!--{if $course['bought'] == 'yes'}-->
							<span>已购买</span>
							<!--{elseif $course['bought'] == 'no'}-->
							<a class="normal" href="{site_url('userMain/buyCourse')}/{$course['id']}/{$sortType}">购买</a>
							<!--{/if}-->
						</div>
						<div class="prepend-31 span-6">
							<div class="span-30 locHolderDiv">
								<a class="normal" href="#">目录</a>
								<div class="locNoteDiv">
									{$course['list']}
								</div>
							</div>
						</div>
						<div class="prepend-31 span-6">
							<div class="span-30 locHolderDiv">
								<a class="normal" href="#">概述</a>
								<div class="locNoteDiv">
									{$course['introduction']}
								</div>
							</div>
						</div>
						<hr>
					</div>
					{/foreach}
				</div>
				{/foreach}
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
		<a class="normal" href="{site_url('FPView/viewAll')}/{$course['course']}" target="_blank">{$course['courseName']}</a>
	</div>
	<!--{/foreach}-->
</div>
<!--{/block}-->
