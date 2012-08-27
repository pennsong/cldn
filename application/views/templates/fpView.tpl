<!--{extends file='defaultPage.tpl'}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<script type="text/javascript" src="{base_url()}resource/js/flexpaper_flash.js"></script>
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="span-64 last pageTop">
	<div class="span-45">
		<img class="logo" src="{base_url()}resource/img/logo.png"/>
	</div>
	<div class="span-16">
	</div>
	<div class="span-3">
	</div>
</div>
<div class="prepend-top span-64 last">
	<div style="position:absolute;left:0px;top:0px;">
		<p id="viewerPlaceHolder" style="width:960px;height:700px;display:block">
			Document loading..
		</p>
		<script type="text/javascript">
			var course = "{$course}";
			var numPages = "{$numPages}";
			var swfFileUrl = escape('({site_url("FPView/noLogin_view")}/' + course + '/[*,0],' + numPages + ')');
			;
			var searchServiceUrl = '';
			var fp = new FlexPaperViewer('{base_url()}resource/js/FlexPaperViewer', 'viewerPlaceHolder',
			{
				config :
				{
					SwfFile : swfFileUrl,
					Scale : 0.6,
					ZoomTransition : 'easeOut',
					ZoomTime : 0.5,
					ZoomInterval : 0.2,
					FitPageOnLoad : false,
					FitWidthOnLoad : false,
					FullScreenAsMaxWindow : false,
					ProgressiveLoading : true,
					MinZoomSize : 0.2,
					MaxZoomSize : 5,
					SearchMatchAll : true,
					SearchServiceUrl : searchServiceUrl,
					InitViewMode : 'Portrait',
					BitmapBasedRendering : false,
					ViewModeToolsVisible : true,
					ZoomToolsVisible : true,
					NavToolsVisible : true,
					CursorToolsVisible : true,
					SearchToolsVisible : true,
					localeChain : 'en_US'
				}
			});
		</script>
	</div>
</div>
<!--{/block}-->