<?php

<<<<<<< HEAD
=======
use App\Events\NotificationSent;
use App\Http\Controllers\NotificationController;
>>>>>>> fd1582b (j)
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

<<<<<<< HEAD
Route::get('/', function () {
    return view('welcome');
=======

Route::get('/sendNotification',[NotificationController::class,'sendNotification']);


Route::get('/', function () {
    return view('welcome');
    // $tr = new GoogleTranslate('en');
    // return $tr->setSource('en')->setTarget('fr')->translate('hello world');
});

Route::get('/userRegisteration', function () {
    return view('userRegisteration');
});

Route::post('/userRegisteration', function () {

    $name=request()->name;
    event(new NotificationSent($name,''));

    return view('userRegisteration');
>>>>>>> fd1582b (j)
});
