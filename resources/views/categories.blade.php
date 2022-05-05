@extends('layouts.app')

@section('content')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

function editCategory(id) {
    'use strict';
    document.forms.updateCategoryForm.reset();
    window.$('#form-update-category-enabled').attr('checked', false);

    window.$.ajax({
        url: '/get/category/' + id,
        success: function (category) {
          console.log(category);

            window.$('#addCategoryFormBlock').hide('fast');
            window.$('#updateCategoryFormBlock').show('fast');
            window.$('#form-update-category-id').val(category.id);
            window.$('#form-update-category-parent_id').val(category.parent_id);
            window.$('#form-update-category-type').val(category.type);
            window.$('#form-update-category-name').val(category.name);
            window.$('#form-update-category-title').val(category.title);
            window.$('#form-update-category-note').val(category.note);
            window.$('#form-update-category-surface').val(category.surface);
            if (Number(category.enabled)) { window.$('#form-update-category-enabled').attr('checked', true); }

            window.$('#form-update-category-parent_id option').each((index, option) => {
              if (option.value == category.id) {
                window.$(option).prop('disabled', true);
              } else {
                window.$(option).prop('disabled', false);
              }
            });
        }
    });
}

var changedCategories = {};

function addCheckedCategory(value, checked) {
    'use strict';
    changedCategories[value] = checked;
    window.$('#warningAlertBox').fadeOut();
}

function checkSelectedCategories() {
    'use strict';
    var id;
    for (id in changedCategories) {
        if (changedCategories.hasOwnProperty(id) && changedCategories[id]) {
            return true;
        }
    }
}

function getCheckedCategoriesArray() {
    'use strict';
    var ids = [],
        id;
    if (checkSelectedCategories()) {
        for (id in changedCategories) {
            if (changedCategories.hasOwnProperty(id) && changedCategories[id]) {
                ids.push(id);
            }
        }
    }
    return ids;
}

