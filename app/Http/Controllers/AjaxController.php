<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Auth;

use App\Tile;
use App\Filter;
use App\SurfaceType;
use App\RoomType;
use App\User;
use App\Savedroom;
use App\CustomTile;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('ajax');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function getTiles() {
        if (config('app.tiles_access_level')) {
            $tiles = Tile::where('enabled', 1)
                ->where(function ($query) {
                    $user = Auth::user();
                    $access_level = isset($user) ? $user->getAccessLevel() : 0;

                    $query->where('access_level', '<=', $access_level)
                        ->orWhere('access_level', null);
                })
                ->get();
                return response()->json($tiles);
        }

        $tiles = Tile::where('enabled', 1)->get();
        return response()->json($tiles);
    }

    public function getTile($id) {
        $tile = Tile::findOrFail($id);
        return response()->json($tile);
    }

    public function getFilters() {
        $filters = Filter::where('enabled', 1)->get();
        return response()->json($filters);
    }

    public function getFilter($id) {
        $filter = Filter::findOrFail($id);
        return response()->json($filter);
    }

    public function getSurfaceType($id) {
        $types = SurfaceType::findOrFail($id);
        return response()->json($types);
    }

    public function getSurfaceTypes() {
        $types = SurfaceType::get();
        return response()->json($types);
    }

    public function getRoomType($id) {
        $types = RoomType::findOrFail($id);
        return response()->json($types);
    }

    public function getUser($id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function getSavedRoomByUrl($url) {
        $savedroom = Savedroom::where('url', $url)->first();
        return response()->json($savedroom);
    }

    public static function saveNewUserRoom($request) {
        $savedroom = new Savedroom;

        $user = Auth::user();
        if (isset($user)) {
            $savedroom->userid = $user->id;
        } else {
            $savedroom->session_token = session('_token');
        }

        $savedroom->roomid = $request->roomId;
        $savedroom->engine = $request->engine;
        $savedroom->roomsettings = $request->roomSettings;
        $savedroom->note = $request->note;
        $savedroom->enabled = 1;
        $savedroom->save();

        $url = md5($savedroom->id);
        $savedroom->url = $url;

        if (isset($request->image)) {
            $image = $request->image;
            list($type, $image) = explode(';base64,', $image, 2);
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);
            if (isset($image)) {
                $fileName = 'savedrooms/' . $url . '.png';
                $savedroom->image = $fileName;
                Storage::disk('public')->put($fileName, $image);
            }
        }
        $savedroom->save();

        $user = Auth::user();
        $loggedIn = 0;
        if (isset($user)) {
            $loggedIn = 1;
        }
        return response()->json([
            'state' => 'success',
            'url' => $savedroom->url,
            'fullUrl' => '/room/url/' . $savedroom->url,
            'loggedIn' => $loggedIn
        ]);
    }

    public static function updateUserRoom($request, &$savedroom) {
        $savedroom->roomsettings = $request->roomSettings;
        $savedroom->note = $request->note;
        if (isset($request->image)) {
            $image = $request->image;
            list($type, $image) = explode(';base64,', $image, 2);
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);
            if (isset($image)) {
                $fileName = 'savedrooms/' . $request->url . '.png';
                $savedroom->image = $fileName;
                Storage::disk('public')->put($fileName, $image);
            }
        }
        $savedroom->save();

        $user = Auth::user();
        $loggedIn = 0;
        if (isset($user)) {
            $loggedIn = 1;
        }
        return response()->json([
            'state' => 'success',
            'url' => $savedroom->url,
            'fullUrl' => '/room/url/' . $savedroom->url,
            'loggedIn' => $loggedIn
        ]);
    }

    public function saveUserRoom(Request $request) {
        $validator = Validator::make($request->all(), [
            'roomId' => 'required|integer',
            'image' => 'nullable|string',
            'engine' => 'nullable|string|max:16',
            'note' => 'nullable|string',
            'url' => 'nullable|max:1000|alpha_num|exists:savedrooms,url',
            'roomSettings' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['state' => 'Validation error']);
        }

        $token = session('_token');

        $user = Auth::user();

        if (isset($request->url)) {
            $savedroom = Savedroom::where('url', $request->url)->first();
            if (isset($user)) {
                // Savedroom::where('session_token', $token)->update(['userid' => $user->id, 'session_token' => null]);

                if (isset($savedroom->userid)) {
                    if ($savedroom->userid == $user->id) {
                        return AjaxController::updateUserRoom($request, $savedroom);
                    } else {
                        return AjaxController::saveNewUserRoom($request);
                    }
                } else {
                    if (isset($savedroom->session_token) && $savedroom->session_token == $token) {
                        $savedroom->userid = $user->id;
                        return AjaxController::updateUserRoom($request, $savedroom);
                    } else {
                        return AjaxController::saveNewUserRoom($request);
                    }
                }
            } else {
                if (isset($savedroom->session_token) && $savedroom->session_token == $token) {
                    return AjaxController::updateUserRoom($request, $savedroom);
                } else {
                    return AjaxController::saveNewUserRoom($request);
                }
            }
        } else {
            return AjaxController::saveNewUserRoom($request);
        }

        return response()->json(['state' => 'error']); // Unreachable response
    }
}
