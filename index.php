<?php

use GuiiReal\DB\Sql;
use Slim\Slim;

require_once("vendor/autoload.php");

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$sql = new Sql();
	$results = $sql->select("SELECT * FROM tb_users");
	echo json_encode($results);

});

$app->run();

 ?>