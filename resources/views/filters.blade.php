@extends('layouts.app')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

function changeValuesState() {
    'use strict';
    if (window.$('#form-filter-type').val() === 'slider') {
        window.$('#form-filter-values').attr('disabled', true);
    } else {
        window.$('#form-filter-values').attr('disabled', false);
    }

    if (window.$('#form-update-filter-type').val() === 'slider') {
        window.$('#form-update-filter-values').attr('disabled', true);
    } else {
        window.$('#form-update-filter-values').attr('disabled', false);
    }
}

function editFilter(id) {
    'use strict';
    document.forms.updateFilterForm.reset();
    window.$('#form-update-filter-enabled').attr('checked', false);

    window.$.ajax({
        url: '/get/filter/' + id,
        success: function (filter) {
            window.$('#addFilterFormBlock').hide('fast');
            window.$('#updateFilterFormBlock').show('fast');
            window.$('#form-update-filter-id').val(filter.id);
            window.$('#form-update-filter-name').val(filter.name);
            window.$('#form-update-filter-field').val(filter.field);
            window.$('#form-update-filter-surface').val(filter.surface);
            window.$('#form-update-filter-type').val(filter.type);
            window.$('#form-update-filter-values').val(filter.values);
            if (Number(filter.enabled)) { window.$('#form-update-filter-enabled').attr('checked', true); }

            changeValuesState();
        }
    });
}

var changedFilters = {};

function addCheckedFilter(value, checked) {
    'use strict';
    changedFilters[value] = checked;
    window.$('#warningAlertBox').fadeOut();
}

function checkSelectedFilters() {
    'use strict';
    var id;
    for (id in changedFilters) {
        if (changedFilters.hasOwnProperty(id) && changedFilters[id]) {
            return true;
        }
    }
}

function getCheckedFiltersArray() {
    'use strict';
    var ids = [],
        id;
    if (checkSelectedFilters()) {
        for (id in changedFilters) {
            if (changedFilters.hasOwnProperty(id) && changedFilters[id]) {
                ids.push(id);
            }
        }
    }
    return ids;
}

