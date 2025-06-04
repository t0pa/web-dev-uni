<?php
/**
 * @OA\Put(
 *     path="/user/update",
 *     tags={"users"},
 *     security={{"ApiKey": {}}},
 *     summary="Update authenticated user's name",
 *     description="Update the authenticated user's username and email",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email"},
 *             @OA\Property(property="username", type="string", example="john_updated"),
 *             @OA\Property(property="email", type="string", format="email", example="john_updated@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     )
 * )
 */
Flight::route('PUT /user/update', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $user = Flight::get('user'); // Assumes user is authenticated
    $id = $user->id; // Get the authenticated user's ID 
    $data = Flight::request()->data->getData();

    $allowedFields = ['username', 'email'];
    $data = array_intersect_key($data, array_flip($allowedFields));
    // Validate that only allowed fields are being updated

    $result = Flight::userService()->update($id, $data);

    if ($result) {
        Flight::json(['message' => 'User updated successfully']);
    } else {
        Flight::halt(500, json_encode(['error' => 'Failed to update user']));
    }
});


/**
 * @OA\Get(
 *     path="/user/me",
 *     tags={"users"},
 *   security={{"ApiKey": {}}},
 *     summary="Get data about the currently authenticated user",
 *  
 *     @OA\Response(
 *         response=200,
 *         description="User data"
 *     )
 * )
 */
Flight::route('GET /user/me', function(){ 
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
      $user = Flight::get('user'); // Assumes user is authenticated
    $user_id = $user->id; 
   Flight::json(Flight::userService()->getById($user_id));
});


/**
 * @OA\Get(
 *     path="/user",
 *     tags={"users"},
 *    security={{"ApiKey": {}}},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="List of all users"
 *     )
 * )
 */
Flight::route('GET /user', function(){
         Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   Flight::json(Flight::userService()->getAll());
});

/**
 * @OA\Get(
 *     path="/user/{id}",
 *     tags={"users"},
 *   security={{"ApiKey": {}}},
 *     summary="Get a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User data"
 *     )
 * )
 */
Flight::route('GET /user/@id', function($id){ 
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   Flight::json(Flight::userService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/user",
 *     tags={"users"},
 *  security={{"ApiKey": {}}},
 *     summary="Create a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "role"},
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="role", type="string", enum={"admin", "user"}, example="user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully"
 *     )
 * )
 */
Flight::route('POST /user', function(){
         //   Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   $data = Flight::request()->data->getData();
   Flight::json(Flight::userService()->create($data));
});

/**
 * @OA\Put(
 *     path="/user/{id}",
 *     tags={"users"},
 *  security={{"ApiKey": {}}},
 *     summary="Update a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "role"},
 *             @OA\Property(property="username", type="string", example="john_updated"),
 *             @OA\Property(property="email", type="string", format="email", example="john_updated@example.com"),
 *             @OA\Property(property="role", type="string", enum={"admin", "user"}, example="admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     )
 * )
 */
Flight::route('PUT /user/@id', function($id){
           // Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   $data = Flight::request()->data->getData();
   Flight::json(Flight::userService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/user/{id}",
 *     tags={"users"},
 *  security={{"ApiKey": {}}},
 *     summary="Delete a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /user/@id', function($id){
          //  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   Flight::json(Flight::userService()->delete($id));
});
