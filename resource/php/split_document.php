<?php
require_once("lib/config.php"); 
require_once("lib/common.php");
$configManager = new Config();

$configManager = new Config();
if($configManager->getConfig('admin.password')==null){
	$url = 'setup.php';
	header("Location: $url");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">	
    <head> 
        <title>FlexPaper</title>         
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <style type="text/css" media="screen"> 
			html, body	{ height:100%; }
			body { margin:0; padding:0; overflow:auto; }   
			#flashContent { display:none; }
        </style> 
		
		<script type="text/javascript" src="js/flexpaper_flash.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
    </head> 
    <body>
    	<?php 
			// Setting current document from parameter or defaulting to 'Paper.pdf'
		
			$doc = "Report.pdf";
			if(isset($_GET["doc"]))
			$doc = $_GET["doc"];
			
			$pdfFilePath = $configManager->getConfig('path.pdf');
			$swfFilePath = $configManager->getConfig('path.swf');
		?> 
    	<div style="position:absolute;left:0px;top:0px;">
		<p id="viewerPlaceHolder" style="width:960px;height:700px;display:block">Document loading..</p>
	        <?php if(is_dir($pdfFilePath) && is_dir($swfFilePath) ){ ?>
		        <script type="text/javascript"> 
		        	var doc 				= '<?php print $doc; ?>';
					var numPages 			= <?php echo getTotalPages($pdfFilePath . $doc) ?>;
					var swfFileUrl 			= escape('{services/view.php?doc='+doc+'&page=[*,0],'+numPages+'}');
	        		var searchServiceUrl	= escape('services/containstext.php?doc='+doc+'&page=[page]&searchterm=[searchterm]');
	        	
					var fp = new FlexPaperViewer(	
							 'FlexPaperViewer',
							 'viewerPlaceHolder', { config : {
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
	  						
	  						 localeChain: 'en_US'
							 }});			
		        </script>
		<?php }else{ ?>
			<script type="text/javascript">
				$('#viewerPlaceHolder').html('Cannot read pdf & swf file path, please check your configuration (in php/lib/config/)');
			</script>
		<?php } ?>
        </div>
		
		
		
	
   </body> 
</html> 