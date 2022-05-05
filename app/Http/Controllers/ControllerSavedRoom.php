<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\RoomType;
use App\Room2d;
use App\Savedroom;
use App\ExternalToken;

class ControllerSavedRoom extends Controller
{

    public function getUserRooms(Request $request) {
        $user_external_token = $request->extt;

        $token = ExternalToken::where('token', $user_external_token)->first();

        if (isset($token)) {
            $rooms = Savedroom::getUserRoomsShort($token->user->id);

            $room_data = [];
            foreach ($rooms as $room) {
                array_push($room_data, [
                    'url' => $room->url,
                    'thumb' => 'http://' . $request->getHost() . $room->image,
                    // 'directLink' => 'http://' . $request->getHost() . '/room/url/' . $room->url,
                    // 'image' => '',
                ]);
            }

            return response()->json([
                'respose' => 0,
                'message' => 'OK',
                'data' => $room_data,
            ]);
        }

        return response()->json([
            'respose' => -1,
            'message' => 'User with token "' . $user_external_token . '" not found',
            'data' => [],
        ]);
    }

}
