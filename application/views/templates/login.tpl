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
			<div class="clear prepend-19 last append-bottom20">
				<div>
					<span class="cldnH1">金融与技术知识学院</span>
				</div>
				<div>
					<span class="cldnH1">Financial IT Academy</span>
				</div>
			</div>
			<form id="locLoginForm" action="{site_url('login/validateLogin')}" method="post">
				<div class="clear prepend-19 append-bottom5">
					<div class="label1">
						用户名
					</div>
				</div>
				<div class="clear prepend-19 span-11 inline append-bottom10">
					<div class="relative">
						<input id="userName" name="userName" class="locDefaultStrContainer input1 validate[required, custom[onlyLetterNumberUnderLineDot], minSize[6], maxSize[15]]" value="{$smarty.post.userName|default:''}" type="text" />
						<div class="locDefaultStr defaultStr1 locUserNameDefaultStr">
							请输入用户名
						</div>
					</div>
				</div>
				<div class="clear prepend-19 append-bottom5">
					<div class="label1">
						密码
					</div>
				</div>
				<div class="clear prepend-19 span-11 inline append-bottom20">
					<div class="relative">
						<input id="password" name="password" class="locDefaultStrContainer input1 validate[required, custom[onlyLetterNumber], minSize[6], maxSize[20]]" type="password" />
						<div class="locDefaultStr defaultStr1 locUserNameDefaultStr">
							请输入密码
						</div>
					</div>
				</div>
				<div class="clear prepend-19 span-6">
					<a class="line1" href="{site_url('visitorMain/noLogin_index')}">先进去看看</a>
				</div>
				<div class="clear prepend-19 append-bottom5">
					<div class="span-6 label1">
						请选择您的身份
					</div>
				</div>
				<div class="clear prepend-19 append-bottom20 locUserType">
					<div class="span-15 label1">
						{html_radios name='type' values=$typeId output=$typeName labels=FALSE selected=$type|default:1}
					</div>
				</div>
				<div class="clear prepend-19">
					<div class="inline span-3">
						<button id="loginButton" class="button1" type="submit">
							登录
						</button>
					</div>
					<div class="span-10 locGeneralErrorInfo">
						<span class="error1">{$loginErrorInfo|default:''}</span>
					</div>
				</div>
			</form>
			<hr>
			<div>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "><b style="font-family: 宋体; font-size: 16px; "><span style="font-size: 10.5pt; ">如何购买用户帐号</span></b></span></span>
					</p>
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "><b style="font-family: 宋体; font-size: 16px; "><span style="font-size: 10.5pt; ">
									<br>
								</span></b></span></span>
					</p>
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">1.</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">需要购买“华钦知识库”网站账户的企业或个人，请联系华钦软件<b>技术咨询服务部（</b></span></span><span class="cldnh1"><b><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">Technical and Consulting Services</span></b></span><span class="cldnh1"><b><span style="font-size: 10.5pt; ">）</span></b></span><span class="cldnh1"><span style="font-size: 10.5pt; ">，并告知需要购买的账户数量和积分数量，以及所选的计费方式。联系方式如下：</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 48pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span style="font-size: 10.5pt; color: rgb(0, 112, 192); ">技术咨询服务部负责人：何耀威（</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; color: rgb(0, 112, 192); ">Filip Ho</span></span><span class="cldnh1"><span style="font-size: 10.5pt; color: rgb(0, 112, 192); ">）</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 48pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span style="font-size: 10.5pt; color: rgb(0, 112, 192); ">邮箱：</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; color: rgb(0, 112, 192); "><a href="mailto:filip.ho@clps.com.cn" style="color: purple; "><span style="color: rgb(0, 112, 192); ">filip.ho@clps.com.cn</span></a></span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 48pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span style="font-size: 10.5pt; color: rgb(0, 112, 192); ">电话：</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; color: rgb(0, 112, 192); ">86 21 31268010</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; color: rgb(0, 112, 192); ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; color: rgb(0, 112, 192); ">分机：</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; color: rgb(0, 112, 192); ">6111</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 48pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; color: rgb(0, 112, 192); ">&nbsp;</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">2.</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">可选的计费方式包括：</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 84pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; text-indent: -21pt; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">1)</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">包年账户</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">：</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">1,000</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">元</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">/</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">账户，送<span lang="EN-US">1,000</span>分，可以阅读购买网站中所有的课程，有效期</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">1</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">年；</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 84pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; text-indent: -21pt; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">2)</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">购买积分：</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">1</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">元</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">/</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">分，客户可根据需要使用积分在网站中购买课程。积分本身无有效期，但已购买课程的有效期为</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">1</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">年；</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 48pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; text-indent: 21pt; ">
						<span class="cldnh1"><span style="font-size: 10.5pt; ">注意：无论何种计费方式，每个账户都只供一名用户使用，不得转借他人。</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">&nbsp;</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">3.</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">如需购买（包括首次购买），请根据第<span lang="EN-US">1</span>项，练习公司技术服务部，签订合同，购买积分。</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">&nbsp;</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">4.</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">合同签订完成后，</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">技术咨询服务部将为客户开立所需的网站账户并预存相应的积分。相关账户号和初始密码将通过加密邮件的方式发送至客户方指定的联系人。</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
					</p>
				</div>
				<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; text-indent: 21pt; ">
					<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">&nbsp;</span></span><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; "></span>
				</p>
				<div style="font-family: Helvetica; margin-left: 42pt; ">
					<p class="MsoNormal" style="margin: 0cm 0cm 0.0001pt; font-size: 12pt; font-family: 宋体; text-align: justify; ">
						<span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">5.</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span class="apple-converted-space"><span lang="EN-US" style="font-size: 7pt; font-family: 'Times New Roman', serif; ">&nbsp;</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">客户收到账户号后即可开始使用，并需在收到帐号后的</span></span><span class="cldnh1"><span lang="EN-US" style="font-size: 10.5pt; font-family: Calibri, sans-serif; ">15</span></span><span class="cldnh1"><span style="font-size: 10.5pt; ">个工作日内将合同中约定的购买款项转入华钦软件指定的银行账户中。</span></span>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>