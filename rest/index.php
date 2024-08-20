<?php

require "../vendor/autoload.php";
require "./services/services.php";

Flight::register('services', 'services');

require './routes/routes.php';

Flight::start();
 ?>


