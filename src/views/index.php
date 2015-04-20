<?php
    session_start();
    
    $dbopts = parse_url(getenv('DATABASE_URL'));
    $app->register(new Herrera\Pdo\PdoServiceProvider(),
        array(
            'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"],
            'pdo.port' => $dbopts["port"],
            'pdo.username' => $dbopts["user"],
            'pdo.password' => $dbopts["pass"]
        )
    );
    
    $app->get('/db/', function() use($app) {
    $st = $app['pdo']->prepare('SELECT name FROM test_table');
    $st->execute();

    $photos = array();
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $app['monolog']->addDebug('Row ' . $row['name']);
        $photos[] = $row;
    }

    return $app['twig']->render('database.twig', array(
        'names' => $names
    ));
});
?>
<!doctype html>
<html>
	<head>
      <meta charset="UTF-8">
      <title>Titre de la page</title>
      <meta name="description" content="description de ma page">
    </head>
   <?php 
   	include_once("./ressource/include/fonction.php");

        echo get_head();	
    ?>   
   <body>
        <div id="wrapper">
            <div class="under_wrapper">
                <div id="wrapper_admin">
                    
                </div>
            </div>
        </div>
   </body>
   <?php echo include_js(); ?>
</html>


<script>
	$(document).ready(function() {
            
	});


	
</script>
