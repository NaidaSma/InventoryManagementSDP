<?php

require_once __DIR__ . '/../services/services.php';

Flight::set('exam_service', new Service);

Flight::route('GET /connection-check', function(){
    new BaseDao();
});
?>