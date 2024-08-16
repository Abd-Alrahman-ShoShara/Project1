<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AttractionController;
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
use App\Http\Controllers\NotificationController;
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
use App\Models\UserPublicTrip;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group( function () {

});

/////////////Authentecation///////////////////////////////////

Route::post('/register',[AuthController::class,'register']);
Route::post('/verifyCode',[AuthController::class,'verifyCode']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forgetPassword',[AuthController::class,'forgetPassword']);
Route::post('/verifyForgetPassword',[AuthController::class,'verifyForgetPassword']);
Route::post('/resatPassword',[AuthController::class,'resatPassword']);
Route::post('/googleRegister',[GoogleUserController::class,'googleRegister']);
Route::post('/choseLanguage',[AuthController::class,'choseLanguage'])->middleware('auth:api');
///////////////////////////////////////////////////////////////////////////////////////

//////////////////////// Dashboard Routs ///////////////////////////////////////////
Route::get('/adminInfo', [AdminController::class, 'adminInfo']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/updateAdmin', [AdminController::class, 'updateAdmin'])->middleware('auth:api');
Route::post('/updateAdminPassword', [AdminController::class, 'updateAdminPassword'])->middleware('auth:api');
Route::post('/logoutAdmin',[AdminController::class,'logoutAdmin'])->middleware('auth:api');

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
Route::get('/getPublicTripInfoWeb/{TripPoint_id}', [PublicTripController::class,'getPublicTripInfoWeb']);
Route::post('/updatePublicTrip/{publicTrip_id}',[PublicTripController::class,'updatePublicTrip']);
Route::post('/deletePublicTrip/{publicTrip_id}',[PublicTripController::class,'deletePublicTrip']);
Route::post('/displayPublicTrip/{publicTrip_id}',[PublicTripController::class,'displayPublicTrip']);
Route::get('/getPublicTrips',[PublicTripController::class,'getPublicTrips']);

Route::post('/addPointsToTrip/{publicTrip_id}',[PublicTripController::class,'addPointsToTrip']);
Route::get('/getPointInfo/{Point_id}',[PublicTripController::class,'getPointInfo']);
Route::post('/updatePoint/{Point_id}',[PublicTripController::class,'updatePoint']);
Route::post('/deletePoint/{Point_id}',[PublicTripController::class,'deletePoint']);
Route::get('/getPublicTripPoints/{TripPoint_id}',[PublicTripController::class,'getPublicTripPoints']);

Route::get('/Classifications',[ClassificationController::class,'Classifications']);
Route::post('/addClassification',[ClassificationController::class,'addClassification']);
Route::post('/deleteClassification/{classification_id}',[ClassificationController::class,'deleteClassification']);

Route::post('/addQuastion',[FAQController::class,'addQuastion']);
Route::get('/getQuastionInfo/{Qusation_id}',[FAQController::class,'getQuastionInfo']);
Route::post('/updateQuastion/{Qusation_id}',[FAQController::class,'updateQuastion']);
Route::post('/deleteQuastion/{Qusation_id}',[FAQController::class,'deleteQuastion']);
Route::get('/allQuastions',[FAQController::class,'allQuastions']);
Route::post('/allQuastionsByType',[FAQController::class,'allQuastionsByType']);

Route::post('/addAttractions',[AttractionController::class,'addAttractions']);
Route::get('/getAttractionInfo/{attraction_id}',[AttractionController::class,'getAttractionInfo']);
Route::post('/updateAttraction/{attraction_id}',[AttractionController::class,'updateAttraction']);
Route::delete('/deleteAttraction/{attraction_id}',[AttractionController::class,'deleteAttraction']);
Route::post('/displayAttraction/{attraction_id}',[AttractionController::class,'displayAttraction']);
Route::get('/allAttractions',[AttractionController::class,'allAttractions']);

Route::post('/addToWallet',[AdminController::class,'addToWallet']);
Route::post('/distroyedAirport/{airport_id}',[AdminController::class,'distroyedAirport']);
///////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////public trip////////////mobile/////////////////////////////////////////////

Route::post('/bookingPublicTrip',[UserPublicTripController::class,'bookingPublicTrip'])->middleware('auth:api');
Route::post('/allPublicTrips',[PublicTripController::class,'allPublicTrips'])->middleware('auth:api');
//getPublicTripInfo --> in wepRoute line()
//getPublicTripPoints -->in wepRoute line()
Route::get('/userPublicTripBooking/{publicTrip_id}',[UserTripController::class,'userPublicTripBooking'])->middleware('auth:api');

//////////////////////////////////////////////////////////////////////////////////

//////////////private trip///////////mobile///////////////////////////////////////

// getAllCities -->in DashboardRoute line()
Route::post('/createTrip',[TripController::class,'createTrip'])->middleware('auth:api');
Route::get('/getAirportFrom/{trip_id}',[AirportController::class,'getAirportFrom']);
Route::get('/getAirportTo/{trip_id}',[AirportController::class,'getAirportTo']);
Route::post('/searchForTicket/{trip_id}',[TicketController::class,'searchForTicket']);
Route::post('/choseTicket/{trip_id}/{ticket_id}',[BookingTicketController::class,'choseTicket']);
Route::get('/cityHotels/{trip_id}',[CitiesHotelController::class,'cityHotels']);
//getRooms -->in DashboardRoute line()
Route::post('/addBookingHotel/{trip_id}',[BookingHotelController::class,'addBookingHotel']);
Route::get('/getTripDays/{trip_id}',[TripDayController::class,'getTripDays']);
Route::post('/getTourismPlaces/{trip_id}',[TourismPlaceController::class,'getTourismPlaces']);
Route::post('/addPlane',[TripDayPlaceController::class,'addPlane']);
Route::get('/getUserPlane/{trip_id}',[TripController::class,'getUserPlane']);
Route::post('/bookingTrip/{trip_id}',[BookingTripeController::class,'bookingTrip']);
Route::get('/searchCity/{nameOfCity}',[CityController::class,'searchCity']);
Route::get('/allTrips',[TripController::class,'allTrips']);
//delete parts
Route::delete('/deleteAllActivities/{trip_id}',[TripDayPlaceController::class,'deleteAllActivities']);

///////////////////////////////////////////////////////////////////////////

///////////////// profile Routs ///////////////////////////////////////

Route::post('/updateName',[AuthController::class,'updateName'])->middleware('auth:api');
Route::post('/updatePhone',[NormalUserController::class,'updatePhone'])->middleware('auth:api');
Route::post('/verifyNewPhone',[NormalUserController::class,'verifyNewPhone'])->middleware('auth:api');
Route::post('/resatPasswordEnternal',[AuthController::class,'resatPasswordEnternal'])->middleware('auth:api');
Route::post('/addReview',[ReviewController::class,'addReview'])->middleware('auth:api');
Route::get('/allReview',[ReviewController::class,'allReview']);
Route::delete('/deleteAccount',[AuthController::class,'deleteAccount'])->middleware('auth:api');
Route::get('/userInfo',[AuthController::class,'userInfo'])->middleware('auth:api');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:api');
//allQuastions -->in DashboardRoute line()
//allQuastionsByType -->in DashboardRoute line()

///////////////////////////////////////////////////////////////////////////

////////////////////////////////my trips /////////////////////////////////

//update ticket:
Route::post('/updateTicket/{bookingTicket_id}',[BookingTicketController::class,'updateTicket']);
Route::post('/updateBookingTicket/{trip_id}/{ticket_id}',[BookingTicketController::class,'updateBookingTicket']);
Route::delete('/deleteTicket/{bookingTicket_id}',[BookingTicketController::class,'deleteTicket']);
//update booking hotel:
Route::post('/updateBookingHotel/{trip_id}',[BookingHotelController::class,'updateBookingHotel']);
Route::delete('/deleteBookingHotel/{trip_id}/{citiesHotel_id}',[BookingHotelController::class,'deleteBookingHotel']);
Route::delete('/deleteBookingRoom/{boolingHotel_id}',[BookingHotelController::class,'deleteBookingRoom']);
//update activities:
Route::delete('/deleteActivities/{tripDay_id}',[TripDayPlaceController::class,'deleteActivities']);
//get trips with state:
Route::get('/favorite',[FavoriteController::class,'favorite'])->middleware('auth:api');
Route::get('/pastTrips',[UserTripController::class,'pastTrips'])->middleware('auth:api');
Route::get('/getPastUserPublicTrip/{publicTrip_id}',[UserTripController::class,'getPastUserPublicTrip'])->middleware('auth:api');
Route::get('/activeTrips',[UserTripController::class,'activeTrips'])->middleware('auth:api');
Route::get('/getActiveUserPublicTrip/{publicTrip_id}',[UserTripController::class,'getActiveUserPublicTrip'])->middleware('auth:api');
Route::get('/getCancelledTrip',[TripController::class,'getCancelledTrip'])->middleware('auth:api');
Route::get('/getCancelledUserPublicTrip/{publicTrip_id}',[UserTripController::class,'getCancelledUserPublicTrip'])->middleware('auth:api');
Route::get('/getUnderConstructionTrip',[TripController::class,'getUnderConstructionTrip'])->middleware('auth:api');
//cancel and fav:
Route::post('/faveOrNot/{publicTrip_id}',[FavoriteController::class,'faveOrNot'])->middleware('auth:api');
Route::post('/cancelPublicTrip/{publicTrip_id}',[UserPublicTripController::class,'cancelPublicTrip']);
Route::post('/cancelePrivateTripe/{trip_id}',[TripController::class,'cancelePrivateTripe']);

///////////////////////////////////////////////////////////////////

//search:
Route::get('/searchPublicTrip/{nameOfPublicTrip}',[PublicTripController::class,'searchPublicTrip'])->middleware('auth:api');
Route::post('/searchTourismPlaces/{trip_id}',[TourismPlaceController::class,'searchTourismPlaces']);
//wep
Route::get('/searchActivity/{nameOfCity}',[TourismPlaceController::class,'searchActivity']);

//sortBy:
Route::post('/publicTripSortBy',[PublicTripController::class,'publicTripSortBy'])->middleware('auth:api');
Route::post('/cityHotelsSortBy/{trip_id}',[CitiesHotelController::class,'cityHotelsSortBy']);

//attractions:
Route::get('/getAttractions',[AttractionController::class,'getAttractions']);
Route::get('/PublicTripAttraction/{publicTrip_id}',[AttractionController::class,'PublicTripAttraction']);
////////////////////////////////////////////////////////////////////////////////////////Attractions

//Route::post('/addPublicTripDiscount/{publicTrip_id}',[PublicTripController::class,'addPublicTripDiscount']);

Route::post('/restoreMoneyPublic/{userPublicTrip_id}',[PublicTripController::class,'restoreMoneyPublic']);

Route::get('/getAllNotifications',[NotificationController::class,'getAllNotifications'])->middleware('auth:api');
