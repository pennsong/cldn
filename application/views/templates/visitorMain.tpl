<!--{extends file='visitorPage.tpl'}-->
<!--{block name=title}-->
<title>访客首页</title>
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
	.red {
		color: red
	}
	.draw {
		cursor: pointer;
	}
	.close {
		width: 8px;
		height: 8px
	}
	.open {
		width: 8px;
		height: 8px
	}
	.expand {
		display: none
	}
	.odd {
		background: #FFFFFF;
	}
	.even {
		background: #EEEEEE;
	}
	.level1 {
		font-weight: bolder;
		color: #000000;
	}
	.level2 {
		font-weight: bolder;
		color: #888888;
	}
	.level3 {
		font-weight: bolder;
		color: #9ACC9A;
	}
</style>
<!--{/block}-->
<!--{block name=subScript}-->
<script>
	$(document).ready(function()
	{
		function nodeToggle(node)
		{
			if (node.children().attr('class') == 'close')
			{
				node.children().attr('class', 'open');
				node.children().attr('src', '{base_url()}resource/img/close.png');
			}
			else
			{
				node.children().attr('class', 'close');
				node.children().attr('src', '{base_url()}resource/img/open.png');
			}
			node.siblings().toggle();
		}

		function nodeOpen(node)
		{
			node.siblings().show();
			node.children().attr('class', 'open');
			node.children().attr('src', '{base_url()}resource/img/close.png');
		}

		function nodeClose(node)
		{
			node.siblings().hide();
			node.children().attr('class', 'close');
			node.children().attr('src', '{base_url()}resource/img/open.png');
		}


		$(".locHolderDiv").hover(function()
		{
			$(".locNoteDiv", this).show();
		}, function()
		{
			$(".locNoteDiv", this).hide();
		});
		$(".draw").click(function()
		{
			nodeToggle($(this));
		});
		$("#openAll").click(function(event)
		{
			event.preventDefault();
			nodeOpen($(".draw"));
		});
		$("#closeAll").click(function(event)
		{
			event.preventDefault();
			nodeClose($(".draw"));
		});
		//初始打开第一层菜单
		nodeToggle($(".level1"));
	}); 
</script>
<!--{/block}-->
<!--{block name=subBody}-->
<div class="prepend-1 span-38">
	<div class="span-38 last">
		{$msg|default:""}
	</div>
	<div class="span-38 last">
		<span class="red">*本网站主要提供通用的业务和技术培训课程，如需根据贵公司的实际业务定制专门的培训课程，请联系华钦软件技术咨询服务部（负责人：何耀威， 邮箱：<a href="mailto:filip.ho@clps.com.cn">filip.ho@clps.com.cn</a>）</span>
	</div>
	<div class="span-38">
		<span class="cldnH1">培训课程:</span>
	</div>
	<div class="span-38">
		<ul id="navtabs">
			<li>
				<a class="{if $sortType=='area'} currentMenu {else} {/if}" href="{site_url('visitorMain/noLogin_index/area')}">按板块分类</a>
			</li>
			<li>
				<a class="{if $sortType=='mark'} currentMenu {else} {/if}" href="{site_url('visitorMain/noLogin_index/mark')}">按标签分类</a>
			</li>
		</ul>
	</div>
	<div class="span-38">
		<div class="span-5">
			课程列表
		</div>
		<div class="span-5">
			<a href="{site_url('visitorMain/noLogin_index/')}/{$sortType}">All</a>
		</div>
		<div class="span-5">
			<a href="{site_url('visitorMain/noLogin_index/')}/{$sortType}/ch">中文</a>
		</div>
		<div class="span-5">
			<a href="{site_url('visitorMain/noLogin_index/')}/{$sortType}/en">En</a>
		</div>
		<div id="openAll" class="span-5">
			<a href="#">全部展开</a>
		</div>
		<div id="closeAll" class="span-5">
			<a href="#">全部收起</a>
		</div>
	</div>
	<div class="span-38 rightBorder">
		{if $sortType == 'area'}
		{foreach $courseAreaSortList as $bigArea}
		<div class="prepend-1 span-37">
			<div class="draw level1">
				<img class="close" src='{base_url()}resource/img/open.png' />{$bigArea['name']}
			</div>
			{foreach $bigArea['areaArray'] as $area}
			<div class="prepend-1 span-36 expand">
				<div class="draw level2">
					<img class="close" src='{base_url()}resource/img/open.png' />{$area['name']}
				</div>
				{foreach $area['markArray'] as $mark}
				<div class="prepend-1 span-35 expand">
					<div class="draw level3">
						<img class="close" src='{base_url()}resource/img/open.png' />{$mark['name']}
					</div>
					{foreach $mark['courseList'] as $course}
					<div class="prepend-1 span-34 expand {cycle values='odd,even'}">
						<div class="span-23">
							<a class="normal" href="{site_url('login/detail')}/{$course['id']}" target="_blank">{$course['name']}</a>
						</div>
						<div class="span-5 point">
							{$course['cost']}点积分
						</div>
						<div class="span-3">
							<a class="normal" href="{site_url('FPView/noLogin_preview')}/{$course['id']}" target="_blank">预览</a>
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
		<div class="prepend-1 span-37">
			<div class="draw level1">
				<img class="close" src='{base_url()}resource/img/open.png' />{$mark['name']}
			</div>
			{foreach $mark['bigAreaArray'] as $bigArea}
			<div class="prepend-1 span-36 expand">
				<div class="draw level2">
					<img class="close" src='{base_url()}resource/img/open.png' />{$bigArea['name']}
				</div>
				{foreach $bigArea['areaArray'] as $area}
				<div class="prepend-1 span-35 expand">
					<div class="draw level3">
						<img class="close" src='{base_url()}resource/img/open.png' />{$area['name']}
					</div>
					{foreach $area['courseList'] as $course}
					<div class="prepend-1 span-34 expand {cycle values='odd,even'}">
						<div class="span-23">
							<a class="normal" href="{site_url('login/detail')}/{$course['id']}" target="_blank">{$course['name']}</a>
						</div>
						<div class="span-5 point">
							{$course['cost']}点积分
						</div>
						<div class="span-3">
							<a class="normal" href="{site_url('FPView/noLogin_preview')}/{$course['id']}" target="_blank">预览</a>
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
</div>
<div class="prepend-1 span-23 last">
	<div class="span-23">
		<span class="cldnH1">已购买课程:</span>
	</div>
</div>
<!--{/block}-->
