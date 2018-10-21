<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\FamilyCreated;
use App\Events\NewMessage;
use App\Events\TaskCreated;
use App\Family;
use App\Message;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('families',function (){

    return Family::pluck('name');
});

Route::post('families',function (){

    $family = Family::create(array_merge(request(['name']),['user_id' => auth()->id()]));

    FamilyCreated::dispatch($family);
});

Route::get('families/{family}/tasks',function (Family $family){

    return $family->tasks()->pluck('body');
});

Route::post('families/{family}/tasks',function (Family $family){

    $task = $family->tasks()->create(request(['body']));

    TaskCreated::dispatch($task);
});

Route::get('tasks/{family}',function (Family $family){

   return view('tasks',compact('family'));
});

Route::get('families/{family}',function (Family $family){

    return view('families',compact('family'));
});

Route::get('messages',function (){

   return Message::all()->load('user');

});

Route::post('messages',function (){

    $message = Message::create(request(['body','user_id']));

    NewMessage::dispatch($message->load('user'));

    return $message->load('user');
});

Route::delete('messages',function (){

    Message::truncate();

});
