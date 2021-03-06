<!--{extends file='uploaderPage.tpl'}-->
<!--{block name=title}-->
<title>管理员用户首页</title>
<!--{/block}-->
<!--{block name=style}-->
<style type="text/css" media="screen">
	.locHMiddle {
		text-align: center;
	}
	.draw {
		cursor: pointer;
	}
</style>
<!--{/block}-->
<!--{block name=subScript}-->
<script type="text/javascript" src="{base_url()}resource/xheditor/xheditor-1.1.14-zh-cn.min.js"></script>
<script>
	$(document).ready(function()
	{
		$(".delete").click(function(e)
		{
			var r = confirm("确定要删除此条记录么?");
			if (r == true)
			{
			}
			else
			{
				e.preventDefault();
			}
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
<div class="prepend-1 span-38 rightBorder">
	<div class="span-38">
		{$msg|default:""}
	</div>
	<!--{if ($type == 'create' && $CI->session->userdata('type') == 'admin')}-->
	<span>请在右侧列表选择需要修改的课程</span>
	<!--{else}-->
	<form method="post" enctype="multipart/form-data" action="{site_url('uploaderMain/uploadSubmit')}/{$sortType}">
		<input type="hidden" name="type" value="{$type}">
		<input type="hidden" name="course" value="{$smarty.post.course|default:''}">
		<input type="hidden" name="uploader" value="{$CI->session->userdata('userId')}" />
		{if $type == 'create'}
		<div class="span-38">
			上传文件:
			<input type="file" name="fileName">
		</div>
		{else if $type == 'update'}
		<div class="span-38">
			正在编辑课程:
			<br />
			<input name="name" value="{$smarty.post.name}" readonly="readonly"/>
		</div>
		{/if}
		<div class="span-38">
			语言选择:
		</div>
		<div class="prepend-1 span-37">
			<!--{html_radios name='language' values=$languageValue output=$languageName selected=$smarty.post.language|default:'' separator="<br />"}-->
		</div>
		<div class="span-38">
			板块选择:
		</div>
		<div class="prepend-1 span-37">
			<!--{html_radios name='area' values=$areaIdList output=$areaNameList selected=$smarty.post.area|default:'' separator="<br />"}-->
		</div>
		<div class="span-38">
			排序(数字小的排在前面):
			{if $CI->session->userdata('type') == 'admin'}
			<input name="sortOrder" value="{$smarty.post.sortOrder|default:'0'}" readonly="readonly" />
			{else}
			<input name="sortOrder" value="{$smarty.post.sortOrder|default:'0'}" />
			{/if}
		</div>
		<div class="span-38">
			标签选择:
		</div>
		<div class="prepend-1 span-37">
			<!--{html_radios name='mark' values=$markIdList output=$markNameList selected=$smarty.post.mark|default:'' separator="<br />"}-->
		</div>
		<div class="span-38">
			分数:
			<input name="cost" value="{$smarty.post.cost|default:''}"/>
		</div>
		<div class="span-38">
			概述:
		</div>
		<div class="prepend-1 span-37">
			<textarea class="xheditor" name="introduction">{$smarty.post.introduction|default:''}</textarea>
		</div>
		<div class="span-38">
			目录:
		</div>
		<div class="prepend-1 span-37">
			<textarea class="xheditor" name="list">{$smarty.post.list|default:''}</textarea>
		</div>
		<div class="prepend-17 span-4">
			<input type="submit" value="上传" />
		</div>
	</form>
	<!--{/if}-->
</div>
<div class="prepend-1 span-23 last">
	<div class="span-23">
		<div class="span-5">
			已上传课程
		</div>
		<div class="prepend-1 span-17">
			<ul id="navtabs">
				<li>
					<a class="{if $sortType=='area'} currentMenu {else} {/if}" href="{site_url('uploaderMain/index')}/area">按板块排序</a>
				</li>
				<li>
					<a class="{if $sortType=='mark'} currentMenu {else} {/if}" href="{site_url('uploaderMain/index')}/mark">按级别排序 </a>
				</li>
			</ul>
		</div>
	</div>
	{if $sortType == 'area'}
	{foreach $courseAreaSortList as $bigArea}
	<div class="prepend-1 span-22">
		<div class="draw">
			{$bigArea['name']}<span>></span>
		</div>
		{foreach $bigArea['areaArray'] as $area}
		<div class="prepend-1 span-21">
			<div class="draw">
				{$area['name']} <span>></span>
			</div>
			{foreach $area['markArray'] as $mark}
			<div class="prepend-1 span-20">
				<div class="draw">
					{$mark['name']} <span>></span>
				</div>
				{foreach $mark['courseList'] as $course}
				<div class="prepend-1 span-19">
					<div class="span-14">
						{$course['name']}
					</div>
					<div class="span-5">
						<a href="{site_url('uploaderMain/update')}/{$course['id']}/{$sortType}">编辑</a>
						<a class="delete" href="{site_url('uploaderMain/delete')}/{$course['id']}/{$sortType}">删除</a>
					</div>
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
	<div class="prepend-1 span-22">
		<div class="draw">
			{$mark['name']}<span>></span>
		</div>
		{foreach $mark['bigAreaArray'] as $bigArea}
		<div class="prepend-1 span-21">
			<div class="draw">
				{$bigArea['name']}<span>></span>
			</div>
			{foreach $bigArea['areaArray'] as $area}
			<div class="prepend-1 span-20">
				<div class="draw">
					{$area['name']}<span>></span>
				</div>
				{foreach $area['courseList'] as $course}
				<div class="prepend-1 span-19">
					<div class="span-14">
						{$course['name']}
					</div>
					<div class="span-5">
						<a href="{site_url('uploaderMain/update')}/{$course['id']}/{$sortType}">编辑</a>
						<a class="delete" href="{site_url('uploaderMain/delete')}/{$course['id']}/{$sortType}">删除</a>
					</div>
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
<!--{/block}-->
