<?php 
	require_once('lib/rb.php');
	
	date_default_timezone_set('Europe/Rome');
	
	R::setup('mysql:host=127.0.0.1;dbname=zara','zara', 'AL3strade');
	R::freeze(TRUE);
	
	$pg=(empty($_REQUEST['p'])) ? 'home' : $_REQUEST['p'];
	$pg='pgs/'.$pg.'.php';
	
?>
<!doctype html>
<html lang="it">
  <head>
    <title>Bilancio</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8" />
  </head>
  <body>
	<div id="all" class="all">
		<? if (file_exists($pg)) include_once($pg); ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" </script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js" </script>
  </body>
</html>
