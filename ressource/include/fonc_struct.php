<?php
	function get_head(){

		
		$html ='';
		
		$html .= '	<head>
						<title>photo</title>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="description" content=" photo" />
						
						'.include_js().'
						
						<link rel="stylesheet" type="text/css" media="screen" href="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_css/structure.css" />
						<link rel="stylesheet" type="text/css" media="screen" href="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_css/button_effet.css" />
						<link rel="stylesheet" type="text/css" media="screen" href="'.$_SERVER["HTTP_HOST"].'/../../../ressource/lib/Magnific-Popup-master/dist/magnific-popup.css" />
						<link rel="stylesheet" type="text/css" media="screen" href="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_css/style.css" />						
						
					</head>';
		
		return $html;
	}
	
	function include_js(){
		$js = '';
		
		$js .= '<script type="text/javascript" src="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_js/jquery-1.10.2.js"></script>
				<script type="text/javascript" src="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_js/jquery-ui-1.10.4.custom.min.js"></script>	
				<script type="text/javascript" src="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_js/liveQuery.js"></script>	
				<script type="text/javascript" src="'.$_SERVER["HTTP_HOST"].'/../../../ressource/public/_js/_js_admin.js"></script>
				<script type="text/javascript" src="'.$_SERVER["HTTP_HOST"].'/../../../ressource/lib/Magnific-Popup-master/dist/jquery.magnific-popup.js"></script>
                                <div id="fb-root"></div>
                                <script>
                                    window.fbAsyncInit = function() {
                                      FB.init({
                                        appId      : "'.APP_ID.'",
                                        xfbml      : true,
                                        version    : "v2.3"
                                      });
                                    };

                                    (function(d, s, id){
                                       var js, fjs = d.getElementsByTagName(s)[0];
                                       if (d.getElementById(id)) {return;}
                                       js = d.createElement(s); js.id = id;
                                       js.src = "//connect.facebook.net/fr_FR/sdk.js";
                                       fjs.parentNode.insertBefore(js, fjs);
                                     }(document, "script", "facebook-jssdk"));
                                </script>
          						';
				
		return $js;
	}
?>