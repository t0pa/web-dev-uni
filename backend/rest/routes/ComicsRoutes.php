<?php

/**
 * @OA\Get(
 *      path="/comics",
 *      tags={"comics"},
 *       security={{"ApiKey": {}}},
 *      summary="Get all comics",
 *      @OA\Response(
 *           response=200,
 *           description="Array of all comics in the database"
 *      )
 * )
 */
Flight::route('GET /comics', function(){
   Flight::json(Flight::comicsService()->getAll());
});

/**
 * @OA\Get(
 *     path="/comics/{id}",
 *     tags={"comics"},
 * security={{"ApiKey": {}}},
 *     summary="Get comic by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the comic",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the comic with the given ID"
 *     )
 * )
 */
Flight::route('GET /comics/@id', function($id){
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

   Flight::json(Flight::comicsService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/comics",
 *     tags={"comics"},
 *    security={{"ApiKey": {}}},
 *     summary="Add a new comic",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "author", "genre", "total_chapters", "cover_image"},
 *             @OA\Property(property="title", type="string", example="Deadpool"),
 *             @OA\Property(property="author", type="string", example="Stan Lee"),
 *             @OA\Property(property="genre", type="string", example="Superhero"),
 *             @OA\Property(property="total_chapters", type="integer", example=1500),
 *             @OA\Property(property="cover_image", type="string", example="deadpool.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New comic created"
 *     )
 * )
 */
Flight::route('POST /comics', function(){
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   $data = Flight::request()->data->getData();
   Flight::json(Flight::comicsService()->create($data));
});

/**
 * @OA\Put(
 *     path="/comics/{id}",
 *     tags={"comics"},
 *   security={{"ApiKey": {}}},
 *     summary="Update a comic fully or partially by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Comic ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "author", "genre", "total_chapters", "cover_image"},
 *             @OA\Property(property="title", type="string", example="Deadpool"),
 *             @OA\Property(property="author", type="string", example="Stan Lee"),
 *             @OA\Property(property="genre", type="string", example="Superhero"),
 *             @OA\Property(property="total_chapters", type="integer", example=1500),
 *             @OA\Property(property="cover_image", type="string", example="deadpool.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comic updated"
 *     )
 * )
 */
Flight::route('PUT /comics/@id', function($id){
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   $data = Flight::request()->data->getData();
   Flight::json(Flight::comicsService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/comics/{id}",
 *     tags={"comics"},
 *  security={{"ApiKey": {}}},
 *     summary="Delete a comic by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Comic ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comic deleted"
 *     )
 * )
 */
Flight::route('DELETE /comics/@id', function($id){
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);

   Flight::json(Flight::comicsService()->delete($id));
});

?>
