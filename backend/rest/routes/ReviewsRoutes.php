<?php

/**
 * @OA\Get(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Get all reviews",
 *     @OA\Response(
 *         response=200,
 *         description="List of all reviews"
 *     )
 * )
 */
Flight::route('GET /reviews', function(){
   Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
   Flight::json(Flight::reviewsService()->getAll());
});

/**
 * @OA\Get(
 *     path="/reviews/comic/{comic_id}",
 *     tags={"reviews"},
 *     summary="Get all reviews for a specific comic",
 *     @OA\Parameter(
 *         name="comic_id",
 *         in="path",
 *         required=true,
 *         description="ID of the comic to get reviews for",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reviews for the given comic"
 *     )
 * )
 */
Flight::route('GET /reviews/comic/@comic_id', function($comic_id){ 
      Flight::auth_middleware()->authorizeRoles([Roles::USER]);

    Flight::json(Flight::reviewsService()->get_reviews_for_comic($comic_id));
});

/**
 * @OA\Post(
 *     path="/reviews/comic/{comic_id}",
 *     tags={"reviews"},
 *     summary="Create a review for a comic",
 *     @OA\Parameter(
 *         name="comic_id",
 *         in="path",
 *         required=true,
 *         description="Comic ID",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"rating", "comment"},
 *             @OA\Property(property="rating", type="integer", example=5),
 *             @OA\Property(property="comment", type="string", example="Amazing plot twists!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review created successfully"
 *     )
 * )
 */
Flight::route('POST /reviews/comic/@comic_id', function($comic_id){
         Flight::auth_middleware()->authorizeRoles([Roles::USER]);

    $data = Flight::request()->data->getData();
    $user_id = 1; // Static user ID
    Flight::json(Flight::reviewsService()->create_review($comic_id, $user_id, $data));
});

/**
 * @OA\Put(
 *     path="/reviews/comic/{comic__id}",
 *     tags={"reviews"},
 *     summary="Update a review for a comic",
 *     @OA\Parameter(
 *         name="comic_id",
 *         in="path",
 *         required=true,
 *         description="Comic ID",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"rating", "comment"},
 *             @OA\Property(property="rating", type="integer", example=4),
 *             @OA\Property(property="comment", type="string", example="Great art and pacing!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review updated successfully"
 *     )
 * )
 */
Flight::route('PUT /reviews/comic/@comic_id', function($comic_id){
         Flight::auth_middleware()->authorizeRoles([Roles::USER]);

    $user_id = 1;
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewsService()->update_review($comic_id, $data));
});

/**
 * @OA\Delete(
 *     path="/reviews/comic/{comic_id}",
 *     tags={"reviews"},
 *     summary="Delete the authenticated user's review for a comic",
 *     @OA\Parameter(
 *         name="comic_id",
 *         in="path",
 *         required=true,
 *         description="Comic ID",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized or not review owner"
 *     )
 * )
 */
Flight::route('DELETE /reviews/comic/@comic_id', function($comic_id) {
         Flight::auth_middleware()->authorizeRoles([Roles::USER]);

    $user_id = Flight::get('user')['id']; // Or however you get the authenticated user

    try {
        Flight::json(Flight::reviewsService()->delete_review_by_user_and_comic($comic_id, $user_id));
    } catch (Exception $e) {
        Flight::halt(403, $e->getMessage()); // Forbidden if not allowed
    }
});

