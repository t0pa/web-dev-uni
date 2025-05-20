<?php

/**
 * @OA\Get(
 *     path="/wishlist",
 *     tags={"wishlist"},
 *     summary="Get all wishlist items",
 *     @OA\Response(
 *         response=200,
 *         description="List of all wishlist entries"
 *     )
 * )
 */
Flight::route('GET /wishlist', function(){
            Flight::auth_middleware()->authorizeRoles([Roles::USER]);

   Flight::json(Flight::wishlistService()->getAll());
});

/**
 * @OA\Get(
 *     path="/wishlist/{id}",
 *     tags={"wishlist"},
 *     summary="Get a wishlist entry by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Wishlist entry ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wishlist entry data"
 *     )
 * )
 */
Flight::route('GET /wishlist/@id', function($id){ 
               Flight::auth_middleware()->authorizeRoles([Roles::USER]);

   Flight::json(Flight::wishlistService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/wishlist/{id}",
 *     tags={"wishlist"},
 *     summary="Add a comic to the wishlist (by comic ID)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Comic ID to add to wishlist",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comic added to wishlist"
 *     )
 * )
 */
Flight::route('POST /wishlist/@id', function($id) {
               Flight::auth_middleware()->authorizeRoles([Roles::USER]);

   $user_id = 1;
   $result = Flight::wishlistService()->add_comic_to_wishlist_by_id($id, $user_id);
   Flight::json($result);  
});

/**
 * @OA\Delete(
 *     path="/wishlist/{id}",
 *     tags={"wishlist"},
 *     summary="Remove a comic from the wishlist (by comic ID)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Comic ID to remove from wishlist",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comic removed from wishlist or not found"
 *     )
 * )
 */
Flight::route('DELETE /wishlist/@id', function($id) {
               Flight::auth_middleware()->authorizeRoles([Roles::USER]);

   $user_id = 1;
   $result = Flight::wishlistService()->remove_comic_from_wishlist($id, $user_id);

   if ($result) {
       Flight::json(['status' => 'success', 'message' => 'Comic removed from wishlist']);
   } else {
       Flight::json(['status' => 'error', 'message' => 'Comic not found in wishlist']);
   }
});
