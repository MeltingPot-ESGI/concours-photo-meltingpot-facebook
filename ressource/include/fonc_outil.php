<?php
//      -------------------------------------------------------------
//      AJOUTE DES ANTISLASHES À UN ARRAY
//      -------------------------------------------------------------
	function add_slashes(&$arr_r){
        foreach($arr_r as & $val) is_array($val) ? add_slashes($val) : $val = addslashes(trim($val));
        unset($val);
	}

//      -------------------------------------------------------------
//      SUPPRIME DES ANTISLASHES À UN ARRAY
//      -------------------------------------------------------------
	function strip_slashes(&$arr_r) {
        foreach($arr_r as & $val) is_array($val) ? strip_slashes($val) : $val = stripslashes($val);
        unset($val);
		}

//      -------------------------------------------------------------
//      SUPPRIME DES ACCENTS D'UNE CHAÎNE
//      -------------------------------------------------------------
	function stripAccChars($data) {
	        $chars_in = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','Þ','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ý','ý','þ','ÿ');
	        $chars_ou = array('a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','d','n','o','o','o','o','o','o','u','u','u','u','y','b','s','a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','d','n','o','o','o','o','o','o','u','u','u','y','y','b','y');
	        return str_replace($chars_in, $chars_ou, $data);
	}	

	
	function sendMailHtml($aOpt){

		$mail = new PHPmailer(); 
        $mail->IsSMTP(); 
        $mail->IsHTML(true); 
        $mail->SMTPAuth = true;  
		$mail->SMTPSecure = 'ssl'; 
        $mail->Host=$aOpt['Host'];
        $mail->Port = $aOpt['Port'];
        $mail->Username=$aOpt['Username'];
		$mail->Password=$aOpt['Password'];
        $mail->From=$aOpt['From']; 
        $mail->FromName = $aOpt['FromName'];
        $mail->AddAddress($aOpt['AddAddress']); 
        $mail->AddReplyTo($aOpt['AddReplyTo']);      
        $mail->Subject=$aOpt['Subject']; 
        $mail->Body=$aOpt['Body']; 
        if($aOpt['AddAttachment'] != '' ){
        	$mail->AddAttachment($aOpt['AddAttachment']);
        } 
         
		
        if(!$mail->Send()){ 
          //echo $mail->ErrorInfo;
		  echo false;  
        } 
        else{      
          //echo 'Mail envoyé avec succès';
          echo true; 
        } 
        $mail->SmtpClose(); 
        unset($mail); 
				
	}


/*!GN 2014-10-28 2eme version de fonction button avec array
$a_opt = array(	'id' => ''
				,'fct_js' => ''
				,'value'  => ''
				,'class'  => ''
				,'style'  => ''					
			);*/
function get_input_btn($a_opt){
	$html = '';
	$js = '';
	$style = '';

	if( isset($a_opt['style']) ){
		$style = ' style="'.$a_opt['style'].'" ';
	}

	//$html .= '<button id="'.$a_opt['id'].'" type="button" class="'.$a_opt['class'].'" name="'.$a_opt['id'].'" style="'.$style.'" >'.$a_opt['value'].'</button>';
	$html .= '<div id="'.$a_opt['id'].'" class="'.$a_opt['class'].'" name="'.$a_opt['id'].'" style="'.$style.'">'.$a_opt['value'].'</div>	';
	$js .= '$("#'.$a_opt['id'].'").click( function() { 
				'.$a_opt['fct_js'].';
			});
			';

	$html .= '<script>
					'.$js.'
				</script>';
	 
	
	return $html;
}


//***************************************//
//			Function admin
//***************************************//

function delete_elem($elem,$id){
	//return "the delete_elem($elem,$id)";
	
	$date_now = new DateTime();

	$sql = 'UPDATE `'.$elem.'` SET `status`= 1, `date_modif`= "'.$date_now->format('Y-m-d H:i:s').'"  WHERE `id`= '.$id.' ';
	//return "the sql ->".$sql;

	$a_res = connexion::delete($sql);
	
	return $a_res;
}

function valid_livre($elem,$id){
	$date_now = new DateTime();

	$sql = 'UPDATE `'.$elem.'` SET `status`= 2, `date_modif`= "'.$date_now->format('Y-m-d H:i:s').'"  WHERE `id`= '.$id.' ';
	//return "the sql ->".$sql;

	$a_res = connexion::update($sql);
	
	return $a_res;

}
















































































?>