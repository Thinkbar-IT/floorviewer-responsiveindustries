<?php

namespace App;

class Room2d extends RoomModel {

    public static function roomsByType() {
        $rooms = Room2d::where('enabled', 1)->get();
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
