<?php

namespace App;

use App\SelectOption;

class SurfaceType extends SelectOption
{

    public static function optionsAsArray()
    {
        $surfaceTypes = SurfaceType::where('enabled', 1)->get();
        $types = [];
        foreach ($surfaceTypes as $surfaceType) {
            $types[$surfaceType->name] = $surfaceType->display_name;
        }
        return $types;
    }

}
