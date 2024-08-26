<?php

require_once __DIR__ . '/../services/services.php';

Flight::set('exam_service', new Service);

Flight::route('GET /connection-check', function(){
    new BaseDao();
});

Flight::route('POST /create_user', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->create_user($payload);
    Flight::json($data);
});

Flight::route('GET /profile/@userID', function($userID){
   
   $data = Flight::get('services')->get_profile_info($userID);
    Flight::json($data);

});
?>
