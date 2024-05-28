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
use App\Http\Controllers\GoogleUserController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomHotelController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TourismPlaceController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripDayPlaceController;
use App\Models\BookingTripe;
use App\Models\CitiesHotel;
use App\Models\TripDay;
use Illuminate\Support\Facades\Route;





Route::middleware('auth:api')->group( function () {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/logoutAdmin',[AdminController::class,'logoutAdmin']);

    Route::post('/createTrip',[TripController::class,'createTrip']);
    
    Route::post('/resatPasswordEnternal',[AuthController::class,'resatPasswordEnternal']);
});


Route::post('/register',[AuthController::class,'register']);

Route::post('/googleRegister',[GoogleUserController::class,'googleRegister']);

Route::post('/admin/login', [AdminController::class, 'login']);

Route::post('/updateAdmin', [AdminController::class, 'updateAdmin'])->middleware('auth:api');

Route::post('/updateAdminPassword', [AdminController::class, 'updateAdminPassword'])->middleware('auth:api');

Route::post('/verifyCode',[AuthController::class,'verifyCode']);

Route::post('/login',[AuthController::class,'login']);

Route::post('/forgetPassword',[AuthController::class,'forgetPassword']);

Route::post('/verifyForgetPassword',[AuthController::class,'verifyForgetPassword']);

Route::post('/resatPassword',[AuthController::class,'resatPassword']);

Route::post('/addCity',[CityController::class,'addCity']);

Route::post('/addAirPort',[AirportController::class,'addAirPort']);

Route::post('/addAirLine',[AirlineController::class,'addAirLine']);

Route::post('/searchForTicket/{trip_id}',[TicketController::class,'searchForTicket']);

Route::get('/allAirlines',[AirlineController::class,'allAirlines']);

Route::get('/allAirports',[AirportController::class,'allAirports']);

Route::get('/getAirportFrom/{trip_id}',[AirportController::class,'getAirportFrom']);

Route::get('/getAirportTo/{trip_id}',[AirportController::class,'getAirportTo']);

Route::post('/choseTicket/{trip_id}/{ticket_id}',[BookingTicketController::class,'choseTicket']);

Route::post('/addHotel',[HotelController::class,'addHotel']);

Route::post('/addCitiesHotel',[CitiesHotelController::class,'addCitiesHotel']);

Route::post('/addRoomsHotel/{citiesHotel_id}',[RoomHotelController::class,'addRoomsHotel']);

Route::post('/addBookingHotel/{trip_id}',[BookingHotelController::class,'addBookingHotel']);

Route::post('/addPlane',[TripDayPlaceController::class,'addPlane']);

Route::post('/addTourismPlaces/{city_id}',[TourismPlaceController::class,'addTourismPlaces']);

Route::get('/getTourismPlacesWep/{city_id}',[TourismPlaceController::class,'getTourismPlacesWep']);

Route::post('/getTourismPlaces/{trip_id}',[TourismPlaceController::class,'getTourismPlaces']);

Route::get('/getUserPlane/{trip_id}',[TripController::class,'getUserPlane']);

Route::get('/allCities',[CityController::class,'allCities']);

Route::get('/allCitiesHotel',[HotelController::class,'allCitiesHotel']);

Route::get('/allHotel',[HotelController::class,'allHotel']);

Route::get('/cityHotels/{trip_id}',[CitiesHotelController::class,'cityHotels']);

Route::post('/bookingTrip/{trip_id}',[BookingTripeController::class,'bookingTrip']);

Route::get('/searchCity/{nameOfCity}',[CityController::class,'searchCity']);

