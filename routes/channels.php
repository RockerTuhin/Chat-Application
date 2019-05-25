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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('lChat', function ($user) {
    return auth()->check();
});
Broadcast::channel('privatechat{receiver_id}', function ($user,$receiver_id) {
    return auth()->check();
});
Broadcast::channel('onlineStatus', function ($user) {
    if(auth()->check()){
    	return $user;
    }
});

