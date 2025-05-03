<?php

/**
 * @OA\Get(
 *     path="/library",
 *     tags={"library"},
 *     summary="Get all library entries",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all user-comic library entries"
 *     )
 * )
 */
Flight::route('GET /library', function(){
   Flight::json(Flight::libraryService()->getAll());
});

/**
 * @OA\Get(
 *     path="/library/{id}",
 *     tags={"library"},
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
   Flight::json(Flight::libraryService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/library/{id}",
 *     tags={"library"},
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
   $user_id = 1;
   $result = Flight::libraryService()->add_comic_to_library_by_id($id, $user_id);
   Flight::json($result);  
});

/**
 * @OA\Delete(
 *     path="/library/{id}",
 *     tags={"library"},
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
   $user_id = 1;
   $result = Flight::libraryService()->remove_comic_from_library($id, $user_id);

   if ($result) {
       Flight::json(['status' => 'success', 'message' => 'Comic removed from library']);
   } else {
       Flight::json(['status' => 'error', 'message' => 'Comic not found in library']);
   }
});