function enableSelectedCategories() {
    'use strict';
    if (checkSelectedCategories()) {
        var checkedCategories = getCheckedCategoriesArray();
        window.$('#categoriesForm').attr('action', '/categories/enable');
        window.$('#categoriesFormInput').val(JSON.stringify(checkedCategories));

        window.$('#confirmDialogHeader').text('Confirm enabling categories');
        window.$('#confirmDialogText').text('Please confirm enabling selected ' + checkedCategories.length + ' categories.');
        window.$('#confirmDialogSubmit').text('Enable Categories');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function disableSelectedCategories() {
    'use strict';
    if (checkSelectedCategories()) {
        var checkedCategories = getCheckedCategoriesArray();
        window.$('#categoriesForm').attr('action', '/categories/disable');
        window.$('#categoriesFormInput').val(JSON.stringify(checkedCategories));

        window.$('#confirmDialogHeader').text('Confirm disabling categories');
        window.$('#confirmDialogText').text('Please confirm disabling selected ' + checkedCategories.length + ' categories.');
        window.$('#confirmDialogSubmit').text('Disable Categories');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteSelectedCategories() {
    'use strict';
    if (checkSelectedCategories()) {
        var checkedCategories = getCheckedCategoriesArray();
        window.$('#categoriesForm').attr('action', '/categories/delete');
        window.$('#categoriesFormInput').val(JSON.stringify(checkedCategories));

        window.$('#confirmDialogHeader').text('Confirm removing categories');
        window.$('#confirmDialogText').text('Please confirm removing selected ' + checkedCategories.length + ' categories.');
        window.$('#confirmDialogSubmit').text('Remove Categories');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}

function deleteCategory(id) {
    'use strict';
    window.$('#categoriesForm').attr('action', '/categories/delete');
    window.$('#categoriesFormInput').val('[' + id + ']');

    window.$('#confirmDialogHeader').text('Confirm removing category');
    window.$('#confirmDialogText').text('Please confirm removing category.');
    window.$('#confirmDialogSubmit').text('Remove Category');
    window.$('#confirmDialog').modal('show');
}
</script>

@include('common.alerts')
@include('common.errors')

<div id="addCategoryFormBlock" class="panel-body" style="display: none;">
  <form action="/category/add" method="POST" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group">
      <label for="form-category-parent_id" class="col-sm-3 control-label">Parent Category</label>
      <div class="col-sm-6">
        <select name="parent_id" id="form-category-parent_id" class="form-control">
          <option value="" selected></option>
          @if (count($categories_tree) > 0)
          @foreach ($categories_tree as $category)
            @if (!isset($category->parent_id))
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endif
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-category-type" class="col-sm-3 control-label">Type</label>
      <div class="col-sm-6">
        <select name="type" id="form-category-type" class="form-control" required>
          @if (count($category_types) > 0)
          @foreach ($category_types as $type => $display_name)
            <option value="{{ $type }}" @if ($type == 1) selected @endif>{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-category-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-category-name" class="form-control" placeholder="Category name" maxlength="100" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-category-title" class="col-sm-3 control-label">Title</label>
      <div class="col-sm-6">
        <input type="text" name="title" id="form-category-title" class="form-control" placeholder="Category title" maxlength="255">
      </div>
    </div>

    <div class="form-group">
      <label for="form-category-note" class="col-sm-3 control-label">Note</label>
      <div class="col-sm-6">
        <input type="text" name="note" id="form-category-note" class="form-control" placeholder="Category note" maxlength="255">
      </div>
    </div>

    <div class="form-group required">
      <label for="form-category-surface" class="col-sm-3 control-label">Surface</label>
      <div class="col-sm-6">
        <select name="surface" id="form-category-surface" class="form-control" required>
          @if (count($surface_types) > 0)
          @foreach ($surface_types as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Add category</button>
          <button type="reset" class="btn btn-default" onclick="window.$('#addCategoryFormBlock').hide('fast');">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<div id="updateCategoryFormBlock" class="panel-body" style="display: none;">
  <form action="/category/update" method="POST" class="form-horizontal" id="updateCategoryForm">
    {{ csrf_field() }}

    <div class="form-group required">
      <label for="form-update-category-id" class="col-sm-3 control-label">Id</label>
      <div class="col-sm-3">
        <input type="text" name="id" id="form-update-category-id" class="form-control" readonly="readonly" required>
      </div>
      <div class="col-sm-3">
        <label><input type="checkbox" name="enabled" id="form-update-category-enabled" value="1"> Enabled</label>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-category-parent_id" class="col-sm-3 control-label">Parent Category</label>
      <div class="col-sm-6">
        <select name="parent_id" id="form-update-category-parent_id" class="form-control">
          <option value="" selected></option>
          @if (count($categories_tree) > 0)
          @foreach ($categories_tree as $category)
            @if (!isset($category->parent_id))
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endif
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-category-type" class="col-sm-3 control-label">Type</label>
      <div class="col-sm-6">
        <select name="type" id="form-update-category-type" class="form-control" required>
          @if (count($category_types) > 0)
          @foreach ($category_types as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-category-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-update-category-name" class="form-control" placeholder="Category name" maxlength="100" required>
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-category-title" class="col-sm-3 control-label">Title</label>
      <div class="col-sm-6">
        <input type="text" name="title" id="form-update-category-title" class="form-control" placeholder="Category title" maxlength="255">
      </div>
    </div>

    <div class="form-group">
      <label for="form-update-category-note" class="col-sm-3 control-label">Note</label>
      <div class="col-sm-6">
        <input type="text" name="note" id="form-update-category-note" class="form-control" placeholder="Category note" maxlength="255">
      </div>
    </div>

    <div class="form-group required">
      <label for="form-update-category-surface" class="col-sm-3 control-label">Surface</label>
      <div class="col-sm-6">
        <select name="surface" id="form-update-category-surface" class="form-control" required>
          @if (count($surface_types) > 0)
          @foreach ($surface_types as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <button type="button" class="btn btn-default" onclick="deleteCategory(window.$('#form-update-category-id').val());" title="Remove Category"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        <span class="pull-right">
          <button type="submit" class="btn btn-primary">Update category</button>
          <button type="button" class="btn btn-default" onclick="window.$('#updateCategoryFormBlock').hide('fast');">Cancel</button>
        </span>
      </div>
    </div>
  </form>
</div>


<form id="categoriesForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="categoriesFormInput" type="hidden" name="selectedCategories" value="">
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
        <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#categoriesForm').submit();">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="dropdown pull-right">
    <button class="btn btn-default btn-sm" onclick="window.$('#addCategoryFormBlock').toggle('fast'); window.$('#updateCategoryFormBlock').hide('fast');">+ Add category</button>
    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
      With selected
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="enableSelectedCategories();">Enable</a></li>
      <li><a href="#" onclick="disableSelectedCategories();">Disable</a></li>
      <li class="divider"></li>
      <li><a href="#" onclick="deleteSelectedCategories();">Remove</a></li>
    </ul>
  </div>

  <h3 class="panel-heading">Categories list</h3>

  @if (count($categories_tree) > 0)

  <div class="panel-body">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Name</th>
          <th>Title</th>
          <th>Type</th>
          <th>Surface</th>
          <!-- <th>Parent</th> -->
          <th>Enabled</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($categories_tree as $category)
          <tr @if (!$category->enabled) style="opacity: 0.5;" @endif>
            <td class="table-text">
              <input type="checkbox" name="" value="{{ $category->id }}" onchange="addCheckedCategory(this.value, this.checked);">
            </td>
            <td class="table-text bold"><a href="#" onclick="editCategory( {{ $category->id }} )" title="Edit">{{ $category->name }}</a></td>
            <td class="table-text">{{ $category->title }}</td>
            <td class="table-text">@if (isset($category_types[$category->type])) {{ $category_types[$category->type] }} @else {{ $category->type }} @endif</td>
            <td class="table-text">@if (isset($surface_types[$category->surface])) {{ $surface_types[$category->surface] }} @else {{ $category->surface }} @endif</td>
            <td class="table-text">@if ($category->enabled) Yes @else No @endif</td>
            <td class="table-text">
              <button type="button" class="close" onclick="deleteCategory({{ $category->id }})" title="Remove Category">&times;</button>
            </td>
          </tr>
          @if (count($category->children) > 0)
            @foreach ($category->children as $sub_category)
              <tr @if (!$sub_category->enabled) style="opacity: 0.5;" @endif>
                <td class="table-text">
                  <input type="checkbox" name="" value="{{ $sub_category->id }}" onchange="addCheckedCategory(this.value, this.checked);">
                </td>
                <td class="table-text bold" style="padding-left: 20px;"><a href="#" onclick="editCategory( {{ $sub_category->id }} )" title="Edit"> - {{ $sub_category->name }}</a></td>
                <td class="table-text">{{ $sub_category->title }}</td>
                <td class="table-text">@if (isset($category_types[$sub_category->type])) {{ $category_types[$sub_category->type] }} @else {{ $sub_category->type }} @endif</td>
                <td class="table-text">@if (isset($surface_types[$sub_category->surface])) {{ $surface_types[$sub_category->surface] }} @else {{ $sub_category->surface }} @endif</td>
                <td class="table-text">@if ($sub_category->enabled) Yes @else No @endif</td>
                <td class="table-text">
                  <button type="button" class="close" onclick="deleteCategory({{ $sub_category->id }})" title="Remove Category">&times;</button>
                </td>
              </tr>
            @endforeach
          @endif
        @endforeach
      </tbody>
    </table>
  </div>

  @endif

</div>
@endsection
