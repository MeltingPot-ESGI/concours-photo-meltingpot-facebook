<?php
	include_once("../include/fonction.php"); 
	
	add_slashes($_POST);
	//print_r($_POST);
	
	$html = '';
	
	switch($_POST['ctxt']){
						
		default:
			$html = get_login();
			break;
	}
		
	echo $html;
	
?>