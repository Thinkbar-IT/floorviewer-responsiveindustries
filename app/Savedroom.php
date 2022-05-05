<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Auth;

class Savedroom extends Model
{
    public function getImageAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }

        if ($this->room->icon) {
            return $this->room->icon;
        }

        return $this->room->iconfile;
    }

    public function getUrlWithParamsAttribute()
    {
        if (config('app.url_filters')) {
            return $this->url . $this->note;
        }

        return $this->url;
    }

    public function room()
    {
        if ($this->engine == '2d') {
            return $this->belongsTo('App\Room2d', 'roomid');
        }
        if ($this->engine == '3d') {
            return $this->belongsTo('App\Room', 'roomid');
        }
        if ($this->engine == 'panorama') {
            return $this->belongsTo('App\Panorama', 'roomid');
        }
    }

    public function del()
    {
        Storage::disk('public')->delete($this->getOriginal('image'));
        $this->delete();
    }

    public function setEngineAttribute($value)
    {
        $this->attributes['engine'] = mb_strtolower($value);
    }

    public static function getUserSavedRooms($userId)
    {
        if (isset($userId)) {
            return Savedroom::where([['userid', $userId], ['enabled', 1]])
                ->whereIn('engine', Savedroom::getEngines())
                ->orderBy('updated_at', 'desc')
                ->paginate(20);
        }
    }

    public static function getUserRoomsShort($userId)
    {
        if (isset($userId)) {
            $fields = ['url', 'image'];

            return Savedroom::where([['userid', $userId], ['enabled', 1]])
                ->whereIn('engine', Savedroom::getEngines())
                ->orderBy('updated_at', 'desc')
                ->get($fields);
        }
    }

    public static function deleteRelated($room_id, $engine)
    {
        if (isset($room_id) && isset($engine)) {
            $saved_rooms = Savedroom::where([['roomid', $room_id], ['engine', $engine]])->get();
            foreach ($saved_rooms as $saved_room) {
                $saved_room->del();
            }
        }
    }

    public static function existsByUrl($room_url)
    {
        if (isset($room_url)) {
            return Savedroom::where([['url', $room_url]])->count() > 0;
        }

        return false;
    }

    private static function getEngines()
    {
        $engine_2d_enabled = config('app.engine_2d_enabled');
        $engine_3d_enabled = config('app.engine_3d_enabled');
        $engine_panorama_enabled = config('app.engine_panorama_enabled');

        $engines = [];
        if (config('app.engine_2d_enabled')) {
            array_push($engines, '2d');
        }
        if (config('app.engine_3d_enabled')) {
            array_push($engines, '3d');
        }
        if (config('app.engine_panorama_enabled')) {
            array_push($engines, 'panorama');
        }
        return $engines;
    }
}
