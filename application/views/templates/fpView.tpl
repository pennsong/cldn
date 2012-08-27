<!--{extends file='defaultPage.tpl'}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="prepend-top span-64 last">
	<div style="position:absolute;left:0px;top:0px;">
		<div id="viewerPlaceHolder" style="width:760px;height:760px;margin: 0 auto">
		</div>
		<script type="text/javascript">
			var course = "{$course}";
			var numPages = "{$numPages}";
			var swfFileUrl = escape('({site_url("FPView/noLogin_view")}/' + course + '/[*,0],' + numPages + ')');
			var searchServiceUrl = '';
			flashembed("viewerPlaceHolder", '{base_url()}resource/js/FlexPaperViewer',
			{
				// these properties are given for the Flash object
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
			});
		</script>
	</div>
</div>
<!--{/block}-->