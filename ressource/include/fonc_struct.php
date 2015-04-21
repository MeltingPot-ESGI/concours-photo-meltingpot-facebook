<?php
include_once(dirname(dirname(__DIR__))."/include_config.php");
	function get_head(){

		
		$html ='';
		
		$html .= '	<head>
						<title>photo</title>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="description" content=" photo" />
						
						'.include_js().'
						
						<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
						<link rel="stylesheet" type="text/css" media="screen" href="'.AppConfig::getCssDirectory().'style.css" />
						<link rel="stylesheet" media="screen" type="text/css" title="Design" href="'.AppConfig::getCssDirectory().'structure.css" />
						<link rel="stylesheet" media="screen" type="text/css" title="Design" href="'.AppConfig::getCssDirectory().'button_effet.css" />
						<link rel="stylesheet" media="screen" type="text/css" title="Design" href="'.AppConfig::getCssDirectory().'admin_css.css" />	
						
						
					</head>';
		
		return $html;
	}
	
	function include_js(){
		$js = '';
		
		$js .= '<script src="'.AppConfig::getJsDirectory().'jquery-1.10.2.js"></script>
				<script src="'.AppConfig::getJsDirectory().'jquery-ui-1.10.4.custom.min.js"></script>	
				<script type="text/javascript" src="'.AppConfig::getJsDirectory().'liveQuery.js"></script>	
				<script type="text/javascript" src="'.AppConfig::getJsDirectory().'_js_admin.js"></script>
                                    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		
				
				
				';
				
		return $js;
	}
?>