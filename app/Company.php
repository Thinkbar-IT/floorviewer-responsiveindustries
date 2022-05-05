<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    public function getLogoAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        } else {
            return Storage::url('company/nologo.png');
        }
    }
}
