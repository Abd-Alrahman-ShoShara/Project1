<?php

use App\Events\NotificationSent;
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
    event(new NotificationSent($name,'','kk'));

    return view('userRegisteration');
});
