<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Family;

Broadcast::channel('tasks.{family}', function ($user, Family $family) {

    return in_array($user->id,$family->allows()->pluck('user_id')->toArray());

});

Broadcast::channel('Messages', function ($user) {

    return ['name' => $user->name];

});