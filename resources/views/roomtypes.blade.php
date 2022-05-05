@extends('layouts.app')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

function editType(id) {
    'use strict';
    document.forms.updateTypeForm.reset();
    window.$('#form-update-type-enabled').attr('checked', false);

    window.$.ajax({
        url: '/get/roomtype/' + id,
        success: function (type) {
            window.$('#addTypeFormBlock').hide('fast');
            window.$('#updateTypeFormBlock').show('fast');
            window.$('#form-update-type-id').val(type.id);
            window.$('#form-update-type-name').val(type.name);
            if (Number(type.enabled)) { window.$('#form-update-type-enabled').attr('checked', true); }
        }
    });
}

var changedTypes = {};

function addCheckedType(value, checked) {
    'use strict';
    changedTypes[value] = checked;
    window.$('#warningAlertBox').fadeOut();
}

function checkSelectedTypes() {
    'use strict';
    var id;
    for (id in changedTypes) {
        if (changedTypes.hasOwnProperty(id) && changedTypes[id]) {
            return true;
        }
    }
}

function getCheckedTypesArray() {
    'use strict';
    var ids = [],
        id;
    if (checkSelectedTypes()) {
        for (id in changedTypes) {
            if (changedTypes.hasOwnProperty(id) && changedTypes[id]) {
                ids.push(id);
            }
        }
    }
    return ids;
}

function enableSelectedTypes() {
    'use strict';
    if (checkSelectedTypes()) {
        var checkedTypes = getCheckedTypesArray();
        window.$('#typesForm').attr('action', '/roomtypes/enable');
        window.$('#typesFormInput').val(JSON.stringify(checkedTypes));

        window.$('#confirmDialogHeader').text('Confirm enabling Room Types');
        window.$('#confirmDialogText').text('Please confirm enabling selected ' + checkedTypes.length + ' Room Types.');
        window.$('#confirmDialogSubmit').text('Enable Room Types');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedTypes() {
    'use strict';
    if (checkSelectedTypes()) {
        var checkedTypes = getCheckedTypesArray();
        window.$('#typesForm').attr('action', '/roomtypes/disable');
        window.$('#typesFormInput').val(JSON.stringify(checkedTypes));

        window.$('#confirmDialogHeader').text('Confirm disabling Room Types');
        window.$('#confirmDialogText').text('Please confirm disabling selected ' + checkedTypes.length + ' Room Types.');
        window.$('#confirmDialogSubmit').text('Disable Room Types');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteSelectedTypes() {
    'use strict';
    if (checkSelectedTypes()) {
        var checkedTypes = getCheckedTypesArray();
        window.$('#typesForm').attr('action', '/roomtypes/delete');
        window.$('#typesFormInput').val(JSON.stringify(checkedTypes));

        window.$('#confirmDialogHeader').text('Confirm removing Room Types');
        window.$('#confirmDialogText').text('Please confirm removing selected ' + checkedTypes.length + ' Room Types.');
        window.$('#confirmDialogSubmit').text('Remove Room Types');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteType(id) {
    'use strict';
    window.$('#typesForm').attr('action', '/roomtypes/delete');
    window.$('#typesFormInput').val('[' + id + ']');

    window.$('#confirmDialogHeader').text('Confirm removing Room Type');
    window.$('#confirmDialogText').text('Please confirm removing Room Type.');
    window.$('#confirmDialogSubmit').text('Remove Room Type');
    window.$('#confirmDialog').modal('show');
}
</script>


@include('common.alerts')
@include('common.errors')

<div id="addTypeFormBlock" class="panel-body" style="display: none;">
  <form action="/roomtype/add" method="POST" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group">
      <label for="form-type-name" class="col-sm-3 control-label">Room Type name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-type-name" class="form-control" placeholder="Room Type name" maxlength="100">
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Add Room Type</button>
          <button type="reset" class="btn btn-default" onclick="window.$('#addTypeFormBlock').hide('fast');">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<div id="updateTypeFormBlock" class="panel-body" style="display: none;">
  <form action="/roomtype/update" method="POST" class="form-horizontal" id="updateTypeForm">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-update-type-id" class="col-sm-3 control-label">Id</label>
      <div class="col-sm-3">
        <input type="text" name="id" id="form-update-type-id" class="form-control" readonly="readonly" required>
      </div>
      <div class="col-sm-3">
        <label><input type="checkbox" name="enabled" id="form-update-type-enabled" value="1"> Enabled</label>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-type-name" class="col-sm-3 control-label">Room Type name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-update-type-name" class="form-control" placeholder="Room Type name" maxlength="100">
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <button type="button" class="btn btn-default" onclick="deleteType(window.$('#form-update-type-id').val());" title="Remove Room Type"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Update Room Type</button>
          <button type="button" class="btn btn-default" onclick="window.$('#updateTypeFormBlock').hide('fast');">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<form id="typesForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="typesFormInput" type="hidden" name="selectedTypes" value="">
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
        <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#typesForm').submit();">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="dropdown pull-right">
    <button class="btn btn-default btn-sm" onclick="window.$('#addTypeFormBlock').toggle('fast'); window.$('#updateTypeFormBlock').hide('fast');">Add Room Type</button>
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
      With selected
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="enableSelectedTypes();">Enable</a></li>
      <li><a href="#" onclick="disableSelectedTypes();">Disable</a></li>
      <li class="divider"></li>
      <li><a href="#" onclick="deleteSelectedTypes();">Remove</a></li>
    </ul>
  </div>

  <h3 class="panel-heading">Room Types List</h3>

  @if (count($types) > 0)

  <div class="panel-body">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Value</th>
          <th>Name</th>
          <th>Enabled</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($types as $type)
          <tr @if (!$type->enabled) style="opacity: 0.5;" @endif>
            <td class="table-text">
              <input type="checkbox" name="" value="{{ $type->id }}" onchange="addCheckedType(this.value, this.checked);">
            </td>
            <td class="table-text bold"><a href="#" onclick="editType( {{ $type->id }} )" title="Edit">{{ $type->name }}</a></td>
            <td class="table-text">{{ $type->display_name }}</td>
            <td class="table-text">@if ($type->enabled) Yes @else No @endif</td>
            <td class="table-text">
              <button type="button" class="close" onclick="deleteType({{ $type->id }})" title="Remove Type">&times;</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="page-links" style="text-align: center;">{{ $types->links() }}</div>
  </div>

  @endif

</div>
@endsection
