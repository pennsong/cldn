<!--{extends file='userPage.tpl'}-->
<!--{block name=title}-->
<title>普通用户首页</title>
<!--{/block}-->
<!--{block name=style}-->
<style type="text/css" media="screen">
	.locHMiddle {
		text-align: center;
	}
</style>
<!--{/block}-->
<!--{block name=subBody}-->
<div class="prepend-1 span-38">
	<div class="span-38">
		课程板块:
	</div>
	<div class="span-38">
		<div class="span-5">
			<a href="#">按板块分类</a>
		</div>
		<div class="prepend-1 span-5">
			<a href="#">按标签分类</a>
		</div>
	</div
	<div class="span-38">
		课程列表
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
</div>
<!--{/block}-->
