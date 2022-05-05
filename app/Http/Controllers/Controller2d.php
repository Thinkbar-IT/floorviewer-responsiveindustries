<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\RoomType;
use App\Room2d;
use App\SurfaceType;
use App\Savedroom;
use App\Category;

class Controller2d extends Controller
{

    /**
     *  AJAX
     */

    public function getRoom($id) {
        $room = Room2d::findOrFail($id);
        $room->surfaceTypes = SurfaceType::optionsAsArray();
        return response()->json($room);
    }


    ///////////////////////////////////////////////////////////


    public function room($id, $url = null, $icon = null) {
        $roomById = false;
        if ($id) {
            $roomById = Room2d::find($id);
            $icon = Room2d::find($id)->icon;
        }

        if (!$roomById && !$url) { abort(404); }

        $userId = Auth::id();

        return view('2d.room', [
            'roomId' => $id,
            'savedRoomUrl' => $url,
            'rooms' => Room2d::roomsByType(),
            'saved_rooms' => Savedroom::getUserSavedRooms($userId),
            'userId' => $userId,
            'room_types' => RoomType::optionsAsArray(),
            'room_icon' => $icon,
            'product_categories' => Category::getByType(1),
        ]);
    }

    public function roomDefault() {
        $room = Room2d::where('enabled', 1)->where('is_home',1)->first();

        if (!$room) { abort(404); }

        return $this->room($room->id);
    }


    ///////////////////////////////////////////////////////////


    public function rooms() {
        $rooms = Room2d::orderBy('id', 'desc')->paginate(10);
        $roomTypes = RoomType::optionsAsArray();

        return view('2d.rooms', ['rooms' => $rooms, 'roomTypes' => $roomTypes]);
    }

    public function roomAdd(Request $request) {
        $image_size_limit = config('app.unlimited_image_size') ? '' : '|max:4096|dimensions:max_width=4096,max_height=4096';

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:32|string',
            'icon' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'image' => 'required|image' . $image_size_limit,
            'shadow' => 'nullable|image' . $image_size_limit,
            'shadow_matt' => 'nullable|image' . $image_size_limit,
        ]);

        if ($validator->fails()) {
            return redirect('/rooms2d')->withInput()->withErrors($validator);
        }

        $room = new Room2d;
        $room->name = $request->name;
        $room->type = $request->type;

        if ($request->hasFile('icon')) {
            $room->icon = $request->file('icon')->store('rooms2d', 'public');
        }
        if ($request->hasFile('image')) {
            $room->image = $request->file('image')->store('rooms2d', 'public');
        }
        if ($request->hasFile('shadow')) {
            $room->shadow = $request->file('shadow')->store('rooms2d', 'public');
        }
        if ($request->hasFile('shadow_matt')) {
            $room->shadow_matt = $request->file('shadow_matt')->store('rooms2d', 'public');
        }

        $room->surfaces = '[]';
        $room->enabled = 1;
        $room->save();

        return redirect('/rooms2d');
    }

    public function roomUpdate(Request $request) {
        $image_size_limit = config('app.unlimited_image_size') ? '' : '|max:4096|dimensions:max_width=4096,max_height=4096';

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:room2ds,id',
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:32|string',
            'icon' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'image' => 'nullable|image' . $image_size_limit,
            'shadow' => 'nullable|image' . $image_size_limit,
            'shadow_matt' => 'nullable|image' . $image_size_limit,
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/rooms2d')->withInput()->withErrors($validator);
        }

        $room = Room2d::findOrFail($request->id);
        $room->name = $request->name;
        $room->type = $request->type;

        if ($request->hasFile('icon')) {
            Storage::disk('public')->delete($room->getOriginal('icon'));
            $room->icon = $request->file('icon')->store('rooms2d', 'public');
        }
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($room->getOriginal('image'));
            $room->image = $request->file('image')->store('rooms2d', 'public');
        }
        if ($request->hasFile('shadow')) {
            Storage::disk('public')->delete($room->getOriginal('shadow'));
            $room->shadow = $request->file('shadow')->store('rooms2d', 'public');
        }
        if ($request->hasFile('shadow_matt')) {
            Storage::disk('public')->delete($room->getOriginal('shadow_matt'));
            $room->shadow_matt = $request->file('shadow_matt')->store('rooms2d', 'public');
        }

        if (isset($request->enabled)) { $room->enabled = 1; } else { $room->enabled = 0; }

        $room->save();

        return redirect('/rooms2d');
    }

    public function roomsDelete(Request $request) {
        $rooms = Room2d::find(json_decode($request->selectedRooms));
        foreach ($rooms as $room) {
            Savedroom::deleteRelated($room->id, '2d');

            Storage::disk('public')->delete($room->getOriginal('icon'));
            Storage::disk('public')->delete($room->getOriginal('image'));
            Storage::disk('public')->delete($room->getOriginal('shadow'));
            Storage::disk('public')->delete($room->getOriginal('shadow_matt'));
            $room->delete();
        }

        return redirect('/rooms2d');
    }

    public function roomsEnable(Request $request) {
        Room2d::whereIn('id', json_decode($request->selectedRooms))->update(['enabled' => 1]);
        return redirect('/rooms2d');
    }

    public function roomsDisable(Request $request) {
        Room2d::whereIn('id', json_decode($request->selectedRooms))->update(['enabled' => 0]);
        return redirect('/rooms2d');
    }



    public function roomSurfaces($id) {
        return view('2d.roomSurfaces', ['roomId' => $id]);
    }

    public function roomSurfacesUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'roomId' => 'required|integer|exists:room2ds,id',
            'surfaces' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect('/rooms2d')->withInput()->withErrors($validator);
        }

        $room = Room2d::findOrFail($request->roomId);
        $room->surfaces = $request->surfaces;
        $room->save();

        return redirect('/rooms2d');
    }

}
