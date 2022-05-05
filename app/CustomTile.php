<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomTile extends Model
{
    public function getFileAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        } else {
            return Storage::url('tiles/nofile.png');
        }
    }
}
