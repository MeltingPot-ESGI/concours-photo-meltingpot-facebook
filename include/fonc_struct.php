<?php
	function get_head(){

		
		$html ='';
		
		$html .= '	<head>
						<title>photo</title>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="description" content=" photo" />
						
						'.include_js().'
						
						<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
						
						<link rel="stylesheet" media="screen" type="text/css" title="Design" href="_css/structure.css" />
						<link rel="stylesheet" media="screen" type="text/css" title="Design" href="_css/button_effet.css" />
						<link rel="stylesheet" media="screen" type="text/css" title="Design" href="_css/admin_css.css" />	
						
						
					</head>';
		
		return $html;
	}
	
	function include_js(){
		$js = '';
		
		$js .= '<script src="./_js/jquery-1.10.2.js"></script>
				<script src="./_js/jquery-ui-1.10.4.custom.min.js"></script>	
				<script type="text/javascript" src="./_js/liveQuery.js"></script>	
				<script type="text/javascript" src="./_js/_js_admin.js"></script>
		
				
				
				';
				
		return $js;
	}
?>