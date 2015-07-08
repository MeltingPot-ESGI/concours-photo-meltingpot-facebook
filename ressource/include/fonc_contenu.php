<?php

function get_login(){
		$html = '';
		
		$html .=	'<div class="bloc_login">
		   				<div style="height: 15%;margin-right: 2%;margin-top: 2%;">
							<i style="font-size: 30px;float: right;" class="fa fa-cogs"></i>
						</div>
		   				<form method="post" name="form_login_admin" id="form_login_admin">
		   					<div style="">
								<div class="label">
									<label>Login : </label>
								</div>
								<div class="input">
									<input style="width: 75%;" type="text" name="login_admin" id="login_admin">
								</div>
							</div>
		   					<div style="">
								<div class="label">
									<label>Password : </label>
								</div>
								<div class="input">
									<input style="width: 75%;" type="password" name="pwd_admin" id="pwd_admin">
								</div>
							</div>
		   					<div style="float: left;margin-left: 38%;margin-top: 1%;">
								<div class="button" id="annuler" onclick="return false;">Reset</div>
								<div class="button" style="margin-left: 6px;" id="valider" onclick="login();return false;">Send</div>				 
							</div>
		   				</form>						
		   			</div>';
				
		return $html;
	}
	
        function getLinkPage ($numberOfPage, $text="")
        {
            $link = 'https://'.$_SERVER['HTTP_HOST'];
            
            $text = $text ? $text : $numberOfPage;
            
            return '<a class="fb-form-participate-infos" href="'.$link.'?currentPage='.$numberOfPage.'">'.$text.'</a>';
        }
?>