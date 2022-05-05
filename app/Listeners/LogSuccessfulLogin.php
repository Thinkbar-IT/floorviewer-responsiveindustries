<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $token = session('_token');
        App\Savedroom::where('session_token', $token)->update(['userid' => $event->user->id, 'session_token' => null]);

        if (config('app.tiles_designer')) {
            App\CustomTile::where('session_token', $token)->update(['user_id' => $event->user->id, 'session_token' => null]);
        }
    }
}
