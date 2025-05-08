<?php

/**
 * @OA\Get(
 *     path="/user",
 *     tags={"users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="List of all users"
 *     )
 * )
 */
Flight::route('GET /user', function(){
   Flight::json(Flight::userService()->getAll());
});

/**
 * @OA\Get(
 *     path="/user/{id}",
 *     tags={"users"},
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
   Flight::json(Flight::userService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/user",
 *     tags={"users"},
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
   $data = Flight::request()->data->getData();
   Flight::json(Flight::userService()->create($data));
});

/**
 * @OA\Put(
 *     path="/user/{id}",
 *     tags={"users"},
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
   $data = Flight::request()->data->getData();
   Flight::json(Flight::userService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/user/{id}",
 *     tags={"users"},
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
   Flight::json(Flight::userService()->delete($id));
});
