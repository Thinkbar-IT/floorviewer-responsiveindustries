
<script type="text/javascript">
/*jslint browser: true */
function showRoomsType(type) {
    'use strict';
    window.$('.rooms-list-by-type').hide();
    window.$('.rooms-types').removeClass('active');
    if (type) {
        window.$('#roomsList_' + type).show();
        window.$('#roomsType_' + type).addClass('active');
    } else {
        window.$('#userRoomsList').show();
        window.$('#userRooms').addClass('active');
    }
}
</script>

<div id="dialogRoomSelect" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">@lang('Select Room')</h3>
            </div>

            <div class="modal-body">
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <?php
                        $saved_rooms_present = isset($saved_rooms) && count($saved_rooms) > 0;
                        if ($saved_rooms_present) {
                            echo '<li id="userRooms" role="presentation" class="rooms-types active"><a href="#" onclick="showRoomsType()">' . __('My rooms') . '</a></li>';
                        }

                        if (isset($rooms) && count($rooms) > 0) {
                            $active_room_tab = 0;
                            foreach ($rooms as $key => $sub_rooms) {
                                $active_room_tab += 1;
                                if ($active_room_tab == 1 && !$saved_rooms_present) {
                                    $active = 'active';
                                } else {
                                    $active = '';
                                }

                                if ($key) {
                                    if (array_key_exists($key, $room_types)) {
                                        $room_type = $room_types[$key];
                                    } else {
                                        $room_type = $key;
                                    }
                                    echo '<li id="roomsType_' . $key . '" role="presentation" class="rooms-types ' . $active . '"><a href="#" onclick="showRoomsType(\'' . $key . '\')">' . __($room_type) . '</a></li>';
                                } else {
                                    $other_rooms_li = '<li id="roomsType_other" role="presentation" class="rooms-types ' . $active . '"><a href="#" onclick="showRoomsType(\'other\')">' . __('Other') . '</a></li>';
                                }
                            }
                            if (isset($other_rooms_li)) echo $other_rooms_li;
                        }
                        ?>

                    </ul>
                </div>
                <div class="text-center rooms-select-list">

                    <?php
                    if ($saved_rooms_present) {
                        echo '<div id="userRoomsList" class="rooms-list-by-type">',
                            '<h4 class="modal-title">Your last saved rooms</h4>';
                        foreach ($saved_rooms as $saved_room) {
                            if ($saved_room->room) {
                                echo '<a href="/room/url/' . $saved_room->urlWithParams . '" title="' . $saved_room->room->name . '" class="room-select-link">';
                                $engine_icon_img = '';
                                if (isset($saved_room->engine) && !config('app.hide_engine_icon')) {
                                    if ($saved_room->engine == '2d') {
                                        $engine_icon = '/img/icons/2d.png';
                                    }
                                    if ($saved_room->engine == '3d') {
                                        $engine_icon = '/img/icons/3d.png';
                                    }
                                    if ($saved_room->engine == 'panorama') {
                                        $engine_icon = '/img/icons/panorama.png';
                                    }
                                    if (isset($engine_icon)) {
                                        $engine_icon_img = '<img src="' . $engine_icon . '" alt="" width="32" class="room-image-engine-icon">';
                                    }
                                }
                                echo '<div class="room-image-holder"><img src="' . $saved_room->image . '" alt="">',
                                    $engine_icon_img . '</div>',
                                    '<p>' . $saved_room->room->name . '</p>',
                                    '</a>';
                            }
                        }
                        echo '<p><a href="/home" title="Home">See more or manage saved rooms</a></p>',
                            '</div>';
                    }

                    if (isset($rooms) && count($rooms) > 0) {
                        $active_room_list = 0;
                        foreach ($rooms as $key => $sub_rooms) {
                            $active_room_list += 1;
                            if ($active_room_list == 1 && !$saved_rooms_present) {
                                $display = '';
                            } else {
                                $display = 'style="display: none"';
                            }
                            if ($key) {
                                echo '<div id="roomsList_' . $key . '" class="rooms-list-by-type" ' . $display . '>';
                            } else {
                                echo '<div id="roomsList_other" class="rooms-list-by-type" ' . $display . '>';
                            }

                            foreach ($sub_rooms as $sub_room) {
                                $room_link = '';
                                $engine_icon = '';
                                $engine_image_element = '';
                                if ($view_name == '2d.room') {
                                    $room_link = '/room2d/' . $sub_room->id;
                                    $engine_icon = '/img/icons/2d.png';
                                }
                                if ($view_name == '3d.room') {
                                    $room_link = '/room3d/' . $sub_room->id;
                                    $engine_icon = '/img/icons/3d.png';
                                }
                                if ($view_name == 'panorama.room') {
                                    $room_link = '/panorama/' . $sub_room->id;
                                    $engine_icon = '/img/icons/panorama.png';
                                }
                                if (!config('app.hide_engine_icon')) {
                                    $engine_image_element = '<img src="' . $engine_icon . '" alt="" width="32" class="room-image-engine-icon">';
                                }
                                echo '<a href="' . $room_link . '" title="' . $sub_room->name . '" class="room-select-link">',
                                    '<div class="room-image-holder"><img src="' . $sub_room->icon . '" alt="">' . $engine_image_element . '</div>',
                                    '<p>' . $sub_room->name . '</p>',
                                    '</a>';
                            }

                            echo '</div>';
                        }
                    } else {
                        echo '<h4 class="modal-title">No one room found</h4>';
                    }
                    ?>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>
