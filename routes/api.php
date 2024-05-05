<?php

use App\Http\Controllers\AirlineController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingHotelController;
use App\Http\Controllers\BookingTicketController;
use App\Http\Controllers\CitiesHotelController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomHotelController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TripController;
use App\Models\CitiesHotel;
use Illuminate\Support\Facades\Route;





Route::middleware('auth:api')->group( function () {
    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/createTrip',[TripController::class,'createTrip']);
    
    Route::post('/resatPasswordEnternal',[AuthController::class,'resatPasswordEnternal']);
});


Route::post('/register',[AuthController::class,'register']);

Route::post('/verifyCode',[AuthController::class,'verifyCode']);

Route::post('/login',[AuthController::class,'login']);

Route::post('/forgetPassword',[AuthController::class,'forgetPassword']);

Route::post('/verifyForgetPassword',[AuthController::class,'verifyForgetPassword']);

Route::post('/resatPassword',[AuthController::class,'resatPassword']);

Route::post('/addCity',[CityController::class,'addCity']);

Route::post('/addAirPort',[AirportController::class,'addAirPort']);

Route::post('/addAirLine',[AirlineController::class,'addAirLine']);

Route::post('/searchForTicket/{trip_id}',[TicketController::class,'searchForTicket']);

Route::get('/getAirportFrom/{Trip_id}',[AirportController::class,'getAirportFrom']);

Route::get('/getAirportTo/{Trip_id}',[AirportController::class,'getAirportTo']);

Route::post('/choseTicket/{trip_id}/{ticket_id}',[BookingTicketController::class,'choseTicket']);

Route::post('/addHotel',[HotelController::class,'addHotel']);

Route::post('/addCitiesHotel',[CitiesHotelController::class,'addCitiesHotel']);

Route::post('/addRoomsHotel/{citiesHotel_id}',[RoomHotelController::class,'addRoomsHotel']);

Route::post('/addBookingHotel/{trip_id}',[BookingHotelController::class,'addBookingHotel']);