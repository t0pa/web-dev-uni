<?php
// Get a specific library by ID
Flight::route('GET /library/@id', function($id){ 
   // Assuming you want to get the library with ID 1 
   Flight::json(Flight::libraryService()->getById($id));
   
});


// Get books 
 Flight::route('GET /library', function(){
   Flight::json(Flight::libraryService()->getAll());
});

//add comic to library
Flight::route('POST /library', function(){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::libraryService()->add_comic_to_library($data));
});

/*
// Update restaurant by ID
Flight::route('PUT /restaurant/@id', function($id){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::restaurantService()->update_restaurant($id, $data));
});
// Partially update restaurant by ID
Flight::route('PATCH /restaurant/@id', function($id){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::restaurantService()->partial_update_restaurant($id, $data));
});
// Delete restaurant by ID
Flight::route('DELETE /restaurant/@id', function($id){
   Flight::json(Flight::restaurantService()->delete_restaurant($id));
}); */
?>
