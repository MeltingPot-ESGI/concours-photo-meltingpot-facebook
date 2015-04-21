<?php
include_once(dirname(dirname(__DIR__))."/include_config.php");
	function get_head(){

		
		$html ='';
		
		$html .= '	<head>
						<title>photo</title>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="description" content=" photo" />
						
						'.include_js().'
						
						<link rel="stylesheet" type="text/css" media="screen" href="ressource/public/_css/structure.css" />
						<link rel="stylesheet" type="text/css" media="screen" href="ressource/public/_css/admin_css.css" />
						<link rel="stylesheet" type="text/css" media="screen" href="ressource/public/_css/button_effet.css" />
						<link rel="stylesheet" type="text/css" media="screen" href="ressource/public/_css/style.css" />
						
						
						
					</head>';
		
		return $html;
	}
	
	function include_js(){
		$js = '';
		
		$js .= '<script src="ressource/public/_js/jquery-1.10.2.js"></script>
				<script src="ressource/public/_js/jquery-ui-1.10.4.custom.min.js"></script>	
				<script type="text/javascript" src="ressource/public/_js/liveQuery.js"></script>	
				<script type="text/javascript" src="ressource/public/_js/_js_admin.js"></script>
                                    
		
				
				
				';
				
		return $js;
	}
?>