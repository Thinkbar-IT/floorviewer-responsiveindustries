@extends('layouts.app')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

var changedRooms = {};

function addCheckedRoom(value, checked) {
    'use strict';
    if (!isNaN(parseInt(value, 10))) {
        changedRooms[value] = checked;
        window.$('#warningAlertBox').slideUp();
    }
}

function getCheckedRoomsArray() {
    'use strict';
    var ids = [],
        id;
    for (id in changedRooms) {
        if (changedRooms.hasOwnProperty(id) && changedRooms[id]) {
            ids.push(id);
        }
    }
    return ids;
}

function showConfirmDialog(headerText, message, buttontext) {
    'use strict';
    window.$('#confirmDialogHeader').text(headerText);
    window.$('#confirmDialogText').text(message);
    window.$('#confirmDialogSubmit').text(buttontext);
    window.$('#confirmDialog').modal('show');
}

function enableSelectedRooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/rooms2d/enable');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm enabling rooms', 'Please confirm enabling selected ' + checkedItems.length + ' rooms.', 'Enable Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedRooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/rooms2d/disable');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm disabling rooms', 'Please confirm disabling selected ' + checkedItems.length + ' rooms.', 'Disable Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteSelectedRooms() {
    'use strict';
    var checkedItems = getCheckedRoomsArray();
    if (checkedItems.length > 0) {
        window.$('#roomsForm').attr('action', '/rooms2d/delete');
        window.$('#roomsFormSelectedRooms').val(JSON.stringify(checkedItems));
        showConfirmDialog('Confirm removing rooms', 'Please confirm removing selected ' + checkedItems.length + ' rooms.', 'Remove Rooms');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteRoom(id) {
    'use strict';
    if (id) {
        window.$('#roomsForm').attr('action', '/rooms2d/delete');
        window.$('#roomsFormSelectedRooms').val('[' + id + ']');
        showConfirmDialog('Confirm removing room', 'Please confirm removing room.', 'Remove Room');
    }
}

function addRoom() {
    'use strict';
    window.$('#updateRoomFormBlock').hide();
    window.$('#addRoomFormBlock').slideToggle();
}

function editRoom(id) {
    'use strict';
    document.forms.updateRoomForm.reset();
    window.$('#form-update-room-enabled').attr('checked', false);
    window.$('#addRoomFormBlock').hide();
    window.$('#form-update-room-icon-img').attr('src', '');
    window.$('#form-update-room-shadow-img').attr('src', '');
    window.$('#form-update-room-shadow-matt-img').attr('src', '');

    window.$.ajax({
        url: '/get/room2d/' + id,
        success: function (room) {
            window.$('#form-update-room-id').val(room.id);
            window.$('#form-update-room-name').val(room.name);
            window.$('#form-update-room-type').val(room.type);

            if (Number(room.enabled)) { window.$('#form-update-room-enabled').attr('checked', true); }
            window.$('#form-update-room-icon-img').attr('src', room.icon);
            window.$('#form-update-room-image-img').attr('src', room.image);
            window.$('#form-update-room-shadow-img').attr('src', room.shadow);
            window.$('#form-update-room-shadow-matt-img').attr('src', room.shadow_matt);

            window.$('#form-update-room-surfaces').attr('href', '/room2d/' + room.id + '/surfaces');

            window.$('#updateRoomFormBlock').slideDown();
        }
    });
}

function showBigIconImageModal(name, image) {
    'use strict';
    if (name && image) {
        window.$('#bigIconImageModalHeader').text('Room: ' + name);
        window.$('#bigIconImageModalImg').attr('src', image);
        window.$('#bigIconImageModal').modal('show');
    }
}

</script>

@include('common.alerts')
@include('common.errors')

<div id="addRoomFormBlock" class="panel-body" style="display: none;">
  <form id="addRoomForm" action="/room2d/add" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-room-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-room-name" class="form-control" placeholder="Room name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-type" class="col-sm-3 control-label">Category</label>
      <div class="col-sm-6">
        <select name="type" id="form-room-type" class="form-control">
          @if (count($roomTypes) > 0)
          @foreach ($roomTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-room-icon" class="col-sm-3 control-label">Room Icon</label>
      <div class="col-sm-6">
        <input type="file" name="icon" id="form-room-icon" accept="image/*" class="form-control">
      </div>
      <span class="col-sm-3 help-block">Image must be less than 1 MB and resolution less than 1024x1024 pixels.</span>
    </div>

    <div class="form-group required">
      <label for="form-room-image" class="col-sm-3 control-label">Room Image</label>
      <div class="col-sm-6">
        <input type="file" name="image" id="form-room-image" accept="image/*" class="form-control" required>
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
      @endif
    </div>

    <div class="form-group">
      <label for="form-room-shadow" class="col-sm-3 control-label">Room Shadow Glossy</label>
      <div class="col-sm-6">
        <input type="file" name="shadow" id="form-room-shadow" accept="image/*" class="form-control">
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
      @endif
    </div>

    <div class="form-group">
      <label for="form-room-shadow-matt" class="col-sm-3 control-label">Room Shadow Matt</label>
      <div class="col-sm-6">
        <input type="file" name="shadow_matt" id="form-room-shadow-matt" accept="image/*" class="form-control">
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
      @endif
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <div class="pull-right">
          <button type="submit" class="btn btn-primary">Add room</button>
          <button type="reset" class="btn btn-default" onclick="$('#addRoomFormBlock').slideUp();">Cancel</button>
        </div>
      </div>
    </div>
  </form>
</div>


<div id="updateRoomFormBlock" class="panel-body" style="display: none;">
  <form id="updateRoomForm" action="/room2d/update" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-update-room-id" class="col-sm-3 control-label">Id</label>
      <div class="col-sm-3">
        <input type="text" name="id" id="form-update-room-id" class="form-control" readonly="readonly" required>
      </div>
      <div class="col-sm-3">
        <label><input type="checkbox" name="enabled" id="form-update-room-enabled" value="1"> Enabled</label>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-room-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-update-room-name" class="form-control" placeholder="Room name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-type" class="col-sm-3 control-label">Category</label>
      <div class="col-sm-6">
        <select name="type" id="form-update-room-type" class="form-control">
          @if (count($roomTypes) > 0)
          @foreach ($roomTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-room-icon" class="col-sm-3 control-label">Room Icon</label>
      <div class="col-sm-2">
        <img id="form-update-room-icon-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
      </div>
      <div class="col-sm-4">
        <input type="file" name="icon" id="form-update-room-icon" accept="image/*" class="form-control">
      </div>
      <span class="col-sm-3 help-block">Image must be less than 1 MB and resolution less than 1024x1024 pixels.</span>
    </div>

    <div class="form-group required">
      <label for="form-update-room-image" class="col-sm-3 control-label">Room Image</label>
      <div class="col-sm-2">
        <img id="form-update-room-image-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
      </div>
      <div class="col-sm-4">
        <input type="file" name="image" id="form-update-room-image" accept="image/*" class="form-control">
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
      @endif
    </div>

    <div class="form-group required">
      <label for="form-update-room-shadow" class="col-sm-3 control-label">Room Shadow</label>
      <div class="col-sm-2">
        <img id="form-update-room-shadow-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
      </div>
      <div class="col-sm-4">
        <input type="file" name="shadow" id="form-update-room-shadow" accept="image/*" class="form-control">
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
      @endif
    </div>

    <div class="form-group required">
      <label for="form-update-room-shadow-matt" class="col-sm-3 control-label">Room Shadow Matt</label>
      <div class="col-sm-2">
        <img id="form-update-room-shadow-matt-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigIconImageModal(window.$('#form-update-room-name').val(), this.src);">
      </div>
      <div class="col-sm-4">
        <input type="file" name="shadow_matt" id="form-update-room-shadow-matt" accept="image/*" class="form-control">
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 4 MB and resolution less than 4096x4096 pixels.</span>
      @endif
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <button type="button" class="btn btn-default" onclick="deleteRoom(window.$('#form-update-room-id').val());" title="Remove Room"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        <a id="form-update-room-surfaces" href="#" class="btn btn-default">Edit Tiled Surfaces</a>
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Update room</button>
          <button type="reset" class="btn btn-default" onclick="$('#updateRoomFormBlock').slideUp();">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<form id="roomsForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="roomsFormSelectedRooms" type="hidden" name="selectedRooms" value="">
</form>

<div id="confirmDialog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="confirmDialogHeader" class="modal-title">Confirm </h4>
      </div>
      <div class="modal-body">
        <p id="confirmDialogText">Please confirm.</p>
      </div>
      <div class="modal-footer">
        <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#roomsForm').submit();">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="bigIconImageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="bigIconImageModalHeader" class="modal-title">Room image</h4>
      </div>
      <div class="modal-body" style="text-align: center;">
        <img id="bigIconImageModalImg" src="" alt="" style="max-width: 512px; max-height: 512px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="panel panel-default">
  <div class="dropdown pull-right">
    <button class="btn btn-default btn-sm" onclick="addRoom();">+ Add room</button>
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
      With selected
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="enableSelectedRooms();">Enable</a></li>
      <li><a href="#" onclick="disableSelectedRooms();">Disable</a></li>
      <li class="divider"></li>
      <li><a href="#" onclick="deleteSelectedRooms();">Remove</a></li>
    </ul>
  </div>

  <h3 class="panel-heading">2D Rooms list</h3>

  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Room</th>
          <th>Name</th>
          <th>Category</th>
          <th>Link</th>
          <th>Enabled</th>
          <th colspan="2">Action</th>
        </tr>
      </thead>

      <tbody>
        @if (count($rooms) > 0)
        @foreach ($rooms as $room)
        <tr @if (!$room->enabled) style="opacity: 0.5;" @endif>
          <td class="table-text">
            <input type="checkbox" name="" value="{{ $room->id }}" onchange="addCheckedRoom(this.value, this.checked);">
          </td>
          <td class="table-text">
            <img src="{{ $room->icon }}" alt="" class="img-thumbnail" style="max-width: 64px; max-height: 64px; cursor: pointer;" onclick="showBigIconImageModal('{{ $room->name }}', this.src);">
          </td>
          <td class="table-text bold"><a href="#" onclick="editRoom( {{ $room->id }} );" title="Edit">{{ $room->name }}</a></td>
          <td class="table-text"> @if (isset($roomTypes[$room->type])) {{ $roomTypes[$room->type] }} @else {{ $room->type }} @endif </td>
          <td class="table-text"><a href="/room2d/{{ $room->id }}" title="/room2d/{{ $room->id }}"><img src="/img/icons/2d.png" alt="" width="32"></a></td>
          <td class="table-text">@if ($room->enabled) Yes @else No @endif</td>
          <td class="table-text">
            <a href="/room2d/{{ $room->id }}/surfaces">Edit Tiled Surfaces</a>
          </td>
          <td class="table-text">
            <button type="button" class="close" onclick="deleteRoom({{ $room->id }});" title="Remove Room">&times;</button>
          </td>
        </tr>
        @endforeach
        @else
          No one room found.
        @endif
      </tbody>
    </table>
    <div class="page-links" style="text-align: center;">{{ $rooms->links() }}</div>
  </div>
</div>

@endsection
