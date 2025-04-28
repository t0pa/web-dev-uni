<?php



Flight::route('GET /comics/@id', function($id){ 
   // Assuming you want to get the library with ID 1 
   Flight::json(Flight::comicsService()->getById($id));
   
});


 Flight::route('GET /comics', function(){
   Flight::json(Flight::comicsService()->getAll());
});


Flight::route('POST /comics', function(){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::comicsService()->create($data));
});



Flight::route('PUT /comics/@id', function($id){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::comicsService()->update($id, $data));
});


/* // Partially update restaurant by ID
Flight::route('PATCH /comics/@id', function($id){
   $data = Flight::request()->data->getData();
   Flight::json(Flight::comicsService()->partial_update_restaurant($id, $data));
});
 */
// Delete restaurant by ID
Flight::route('DELETE /comics/@id', function($id){
   Flight::json(Flight::comicsService()->delete($id));
});
?>
