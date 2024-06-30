<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingHotelController;
use App\Http\Controllers\BookingTicketController;
use App\Http\Controllers\BookingTripeController;
use App\Http\Controllers\CitiesHotelController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GoogleUserController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\NormalUserController;
use App\Http\Controllers\PublicTripController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoomHotelController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TourismPlaceController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripDayController;
use App\Http\Controllers\TripDayPlaceController;
use App\Http\Controllers\UserPublicTripController;
use App\Http\Controllers\UserTripController;
use App\Models\Classification;
use App\Models\Favorite;
use Illuminate\Support\Facades\Route;





Route::middleware('auth:api')->group( function () {

    Route::post('/createTrip',[TripController::class,'createTrip']);

});


Route::get('/adminInfo', [AdminController::class, 'adminInfo']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/updateAdmin', [AdminController::class, 'updateAdmin'])->middleware('auth:api');
Route::post('/updateAdminPassword', [AdminController::class, 'updateAdminPassword'])->middleware('auth:api');
Route::post('/logoutAdmin',[AdminController::class,'logoutAdmin'])->middleware('auth:api');



Route::post('/googleRegister',[GoogleUserController::class,'googleRegister']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/verifyCode',[AuthController::class,'verifyCode']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forgetPassword',[AuthController::class,'forgetPassword']);
Route::post('/verifyForgetPassword',[AuthController::class,'verifyForgetPassword']);
Route::post('/resatPassword',[AuthController::class,'resatPassword']);


///////////////////////////////////////////////// profile Routs ///////////////////////////////////////////

Route::post('/updateName',[AuthController::class,'updateName'])->middleware('auth:api');
Route::post('/updatePhone',[NormalUserController::class,'updatePhone'])->middleware('auth:api');
Route::post('/verifyNewPhone',[NormalUserController::class,'verifyNewPhone'])->middleware('auth:api');
Route::post('/resatPasswordEnternal',[AuthController::class,'resatPasswordEnternal'])->middleware('auth:api');


Route::post('/addReview',[ReviewController::class,'addReview'])->middleware('auth:api');
Route::get('/allReview',[ReviewController::class,'allReview']);
Route::get('/userInfo',[AuthController::class,'userInfo'])->middleware('auth:api');


Route::delete('/deleteAccount',[AuthController::class,'deleteAccount'])->middleware('auth:api');

Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:api');

////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////// Dashboard Routs ///////////////////////////////////////////


Route::post('/addCity',[CityController::class,'addCity']);
Route::get('/getCityInfo/{city_id}',[CityController::class,'getCityInfo']);
Route::post('/updateCity/{city_id}',[CityController::class,'updateCity']);
Route::post('/deleteCity/{city_id}',[CityController::class,'deleteCity']);
Route::get('/allCities',[CityController::class,'allCities']);


Route::post('/addAirPort',[AirportController::class,'addAirPort']);
Route::get('/getAirportInfo/{airport_id}',[AirportController::class,'getAirportInfo']);
Route::post('/updateAirport/{airport_id}',[AirportController::class,'updateAirport']);
Route::post('/deleteAirport/{airport_id}',[AirportController::class,'deleteAirport']);
Route::get('/allAirports',[AirportController::class,'allAirports']);

Route::post('/addAirLine',[AirlineController::class,'addAirLine']);
Route::get('/getAirlineInfo/{airline_id}',[AirlineController::class,'getAirlineInfo']);
Route::post('/updateAirline/{airline_id}',[AirlineController::class,'updateAirline']);
Route::post('/deleteAirline/{airline_id}',[AirlineController::class,'deleteAirline']);
Route::get('/allAirlines',[AirlineController::class,'allAirlines']);


Route::post('/addHotel',[HotelController::class,'addHotel']);
Route::get('/getHotelInfo/{hotel_id}',[HotelController::class,'getHotelInfo']);
Route::post('/updateHotel/{hotel_id}',[HotelController::class,'updateHotel']);
Route::post('/deleteHotel/{hotel_id}',[HotelController::class,'deleteHotel']);
Route::get('/allHotel',[HotelController::class,'allHotel']);


Route::post('/addCitiesHotel',[CitiesHotelController::class,'addCitiesHotel']);
Route::get('/getCitiesHotelInfo/{citiesHotel_id}',[CitiesHotelController::class,'getCitiesHotelInfo']);
Route::post('/updateCitiesHotel/{citiesHotel_id}',[CitiesHotelController::class,'updateCitiesHotel']);
Route::post('/deleteCitiesHotel/{citieshotel_id}',[CitiesHotelController::class,'deleteCitiesHotel']);
Route::get('/allCitiesHotel',[CitiesHotelController::class,'allCitiesHotel']);

Route::post('/addRoomsHotel/{citiesHotel_id}',[RoomHotelController::class,'addRoomsHotel']);
Route::get('/getRoomHotelInfo/{roomHotel_id}',[RoomHotelController::class,'getRoomHotelInfo']);
Route::post('/updateRoomHotel/{roomHotel_id}',[RoomHotelController::class,'updateRoomHotel']);
Route::post('/deleteRoomHotel/{roomHotel_id}',[RoomHotelController::class,'deleteRoomHotel']);
Route::get('/getRooms/{citiesHotel_id}',[RoomHotelController::class,'getRooms']);

Route::post('/addTourismPlace/{city_id}',[TourismPlaceController::class,'addTourismPlace']);
Route::get('/getTourismPlaceInfo/{tourismPlace_id}',[TourismPlaceController::class,'getTourismPlaceInfo']);
Route::post('/updateTourismPlace/{tourismPlace_id}',[TourismPlaceController::class,'updateTourismPlace']);
Route::post('/deleteTourismPlace/{tourismPlace_id}',[TourismPlaceController::class,'deleteTourismPlace']);
Route::get('/getTourismPlacesWep/{city_id}',[TourismPlaceController::class,'getTourismPlacesWep']);

Route::post('/addPublicTrip',[PublicTripController::class,'addPublicTrip']);
Route::get('/getPublicTripInfo/{TripPoint_id}', [PublicTripController::class,'getPublicTripInfo']);
Route::post('/updatePublicTrip/{publicTrip_id}',[PublicTripController::class,'updatePublicTrip']);
Route::post('/deletePublicTrip/{publicTrip_id}',[PublicTripController::class,'deletePublicTrip']);
Route::get('/getPublicTrips',[PublicTripController::class,'getPublicTrips']);

Route::post('/addPointsToTrip/{publicTrip_id}',[PublicTripController::class,'addPointsToTrip']);
Route::get('/getPointInfo/{Point_id}',[PublicTripController::class,'getPointInfo']);
Route::post('/updatePoint/{Point_id}',[PublicTripController::class,'updatePoint']);
Route::post('/deletePoint/{Point_id}',[PublicTripController::class,'deletePoint']);
Route::get('/getPublicTripPoints/{TripPoint_id}',[PublicTripController::class,'getPublicTripPoints']);

Route::post('/addQuastion',[FAQController::class,'addQuastion']);
Route::get('/getQuastionInfo/{Qusation_id}',[FAQController::class,'getQuastionInfo']);
Route::post('/updateQuastion/{Qusation_id}',[FAQController::class,'updateQuastion']);
Route::post('/deleteQuastion/{Qusation_id}',[FAQController::class,'deleteQuastion']);
Route::get('/allQuastions',[FAQController::class,'allQuastions']);
Route::post('/allQuastionsByType',[FAQController::class,'allQuastionsByType']);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/allPublicTrips',[PublicTripController::class,'allPublicTrips']);







Route::post('/searchForTicket/{trip_id}',[TicketController::class,'searchForTicket']);

Route::get('/getAirportFrom/{trip_id}',[AirportController::class,'getAirportFrom']);

Route::get('/getAirportTo/{trip_id}',[AirportController::class,'getAirportTo']);

Route::post('/choseTicket/{trip_id}/{ticket_id}',[BookingTicketController::class,'choseTicket']);


Route::post('/addBookingHotel/{trip_id}',[BookingHotelController::class,'addBookingHotel']);

Route::post('/addPlane',[TripDayPlaceController::class,'addPlane']);



Route::post('/getTourismPlaces/{trip_id}',[TourismPlaceController::class,'getTourismPlaces']);

Route::get('/getTripDays/{trip_id}',[TripDayController::class,'getTripDays']);
Route::get('/getUserPlane/{trip_id}',[TripController::class,'getUserPlane']);





Route::get('/cityHotels/{trip_id}',[CitiesHotelController::class,'cityHotels']);


Route::post('/bookingTrip/{trip_id}',[BookingTripeController::class,'bookingTrip']);

Route::get('/searchCity/{nameOfCity}',[CityController::class,'searchCity']);

Route::get('/allTrips',[TripController::class,'allTrips']);

////////////////////////////////////////////////////////////////  public trip

Route::get('/Classifications',[ClassificationController::class,'Classifications']);
Route::post('/addClassification',[ClassificationController::class,'addClassification']);
Route::post('/deleteClassification/{classification_id}',[ClassificationController::class,'deleteClassification']);





Route::post('/bookingPublicTrip',[UserPublicTripController::class,'bookingPublicTrip'])->middleware('auth:api');

////////////////////////////////////////////my trips /////////////

Route::get('/activeTrips',[UserTripController::class,'activeTrips'])->middleware('auth:api');
Route::get('/pastTrips',[UserTripController::class,'pastTrips'])->middleware('auth:api');
Route::get('/favorite',[FavoriteController::class,'favorite'])->middleware('auth:api');

Route::post('/faveOrNot/{publicTrip_id}',[FavoriteController::class,'faveOrNot'])->middleware('auth:api');

Route::post('/updateTicket/{bookingTicket_id}',[BookingTicketController::class,'updateTicket']);
Route::post('/updateBookingTicket/{trip_id}/{ticket_id}',[BookingTicketController::class,'updateBookingTicket']);
Route::delete('/deleteTicket/{bookingTicket_id}',[BookingTicketController::class,'deleteTicket']);

Route::post('/updateBookingHotel/{trip_id}',[BookingHotelController::class,'updateBookingHotel']);