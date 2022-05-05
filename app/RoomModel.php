<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomModel extends Model {

    public function getIconAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        } else {
            return Storage::url('rooms/noiconfile.png');
        }
    }

    public function getImageAttribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getShadowAttribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

    public function getShadowMattAttribute($value) {
        if ($value) {
            return Storage::url($value);
        }
    }

}
