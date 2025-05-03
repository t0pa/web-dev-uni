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
   Flight::json(Flight::reviewsService()->getAll());
});

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Get a specific review by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review details"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function($id){ 
   Flight::json(Flight::reviewsService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Create a review for a comic",
 *     @OA\Parameter(
 *         name="id",
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
Flight::route('POST /reviews/@id', function($comic_id){
    $data = Flight::request()->data->getData();
    $user_id = 1; // Static user ID
    Flight::json(Flight::reviewsService()->create_review($comic_id, $user_id, $data));
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Update a review for a comic",
 *     @OA\Parameter(
 *         name="id",
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
Flight::route('PUT /reviews/@id', function($comic_id){
    $user_id = 1;
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewsService()->update($comic_id, $data));
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Delete a review by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function($id){
   Flight::json(Flight::reviewsService()->delete($id));
});
