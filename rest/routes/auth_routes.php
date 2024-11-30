<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/auth_service.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
Flight::set('auth_service', new AuthService);

Flight::route('POST /login', function(){
$payload=Flight::request()->data->getData();
$user=Flight::get('auth_service')->get_user_by_username($payload['username']);
if(!$user || !password_verify($payload['password'], $user['password']))
Flight::halt(401, "Unauthorized");

unset($user['password']);

$jwt_payload = [
'user' => $user,
'iat' => time(),
'exp' => time() + (60 * 60 * 24) // valid for day
];

$token = JWT::encode(
$jwt_payload,
Config::JWT_SECRET(),
'HS256'
);

Flight::json(
array_merge($user, ['token' => $token])
);
});

Flight::route('POST /logout', function() {
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

        Flight::json([
            'jwt_decoded' => $decoded_token,
            'user' => $decoded_token->user
        ]);
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});


?>