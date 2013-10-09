<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!--{$commonHead}-->
		<!--{$jqueryHead}-->
		<!--{$validationEngineHead}-->
		<title>登录</title>
		<style type="text/css" media="screen">
			div.locUserNameDefaultStr {
				left: 4px;
			}
			div.locGeneralErrorInfo {
				padding-top: 1px;
				padding-bottom: 1px;
			}
			div.locUserType {
				height: 19px;
			}
		</style>
		<script>
			$(document).ready(function()
			{
				$(".locDefaultStr").click(function()
				{
					$(this).prev(".locDefaultStrContainer").focus();
				});
				$(".locDefaultStrContainer").focus(function()
				{
					$(this).next(".locDefaultStr").hide();
				});
				$(".locDefaultStrContainer").blur(function()
				{
					if ($(this).val() == "")
					{
						$(this).next(".locDefaultStr").show();
					}
				});
				$(".locDefaultStrContainer").blur();
				$("#locLoginForm").validationEngine('attach',
				{
					promptPosition : "centerRight",
					autoPositionUpdate : "true"
				});
			});
			function checkUserName(field, rules, i, options)
			{
				var err = new Array();
				var reg1 = /^[_\.].*/;
				var reg2 = /.*[_\.]$/;
				var str = field.val();
				if (reg1.test(str) || reg2.test(str))
				{
					err.push('* 不能以下划线或点开始或结束！');
				}
				if ((countOccurrences(str, '.') + countOccurrences(str, '_')) > 1)
				{
					err.push('* 一个用户名仅允许包含一个下划线或一个点！');
				}
				if (err.length > 0)
				{
					return err.join("<br>");
				}
			}

			function countOccurrences(str, character)
			{
				var i = 0;
				var count = 0;
				for ( i = 0; i < str.length; i++)
				{
					if (str.charAt(i) == character)
					{
						count++;
					}
				}
				return count;
			}
		</script>
	</head>
	<body class="cldn">
		<div class="container">
			<div class="span-64 last">
				  <img class="" src="{base_url()}resource/img/logo.png"/>
			</div>
			<div class="span-20">
				<div class="clear append-bottom20">
					<div>
						<span class="cldnH1">金融与技术知识学院</span>
					</div>
					<div>
						<span class="cldnH1">Financial IT Academy</span>
					</div>
				</div>
				<form id="locLoginForm" action="{site_url('login/validateLogin')}" method="post">
					<div class="clear append-bottom5">
						<div class="label1">
							用户名
						</div>
					</div>
					<div class="clear span-11 inline append-bottom10">
						<div class="relative">
							<input id="userName" name="userName" class="locDefaultStrContainer input1 validate[required, custom[onlyLetterNumberUnderLineDot], minSize[6], maxSize[15]]" value="{$smarty.post.userName|default:''}" type="text" />
							<div class="locDefaultStr defaultStr1 locUserNameDefaultStr">
								请输入用户名
							</div>
						</div>
					</div>
					<div class="clear append-bottom5">
						<div class="label1">
							密码
						</div>
					</div>
					<div class="clear span-11 inline append-bottom20">
						<div class="relative">
							<input id="password" name="password" class="locDefaultStrContainer input1 validate[required, custom[onlyLetterNumber], minSize[6], maxSize[20]]" type="password" />
							<div class="locDefaultStr defaultStr1 locUserNameDefaultStr">
								请输入密码
							</div>
						</div>
					</div>
					<div class="clear span-6">
						<a class="line1" href="{site_url('visitorMain/noLogin_index')}">先进去看看</a>
					</div>
					<div class="clear append-bottom5">
						<div class="span-6 label1">
							请选择您的身份
						</div>
					</div>
					<div class="clear append-bottom20 locUserType">
						<div class="span-15 label1">
							{html_radios name='type' values=$typeId output=$typeName labels=FALSE selected=$type|default:1}
						</div>
					</div>
					<div class="clear">
						<div class="inline span-3">
							<button id="loginButton" class="button1" type="submit">
								登录
							</button>
						</div>
						<div class="span-10 locGeneralErrorInfo">
							<span class="error1">{$loginErrorInfo|default:''}</span>
						</div>
					</div>
					<div class="clear">
						<!--{if isset($noticeTitle)}-->
						<a href="{site_url('login/getNotice')}" target='_blank'>{$noticeTitle}</a>
						<!--{/if}-->
					</div>
				</form>
			</div>
			<div class="prepend-1 span-43 last">
				<div style="font-family: Helvetica; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<b><span style="font-size: 10pt; ">如何申请购买网站账户</span></b><span lang="EN-US"></span>
					</p>
				</div>
				<ol start="1" type="1" style="margin-bottom: 0cm; font-family: Helvetica; margin-top: 0cm; ">
					<li class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span style="font-size: 10pt; ">需要购买“华钦知识库”网站账户的企业或个人，请联系华钦软件技术咨询服务部（<span lang="EN-US">Technical and Consulting Services</span>），联系方式如下：</span><span lang="EN-US"></span>
					</li>
				</ol>
				<div style="font-family: Helvetica; margin-left: 66pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; color: rgb(1, 112, 192); ">华钦软件技术咨询服务部负责人：胡晓鸣（<span lang="EN-US">Shawn Hu</span>）</span><span lang="EN-US"></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 66pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; color: rgb(1, 112, 192); ">邮箱：<span lang="EN-US"><a href="mailto:shawn.hu@clps.com.cn" style="color: purple; ">shawn.hu@clps.com.cn</a></span></span><span lang="EN-US"></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 66pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; color: rgb(1, 112, 192); ">电话：<span lang="EN-US">86 21 31268010<span class="apple-converted-space">&nbsp;</span></span>分机：<span lang="EN-US">6115</span></span><span lang="EN-US"></span>
					</p>
				</div>
				<ol start="2" type="1" style="margin-bottom: 0cm; font-family: Helvetica; margin-top: 0cm; ">
					<li class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; ">购买积分<b>：</b><span lang="EN-US">1</span>元<span lang="EN-US">/</span>分，可供用户在网站中购买课程，已购买课程的有效期为<span lang="EN-US">1</span>年。</span><span lang="EN-US"></span>
					</li>
					<li class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; ">年费会员<b>：</b>账户的有效期为一年，在有效期内，年费会员可以阅读网站中所有的课程（具体费用请咨询华钦软件技术咨询服务部）。</span><span lang="EN-US"></span>
					</li>
					<li class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; ">双方在协商确定计费方式、购买数量和价格后签订购买合同。</span><span lang="EN-US"></span>
					</li>
					<li class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; ">
						<span style="font-size: 10pt; ">合同签订完成后， 技术咨询服务部将为客户开立所需的网站账户并预存相应的积分。相关账户号和初始密码将通过加密邮件的方式发送至客户方指定的联系人。</span><span lang="EN-US"></span>
					</li>
					<li class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span style="font-size: 10pt; ">客户收到账户号后即可开始使用，并需在收到帐号后的<span lang="EN-US">15</span>个工作日内将合同中约定的购买款项转入华钦软件指定的银行账户中。</span>
					</li>
				</ol>
			</div>
		</div>
	</body>
</html>