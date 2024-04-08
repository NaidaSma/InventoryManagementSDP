<?php

require 'vendor/autoload.php';

Flight::route('/', function(){
    echo 'HELLO WORLD';
});

Flight::start();
?>