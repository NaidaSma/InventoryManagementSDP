<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/auth_service.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
Flight::set('auth_service', new AuthService);
/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Authentication"},
 *     summary="User login",
 *     description="Authenticate the user and return a JWT token.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "password"},
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
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
/**
 * @OA\Post(
 *     path="/logout",
 *     tags={"Authentication"},
 *     summary="User logout",
 *     description="Decode the JWT token and log out the user.",
 *     security={{"ApiKey":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful logout",
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
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
//profile routes

/**
 * @OA\Put(
 *     path="/user/profile",
 *     tags={"Profile"},
 *     summary="Update user profile",
 *     description="Update the profile information of the authenticated user.",
 *     security={{"ApiKey":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "surname", "username"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="surname", type="string"),
 *             @OA\Property(property="username", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Profile updated successfully")
 *         )
 *     ),
 *     @OA\Response(response=400, description="Invalid input"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=404, description="User not found")
 * )
 */
Flight::route('PUT /user/profile/update', function () {
    $token = Flight::request()->getHeader("Authentication");

    if (!$token || !preg_match('/Bearer\s(\S+)/', $token, $matches)) {
        Flight::halt(401, "Unauthorized");
        return;
    }

    $token = $matches[1];
    $decodedToken = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
    $userID = $decodedToken->user->userID;

    $userData = Flight::request()->data->getData();

    if (!isset($userData['name']) || !isset($userData['surname']) || !isset($userData['username'])) {
        Flight::json(['error' => 'Invalid input'], 400);
        return;
    }

    $userService = new AuthService();
    $success = $userService->updateUserProfile($userID, $userData);

    if ($success) {
        Flight::json(['message' => 'Profile updated successfully']);
    } else {
        Flight::halt(400, "Failed to update profile");
    }
});


?>