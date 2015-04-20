<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css" />
		<title>Formulaire de participation</title>
		<meta name="description" content="Facebook - Concours Photos Tatouages">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	</head>
	<body>
		<div class="encart_concours">
			<h1>PARTICIPER AU CONCOURS</h1>
			<form>
				<label for="form_name">Nom: </label><input type="text" name="name" value="Nom" id="form_name" maxlength="100" size="50" onclick="this.value='';"><br>
				<label for="form_email">E-mail: </label><input type="text" name="email" value="E-mail" id="form_email" maxlength="100" size="50" onclick="this.value='';"><br>
				<label for="form_city">Ville: </label><input type="text" name="city" value="Ville" id="form_city" maxlength="100" size="50" onclick="this.value='';"><br>
				<label for="form_gooddeals">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="" id="form_gooddeals"><br>
				<label for="form_policy">J'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="" id="form_reglement">
				<input type="submit" name="form_validate" value="Participer">
			</form>
		</div>
	</body>
</html>