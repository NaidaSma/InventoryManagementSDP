<?php

require "../vendor/autoload.php";
require "./services/services.php";
Flight::register('BaseDao', 'BaseDao');
Flight::register('services', 'services', [Flight::BaseDao()]);

require './routes/routes.php';

Flight::start();
 ?>


