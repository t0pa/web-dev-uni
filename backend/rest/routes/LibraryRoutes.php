
<?php


/**
 * @OA\Get(
 *     path="/library/user",
 *     tags={"library"},
 *     security={{"ApiKey": {}}},
 *     summary="Get all comics in the current user's library",
 *     @OA\Response(
 *         response=200,
 *         description="List of comic details in the user's library"
 *     )
 * )
 */
Flight::route('GET /library/user', function () {
 
     Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $user = Flight::get('user'); // Assumes user is authenticated
    $user_id = $user->id;  
    Flight::json(Flight::libraryService()->getUserLibraryComics($user_id));
    
});

/**
 * @OA\Get(
 *     path="/library",
 *     tags={"library"},
 *    security={{"ApiKey": {}}},
 *     summary="Get all library entries",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all user-comic library entries"
 *     )
 * )
 */
Flight::route('GET /library', function(){
   Flight::auth_middleware()->authorizeRoles([Roles::USER , Roles::ADMIN]);
   
   Flight::json(Flight::libraryService()->getAll());
});

/**
 * @OA\Get(
 *     path="/library/{id}",
 *     tags={"library"},
 *    security={{"ApiKey": {}}},
 *     summary="Get a specific library entry by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Library entry ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Library entry details"
 *     )
 * )
 */
     Flight::route('GET /library/@id', function($id){ 
      Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);

   Flight::json(Flight::libraryService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/library/{id}",
 *     tags={"library"},
 *   security={{"ApiKey": {}}},
 *     summary="Add a comic to the library by comic ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Comic ID",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comic added to user's library"
 *     )
 * )
 */
Flight::route('POST /library/@id', function($id) {
      Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
$user = Flight::get('user'); // Assumes user is authenticated
    $user_id = $user->id; 
   $result = Flight::libraryService()->add_comic_to_library_by_id($id, $user_id);
   Flight::json($result);  
});

/**
 * @OA\Delete(
 *     path="/library/{id}",
 *     tags={"library"},
 *   security={{"ApiKey": {}}},
 *     summary="Remove a comic from the library by comic ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Comic ID to remove",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comic removed from user's library"
 *     )
 * )
 */
Flight::route('DELETE /library/@id', function($id) {
      Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
$user = Flight::get('user'); // Assumes user is authenticated
    $user_id = $user->id; 
   $result = Flight::libraryService()->remove_comic_from_library($id, $user_id);

   if ($result) {
       Flight::json(['status' => 'success', 'message' => 'Comic removed from library']);
   } else {
       Flight::json(['status' => 'error', 'message' => 'Comic not found in library']);
   }
});