function enableSelectedFilters() {
    'use strict';
    if (checkSelectedFilters()) {
        var checkedFilters = getCheckedFiltersArray();
        window.$('#filtersForm').attr('action', '/filters/enable');
        window.$('#filtersFormInput').val(JSON.stringify(checkedFilters));

        window.$('#confirmDialogHeader').text('Confirm enabling filters');
        window.$('#confirmDialogText').text('Please confirm enabling selected ' + checkedFilters.length + ' filters.');
        window.$('#confirmDialogSubmit').text('Enable Filters');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedFilters() {
    'use strict';
    if (checkSelectedFilters()) {
        var checkedFilters = getCheckedFiltersArray();
        window.$('#filtersForm').attr('action', '/filters/disable');
        window.$('#filtersFormInput').val(JSON.stringify(checkedFilters));

        window.$('#confirmDialogHeader').text('Confirm disabling filters');
        window.$('#confirmDialogText').text('Please confirm disabling selected ' + checkedFilters.length + ' filters.');
        window.$('#confirmDialogSubmit').text('Disable Filters');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
        // window.$("#warningAlertBox").fadeTo(7000, 500).slideUp(500, function(){
        //     window.$("#warningAlertBox").slideUp(500);
        // });
    }
}

function deleteSelectedFilters() {
    'use strict';
    if (checkSelectedFilters()) {
        var checkedFilters = getCheckedFiltersArray();
        window.$('#filtersForm').attr('action', '/filters/delete');
        window.$('#filtersFormInput').val(JSON.stringify(checkedFilters));

        window.$('#confirmDialogHeader').text('Confirm removing filters');
        window.$('#confirmDialogText').text('Please confirm removing selected ' + checkedFilters.length + ' filters.');
        window.$('#confirmDialogSubmit').text('Remove Filters');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteFilter(id) {
    'use strict';
    window.$('#filtersForm').attr('action', '/filters/delete');
    window.$('#filtersFormInput').val('[' + id + ']');

    window.$('#confirmDialogHeader').text('Confirm removing filter');
    window.$('#confirmDialogText').text('Please confirm removing filter.');
    window.$('#confirmDialogSubmit').text('Remove Filter');
    window.$('#confirmDialog').modal('show');
}

function clearField() {
    'use strict';
    var filterFieldName = window.$('#form-update-filter-field').val();
    window.$('#form-update-filter-field').val(filterFieldName.replace(/\W/g, ''))

    filterFieldName = window.$('#form-filter-field').val();
    window.$('#form-filter-field').val(filterFieldName.replace(/\W/g, ''))
}
</script>


@include('common.alerts')
@include('common.errors')

<div id="addFilterFormBlock" class="panel-body" style="display: none;">
  <form action="/filter/add" method="POST" class="form-horizontal" onsubmit="clearField()">
    {{ csrf_field() }}

    <div class="form-group">
      <label for="form-filter-name" class="col-sm-3 control-label">Filter name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-filter-name" class="form-control" placeholder="Filter name" maxlength="100">
      </div>
    </div>

    <div class="form-group required">
      <label for="form-filter-field" class="col-sm-3 control-label">Field</label>
      <div class="col-sm-6">
        <input type="text" name="field" id="form-filter-field" class="form-control" placeholder="Field" maxlength="32" required>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-filter-surface" class="col-sm-3 control-label">Surface</label>
      <div class="col-sm-6">
        <select name="surface" id="form-filter-surface" class="form-control" required>
          @if (count($surfaceTypes) > 0)
          @foreach ($surfaceTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-filter-type" class="col-sm-3 control-label">Type</label>
      <div class="col-sm-6">
        <select name="type" id="form-filter-type" class="form-control" onclick="changeValuesState();">
          <option value="checkbox" selected>Checkbox</option>
          <option value="slider">Slider</option>
          <option value="accordion">Accordion</option>
        </select>
      </div>
    </div>

    <div id="form-filter-values-box" class="form-group">
      <label for="form-filter-values" class="col-sm-3 control-label">Values</label>
      <div class="col-sm-6">
        <input type="text" name="values" id="form-filter-values" class="form-control" placeholder="Values">
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Add filter</button>
          <button type="reset" class="btn btn-default" onclick="window.$('#addFilterFormBlock').hide('fast');">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<div id="updateFilterFormBlock" class="panel-body" style="display: none;">
  <form action="/filter/update" method="POST" class="form-horizontal" id="updateFilterForm" onsubmit="clearField()">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-update-filter-id" class="col-sm-3 control-label">Id</label>
      <div class="col-sm-3">
        <input type="text" name="id" id="form-update-filter-id" class="form-control" readonly="readonly" required>
      </div>
      <div class="col-sm-3">
        <label><input type="checkbox" name="enabled" id="form-update-filter-enabled" value="1"> Enabled</label>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-filter-name" class="col-sm-3 control-label">Filter name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-update-filter-name" class="form-control" placeholder="Filter name" maxlength="100">
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-filter-field" class="col-sm-3 control-label">Field</label>
      <div class="col-sm-6">
        <input type="text" name="field" id="form-update-filter-field" class="form-control" placeholder="Field" maxlength="32" required>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-filter-surface" class="col-sm-3 control-label">Surface</label>
      <div class="col-sm-6">
        <select name="surface" id="form-update-filter-surface" class="form-control" required>
          @if (count($surfaceTypes) > 0)
          @foreach ($surfaceTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-filter-type" class="col-sm-3 control-label">Type</label>
      <div class="col-sm-6">
        <select name="type" id="form-update-filter-type" class="form-control" onclick="changeValuesState();">
          <option value="checkbox" selected>Checkbox</option>
          <option value="slider">Slider</option>
          <option value="accordion">Accordion</option>
        </select>
      </div>
    </div>

    <div id="form-update-filter-values-box" class="form-group">
      <label for="form-update-filter-values" class="col-sm-3 control-label">Values</label>
      <div class="col-sm-6">
        <input type="text" name="values" id="form-update-filter-values" class="form-control" placeholder="Values">
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <button type="button" class="btn btn-default" onclick="deleteFilter(window.$('#form-update-filter-id').val());" title="Remove Filter"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Update filter</button>
          <button type="button" class="btn btn-default" onclick="window.$('#updateFilterFormBlock').hide('fast');">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<form id="filtersForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="filtersFormInput" type="hidden" name="selectedFilters" value="">
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
        <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#filtersForm').submit();">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="dropdown pull-right">
    <button class="btn btn-default btn-sm" onclick="window.$('#addFilterFormBlock').toggle('fast'); window.$('#updateFilterFormBlock').hide('fast');">+ Add filter</button>
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
      With selected
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="enableSelectedFilters();">Enable</a></li>
      <li><a href="#" onclick="disableSelectedFilters();">Disable</a></li>
      <li class="divider"></li>
      <li><a href="#" onclick="deleteSelectedFilters();">Remove</a></li>
    </ul>
  </div>

  <h3 class="panel-heading">Filters list</h3>

  @if (count($filters) > 0)

  <div class="panel-body">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Name</th>
          <th>Field</th>
          <th>Surface</th>
          <th>Type</th>
          <th>Values</th>
          <th>Enabled</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($filters as $filter)
          <tr @if (!$filter->enabled) style="opacity: 0.5;" @endif>
            <td class="table-text">
              <input type="checkbox" name="" value="{{ $filter->id }}" onchange="addCheckedFilter(this.value, this.checked);">
            </td>
            <td class="table-text bold"><a href="#" onclick="editFilter( {{ $filter->id }} )" title="Edit">{{ $filter->name }}</a></td>
            <td class="table-text">{{ $filter->field }}</td>
            <td class="table-text"> @if (isset($surfaceTypes[$filter->surface])) {{ $surfaceTypes[$filter->surface] }} @else {{ $filter->surface }} @endif </td>
            <td class="table-text">{{ $filter->type }}</td>
            <td class="table-text" title="{{ $filter->values }}">{{ substr($filter->values, 0, 12) }} ...</td>
            <td class="table-text">@if ($filter->enabled) Yes @else No @endif</td>
            <td class="table-text">
              <button type="button" class="close" onclick="deleteFilter({{ $filter->id }})" title="Remove Filter">&times;</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="page-links" style="text-align: center;">{{ $filters->links() }}</div>
  </div>

  @endif

</div>
@endsection
