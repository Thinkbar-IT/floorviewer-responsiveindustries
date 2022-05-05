<?php

namespace App;

use App\SelectOption;

class RoomType extends SelectOption
{

    public static function optionsAsArray()
    {
        $roomTypes = RoomType::where('enabled', 1)->get();
        $types = [];
        foreach ($roomTypes as $roomType) {
            $types[$roomType->name] = $roomType->display_name;
        }
        return $types;
    }

}
