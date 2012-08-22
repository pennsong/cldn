<!--{extends file='uploaderPage.tpl'}-->
<!--{block name=title}-->
<title>管理员用户首页</title>
<!--{/block}-->
<!--{block name=style}-->
<style type="text/css" media="screen">
	.locHMiddle {
		text-align: center;
	}
</style>
<!--{/block}-->
<!--{block name=subScript}-->
<script type="text/javascript" src="{base_url()}resource/xheditor/xheditor-1.1.14-zh-cn.min.js"></script>
<!--{/block}-->
<!--{block name=subBody}-->
<div class="prepend-1 span-38">
	<div class="span-38">
		{$msg|default:""}
	</div>
	<form method="post" enctype="multipart/form-data" action="{site_url('uploaderMain/uploadSubmit')}">
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
			板块选择:
		</div>
		<div class="prepend-1 span-37">
			<!--{html_radios name='area' values=$areaIdList output=$areaNameList selected=$smarty.post.area|default:'' separator="<br />"}-->
		</div>
		<div class="span-38">
			标签选择:
		</div>
		<div class="prepend-1 span-37">
			<!--{html_checkboxes name='mark' values=$markIdList output=$markNameList selected=$smarty.post.mark|default:'' separator="<br />"}-->
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
</div>
<div class="prepend-1 span-23 last">
	<div class="span-23">
		已上传课程
	</div>
	<!--{foreach $uploadedFileList as $uploadedFile}-->
	<div class="prepend-1 span-22">
		<div class="span-3">
			<!--{$uploadedFile['areaName']}-->
		</div>
		<div class="span-7">
			<!--{foreach $uploadedFile['markList'] as $mark}-->
			<div class="span-3">
				<!--{$mark['markName']}-->
			</div>
			<!--{/foreach}-->
		</div>
		<div class="span-7 overflowHidden">
			{$uploadedFile['courseName']}
		</div>
		<div class="span-4">
			<a href="{site_url('uploaderMain/update')}/{$uploadedFile['course']}">编辑</a>
			<a href="{site_url('uploaderMain/delete')}/{$uploadedFile['course']}">删除</a>
		</div>
	</div>
	<!--{/foreach}-->
</div>
<!--{/block}-->
