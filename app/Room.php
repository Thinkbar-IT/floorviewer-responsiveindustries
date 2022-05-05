<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{

    public function getIconfileAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        } else {
            return Storage::url('rooms/noiconfile.png');
        }
    }

    public function getIconAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        } else {
            return $this->iconfile;
        }
    }

    public static function roomsByType() {
        $rooms = Room::where('enabled', 1)->get();
        $by_type = [];
        foreach ($rooms as $room) {
            if (!isset($by_type[$room->type])) {
                $by_type[$room->type] = [$room];
            } else {
                array_push($by_type[$room->type], $room);
            }
        }
        return $by_type;
    }
}
