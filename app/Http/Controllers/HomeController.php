<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Savedroom;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();

        $saved_rooms = Savedroom::getUserSavedRooms($user_id);
        // $saved_rooms = Savedroom::where('userid', $user_id)->where('enabled', 1)->orderBy('updated_at', 'desc')->paginate(20);
        // $saved_rooms = Savedroom::where('userid', $user_id)->orderBy('updated_at', 'desc')->paginate(20);
        return view('home', ['savedRooms' => $saved_rooms]);
    }
}
