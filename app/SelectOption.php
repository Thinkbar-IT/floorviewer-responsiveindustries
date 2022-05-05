<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectOption extends Model
{
    protected $typed_name;

    public function setNameAttribute($value)
    {
        $this->typed_name = trim($value);
        $this->attributes['name'] = mb_strtolower($this->typed_name);
    }



    public function getDisplayNameAttribute($value)
    {
        if (isset($value) && $value) {
            return ($value);
        } else {
            return $this->name;
        }
    }

    public function setDisplayNameAttribute($value)
    {
        if (isset($value) && trim($value)) {
            $this->attributes['display_name'] = trim($value);
        } else {
            $this->attributes['display_name'] = trim($this->typed_name);
        }
    }



    public function setEnabledAttribute($value)
    {
        if (isset($value) && $value) {
            $this->attributes['enabled'] = 1;
        } else {
            $this->attributes['enabled'] = 0;
        }
    }

}
