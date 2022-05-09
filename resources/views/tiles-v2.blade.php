@extends('layouts.app')

@section('content')

<?php
  $config_app_product_finish = config('app.product_finish');
?>

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */
var addTilesFormAction = 'upload';
var formFilters;
var formTileSizes;
function getCheckedTilesArray() {
    'use strict';
    var ids = [];
    var tileCheckBoxes = $('.tiles-list-checkbox');
    Array.prototype.forEach.call(tileCheckBoxes, function (checkBox) {
        if (checkBox.checked) {
            ids.push(checkBox.value);
        }
    });
    return ids;
}
function batchProcessFormOnSubmit() {
    'use strict';
    var checkedTiles = getCheckedTilesArray();
    if (checkedTiles.length > 0) {
        window.$('#batchProcessSelectedTiles').val(JSON.stringify(checkedTiles));
    } else {
        window.$('#warningAlertBox').fadeIn();
        return false;
    }
}
function enableSelectedTiles() {
    'use strict';
    var checkedTiles = getCheckedTilesArray();
    if (checkedTiles.length > 0) {
        window.$('#tilesForm').attr('action', '/tiles/enable');
        window.$('#tilesFormInput').val(JSON.stringify(checkedTiles));
        window.$('#confirmDialogHeader').text('Confirm enabling tiles');
        window.$('#confirmDialogText').text('Please confirm enabling selected ' + checkedTiles.length + ' tiles.');
        window.$('#confirmDialogSubmit').text('Enable Tiles');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}
function disableSelectedTiles() {
    'use strict';
    var checkedTiles = getCheckedTilesArray();
    if (checkedTiles.length > 0) {
        window.$('#tilesForm').attr('action', '/tiles/disable');
        window.$('#tilesFormInput').val(JSON.stringify(checkedTiles));
        window.$('#confirmDialogHeader').text('Confirm disabling tiles');
        window.$('#confirmDialogText').text('Please confirm disabling selected ' + checkedTiles.length + ' tiles.');
        window.$('#confirmDialogSubmit').text('Disable Tiles');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}
function deleteSelectedTiles() {
    'use strict';
    var checkedTiles = getCheckedTilesArray();
    if (checkedTiles.length > 0) {
        window.$('#tilesForm').attr('action', '/tiles/delete');
        window.$('#tilesFormInput').val(JSON.stringify(checkedTiles));
        window.$('#confirmDialogHeader').text('Confirm removing tiles');
        window.$('#confirmDialogText').text('Please confirm removing selected ' + checkedTiles.length + ' tiles.');
        window.$('#confirmDialogSubmit').text('Remove Tiles');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}
function copySelectedTiles() {
    'use strict';
    var checkedTiles = getCheckedTilesArray();
    if (checkedTiles.length > 0) {
        window.$('#tilesForm').attr('action', '/tiles/copy');
        window.$('#tilesFormInput').val(JSON.stringify(checkedTiles));
        window.$('#confirmDialogHeader').text('Confirm copying tiles');
        window.$('#confirmDialogText').text('Please confirm creating copies of selected ' + checkedTiles.length + ' tiles.');
        window.$('#confirmDialogSubmit').text('Create Tiles Copies');
        window.$('#confirmDialog').modal('show');
    } else {
        window.$('#warningAlertBox').fadeIn();
    }
}
function deleteTile(id) {
    'use strict';
    if (id !== undefined && id !== '') {
        window.$('#tilesForm').attr('action', '/tiles/delete');
        window.$('#tilesFormInput').val('[' + id + ']');
        window.$('#confirmDialogHeader').text('Confirm removing tile');
        window.$('#confirmDialogText').text('Please confirm removing tile.');
        window.$('#confirmDialogSubmit').text('Remove Tile');
        window.$('#confirmDialog').modal('show');
    }
}
var formTileOptionIndex = 0;
function prepareExpPropsJsonString() {
    'use strict';
    var i,
        expProps = {},
        option,
        value;
    for (i = 0; i <= formTileOptionIndex; i += 1) {
        option = window.$('#form-tile-expPropsOption' + i).val();
        value = window.$('#form-tile-expPropsValue' + i).val();
        if (option && value) {
            expProps[option.toLowerCase()] = value;
        }
    }
    var offsetWidth = Number(window.$('#form-tile-offset-width').val()) || 0;
    var offsetHeight = Number(window.$('#form-tile-offset-height').val()) || 0;
    if (offsetWidth || offsetHeight) {
        expProps.preparedSetOffset =  {
            x: offsetWidth,
            y: offsetHeight,
        };
    }
    var category = Number(window.$('#form-tile-category').val()) || 0;
    if (category > 0) {
        expProps.categories = [category];
    }
    var buildersRange = window.$('#form-tile-buildersRange').prop('checked');
    if (buildersRange) expProps.buildersRange = buildersRange;
    window.$('#form-tile-expProps').val(JSON.stringify(expProps));
}
function formTileAddOption(options) {
    'use strict';
    if (typeof options !== 'object') options = {};
    formTileOptionIndex += 1;
    var innerHtml,
        option = '',
        value = '',
        values,
        optionIndex = formTileOptionIndex,
        readonly = options.readonly ? 'readonly' : '',
        prepend = false,
        removable = typeof options.removable === 'boolean' ? options.removable : true,
        inputType = 'text';
    if (options && options.filter) {
        prepend = true;
        removable = false;
        option = options.filter.field;
        readonly = 'readonly';
        switch (options.filter.type) {
          case 'slider':
            inputType = 'number';
            break;
          default: // 'checkbox', 'tab'
            values = options.filter.values;
        }
    }
    if (options && options.name) { option = options.name; }
    if (options && options.value) { value = options.value; }
    innerHtml = '<div id="form-tile-option-' + optionIndex + '" class="form-group">' +
        '<div class="col-sm-4">' +
        '<input type="text" name="" id="form-tile-expPropsOption' + optionIndex + '" class="form-control input-sm" value="' + option + '" placeholder="Option name" ' + readonly + '>' +
        '</div>' +
        '<div class="col-sm-7">' +
        '<input type="' + inputType + '" name="" id="form-tile-expPropsValue' + optionIndex + '" class="form-control input-sm" placeholder="Value" value="' + value + '">' +
        '</div>';
    if (removable) {
        innerHtml += '<div class="col-sm-1"><button type="button" class="close" title="Remove option" onclick="window.$(\'#form-tile-option-' + optionIndex + '\').remove();">&times;</button></div>';
    }
    if (values) {
        values = values.split(',');
        innerHtml += '<div>';
        values.forEach(function (value, index) {
            value = value.trim();
            innerHtml += '<span class="col-sm-4 form-check"><label class="form-check-label"><input type="checkbox" id="form-tile-sub-control-check-' + option + index + '" name="form-tile-sub-control-check-' + option + '" value="' + value + '" class="form-tile-sub-control-check-' + option + ' form-check-input"> ' + value + '</label></span>';
        });
        innerHtml += '</div>';
    }
    innerHtml += '</div>';
    if (prepend) {
        window.$('#form-tile-options').prepend(innerHtml);
    } else {
        window.$('#form-tile-options').append(innerHtml);
    }
    if (values) {
        window.$('.form-tile-sub-control-check-' + option).change(function () {
            var checkedString = '',
                i;
            for (i = 0; i < values.length; i += 1) {
                if (window.$('#form-tile-sub-control-check-' + option + i).prop("checked")) {
                    if (checkedString === '') {
                        checkedString = window.$('#form-tile-sub-control-check-' + option + i).val();
                    } else {
                        checkedString += ',' + window.$('#form-tile-sub-control-check-' + option + i).val();
                    }
                }
            }
            window.$('#form-tile-expPropsValue' + optionIndex).val(checkedString);
        });
    }
}
var tileExtraOptionsString = '{!! config('app.tiles_extra_options') !!}';
var tileExtraOptions = tileExtraOptionsString.split(',');
function addTileExtraOptions() {
    'use strict';
    if (tileExtraOptions.length > 0) {
        tileExtraOptions.forEach(function (option) {
            formTileAddOption({
                name: option,
                value: '',
                readonly: true,
                removable: false,
            });
        });
    }
}
function hideCategoryOptions() {
  var categorySelect = document.getElementById('form-tile-category');
  if (!categorySelect) return;
  var surfaceSelect = document.getElementById('form-tile-surface');
  categorySelect.value = '';
  for (var i = 0; i < categorySelect.options.length; i += 1) {
    if (!categorySelect.options[i].dataset.surface || categorySelect.options[i].dataset.surface === surfaceSelect.value) {
      categorySelect.options[i].style.display = '';
    } else {
      categorySelect.options[i].style.display = 'none';
    }
  }
}
function showFilters() {
    'use strict';
    hideCategoryOptions();
    window.$('#form-tile-options').empty();
    addTileExtraOptions();
    window.$('#form-tile-expProps').val('');
    if (!Array.isArray(formFilters)) return;
    formFilters.forEach(function (filter) {
        if (filter.surface === window.$('#form-tile-surface').val()) {
            if (filter.field !== 'size' && filter.field !== 'finish' && filter.field !== 'price') {
                formTileAddOption({ filter: filter });
            }
        }
    });
}
function loadFilters() {
    'use strict';
    window.$.ajax({
        url: '/get/filters',
        success: function (filters) {
            formFilters = filters;
            showFilters();
        }
    });
}
function fillExpProps(expProps) {
    'use strict';
    try {
        expProps = JSON.parse(expProps);
        var option,
            i,
            existOption,
            matchFound;
        for (option in expProps) {
            if (expProps.hasOwnProperty(option) && expProps[option]) {
                switch (option) {
                case 'preparedSetOffset':
                    window.$('#form-tile-offset-width').val(expProps.preparedSetOffset.x || 0);
                    window.$('#form-tile-offset-height').val(expProps.preparedSetOffset.y || 0);
                    break;
                case 'buildersRange':
                    window.$('#form-tile-buildersRange').attr('checked', expProps.buildersRange);
                    break;
                case 'categories':
                    if (Array.isArray(expProps.categories)) {
                        window.$('#form-tile-category').val(expProps.categories[0] || '');
                    }
                    break;
                default:
                    matchFound = false;
                    for (i = 0; i <= formTileOptionIndex; i += 1) {
                        existOption = window.$('#form-tile-expPropsOption' + i).val();
                        if (option === existOption) {
                            matchFound = true;
                            window.$('#form-tile-expPropsValue' + i).val(expProps[option]);
                            if (typeof expProps[option] === 'string') {
                                var values = expProps[option].split(',');
                                var $checkboxes = window.$('#form-tile-option-' + i + ' input[type=checkbox]');
                                $checkboxes.each(function (i, checkbox) {
                                    var val = checkbox.value.trim().toLowerCase();
                                    values.forEach(function (value) {
                                        value = value.trim().toLowerCase();
                                        if (value === val) checkbox.checked = true;
                                    });
                                })
                            }
                            break;
                        }
                    }
                    if (!matchFound) {
                        formTileAddOption({ name: option, value: expProps[option] });
                    }
                    break;
                }
            }
        }
    } catch (error) {
        console.warn(error);
    }
}
function setValueTileOffsetInputs(shape) {
    var tilesOffset = {
        // preparedSet: {
        //     width: 0,
        //     height: 0,
        //     offsetX: 0,
        //     offsetY: 0,
        // },
        notionHerringbon: {
            width: 302,
            height: 294,
            offsetX: 20,
            offsetY: 68,
        },
          riverstoneRohmboid: {
            width: 328,
            height: 264,
            offsetX: 65,
            offsetY: 37,
        },
          rivertsoneChevron: {
            width: 238,
            height: 298,
            offsetX: 0,
            offsetY: 55,
        },
          stoneSystemCombo: {
            width: 711,
            height: 416, // 306
            offsetX: 60,
            offsetY: 0,
        },
    };
    var offsets = tilesOffset[shape];
    window.$('#form-tile-width').val(offsets.width || '');
    window.$('#form-tile-height').val(offsets.height || '');
    window.$('#form-tile-offset-width').val(offsets.offsetX || '');
    window.$('#form-tile-offset-height').val(offsets.offsetY || '');
    window.$('#form-tile-grout').attr('checked', false);
    window.$('#form-tile-offset').show();
}
function showTileOffsetInput(shape) {
    switch (shape) {
        // case 'preparedSet':
        //     setValueTileOffsetInputs(shape);
        //     break;
        case 'notionHerringbon':
            setValueTileOffsetInputs(shape);
            break;
        case 'riverstoneRohmboid':
            setValueTileOffsetInputs(shape);
            break;
        case 'rivertsoneChevron':
            setValueTileOffsetInputs(shape);
            break;
        case 'stoneSystemCombo':
            setValueTileOffsetInputs(shape);
            break;
        default:
            window.$('#form-tile-width').val('');
            window.$('#form-tile-height').val('');
            window.$('#form-tile-offset-width').val('');
            window.$('#form-tile-offset-height').val('');
            window.$('#form-tile-offset').hide();
    }
}
function tileFormReset() {
    'use strict';
    window.$('#addTilesFormBlock').hide();
    document.forms.addTilesForm.reset();
    window.$('#form-tile-enabled').attr('checked', false);
    window.$('#form-tile-file-img').attr('src', '');
    window.$('#form-tile-icon-img').attr('src', '');
    window.$('#form-tile-grout').attr('checked', true);
    window.$('#form-tile-buildersRange').attr('checked', false);
    showTileOffsetInput();
}
function addTiles() {
    'use strict';
    if (addTilesFormAction !== 'upload') {
        tileFormReset();
        window.$('#form-tile-id-box').hide();
        window.$('#form-tile-name-box').hide();
        window.$('#form-tile-file-img-box').hide();
        window.$('#form-tile-icon-img-box').hide();
        window.$('#form-tile-grout-box').hide();
        window.$('#form-tile-url-box').hide();
        window.$('#form-tile-price-box').hide();
        window.$('#form-tile-id').attr('required', false);
        window.$('#form-tile-name').attr('required', false);
        window.$('#form-tile-files').attr('required', true);
        window.$('#form-tile-files').attr('multiple', true);
        window.$('#form-tile-removeTile').hide();
        window.$('#form-tile-saveAsCopy').hide();
        window.$('#form-tile-submit').text('Add tile');
        window.$('#form-tile-file-img').attr('src', '');
        window.$('#form-tile-icon-img').attr('src', '');
        addTilesFormAction = 'upload';
        document.forms.addTilesForm.action = '/tiles/upload';
    }
    showFilters();
    window.$('#addTilesFormBlock').slideDown();
}
function editTile(id) {
    'use strict';
    addTilesFormAction = 'update';
    document.forms.addTilesForm.action = '/tile/update';
    tileFormReset();
    window.$.ajax({
        url: '/get/tile/' + id,
        success: function (tile) {
            showTileOffsetInput(tile.shape);
            window.$('#form-tile-id-box').show();
            window.$('#form-tile-name-box').show();
            window.$('#form-tile-file-img-box').show();
            window.$('#form-tile-icon-img-box').show();
            window.$('#form-tile-grout-box').show();
            window.$('#form-tile-url-box').show();
            window.$('#form-tile-price-box').show();
            window.$('#form-tile-id').attr('required', true);
            window.$('#form-tile-name').attr('required', true);
            window.$('#form-tile-files').attr('required', false);
            window.$('#form-tile-files').attr('multiple', false);
            window.$('#form-tile-removeTile').show();
            window.$('#form-tile-saveAsCopy').show();
            window.$('#form-tile-submit').text('Update tile');
            window.$('#form-tile-id').val(tile.id);
            window.$('#form-tile-name').val(tile.name);
            if (Number(tile.enabled)) { window.$('#form-tile-enabled').attr('checked', true); }
            window.$('#form-tile-file-img').attr('src', tile.file);
            window.$('#form-tile-icon-img').attr('src', tile.icon);
            window.$('#form-tile-surface').val(tile.surface);
            showFilters();
            window.$('#form-tile-shape').val(tile.shape);
            window.$('#form-tile-width').val(tile.width);
            window.$('#form-tile-height').val(tile.height);
            window.$('#form-tile-finish').val(tile.finish);
            window.$('#form-tile-grout').attr('checked', Number(tile.grout) === 1 ? true : false);
            window.$('#form-tile-url').val(tile.url);
            window.$('#form-tile-price').val(tile.price);
            window.$('#form-tile-rotoPrintSetName').val(tile.rotoPrintSetName);
            window.$('#form-tile-accessLevel').val(tile.access_level || 0);
            if (tile.expProps) { fillExpProps(tile.expProps); }
            window.$('#addTilesFormBlock').slideDown();
        }
    });
}
function clearTilesFilterForm() {
    'use strict';
    window.$('#filterTileName').val('');
    window.$('#filterTileShape').val('');
    window.$('#filterTileWidth').val('');
    window.$('#filterTileHeight').val('');
    window.$('#filterTileSurface').val('');
    window.$('#filterTileFinish').val('');
    window.$('#filterTileUrl').val('');
    window.$('#filterTilePrice').val('');
    window.$('#filterTileRotoPrintSetName').val('');
    window.$('#filterTileExpProps').val('');
    window.$('#filterTileEnabled').val('');
}
function fillTilesFilterForm() {
    'use strict';
    window.$('#filterTileShape').val(window.$('#filterTileShape').attr('value'));
    window.$('#filterTileSurface').val(window.$('#filterTileSurface').attr('value'));
    window.$('#filterTileFinish').val(window.$('#filterTileFinish').attr('value'));
    window.$('#filterTileEnabled').val(window.$('#filterTileEnabled').attr('value'));
    if (window.$('#filterTileName').val() !== '') { window.$('#filterTileName').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileShape').val() !== '') { window.$('#filterTileShape').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileWidth').val() !== '') { window.$('#filterTileWidth').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileHeight').val() !== '') { window.$('#filterTileHeight').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileSurface').val() !== '') { window.$('#filterTileSurface').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileFinish').val() !== '') { window.$('#filterTileFinish').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileUrl').val() !== '') { window.$('#filterTileUrl').css('background-color', '#aaffaa'); }
    if (window.$('#filterTilePrice').val() !== '') { window.$('#filterTilePrice').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileRotoPrintSetName').val() !== '') { window.$('#filterTileRotoPrintSetName').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileExpProps').val() !== '') { window.$('#filterTileExpProps').css('background-color', '#aaffaa'); }
    if (window.$('#filterTileEnabled').val() !== '') { window.$('#filterTileEnabled').css('background-color', '#aaffaa'); }
}
function changeFilterInputColor() {
    'use strict';
    var filterInput = window.$(this);
    if (filterInput.val() === '') {
        filterInput.css('background-color', 'inherit');
    } else {
        filterInput.css('background-color', '#aaffaa');
    }
}
function showBigTileImageModal(name, image) {
    'use strict';
    if (name && image) {
        window.$('#bigTileImageModalHeader').text('Tile: ' + name);
        window.$('#bigTileImageModalImg').attr('src', image);
        window.$('#bigTileImageModal').modal('show');
    }
}
function selectItem(checked) {
    'use strict';
    window.$('#warningAlertBox').slideUp();
    window.$('#selectAllItemsOnCurrentPages').prop('checked', false);
    window.$('#selectAllItemsOnAllPages').prop('checked', false);
    window.$('#selectAllFiltered').val(false);
}
function selectAllItemsOnCurrentPages(checked) {
    'use strict';
    window.$('.tiles-list-checkbox').prop('checked', checked);
    window.$('#selectAllItemsOnAllPages').prop('checked', false);
    window.$('#selectAllFiltered').val(false);
    window.$('#warningAlertBox').slideUp();
}
function selectAllItemsOnAllPages(checked) {
    'use strict';
    window.$('.tiles-list-checkbox').prop('checked', checked);
    window.$('#selectAllItemsOnCurrentPages').prop('checked', false);
    window.$('#selectAllFiltered').val(checked);
    window.$('#warningAlertBox').slideUp();
}
document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    loadFilters();
    fillTilesFilterForm();
    window.$('.tiles-filter-input').on('change', changeFilterInputColor);
    window.$('#form-tile-shape').change(function () {
        showTileOffsetInput(this.value);
    });
    document.forms.addTilesForm.onsubmit = function () {
        prepareExpPropsJsonString();
    }
});
</script>


@include('common.alerts')
@include('common.errors')


<div id="addTilesFormBlock" class="panel-body" style="display: none;">
  <form id="addTilesForm" name="addTilesForm" action="/tiles/upload" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group required" id="form-tile-id-box" style="display: none;">
      <label for="form-tile-id" class="col-sm-3 control-label">Id</label>
      <div class="col-sm-3">
        <input type="text" name="id" id="form-tile-id" class="form-control" readonly="readonly">
      </div>
      <div class="col-sm-3">
        <label><input type="checkbox" name="enabled" id="form-tile-enabled" value="1"> Enabled</label>
      </div>
    </div>

    <div class="form-group required" id="form-tile-name-box" style="display: none;">
      <label for="form-tile-name" class="col-sm-3 control-label">Name</label>
      <div class="col-sm-6">
        <input type="text" name="name" id="form-tile-name" class="form-control" placeholder="Name" maxlength="255">
      </div>
    </div>

    <div class="form-group">
      <label for="form-tile-surface" class="col-sm-3 control-label">Surface</label>
      <div class="col-sm-6">
        <select name="surface" id="form-tile-surface" class="form-control" onchange="showFilters();" required>
          @if (count($surfaceTypes) > 0)
          @foreach ($surfaceTypes as $type => $display_name)
            <option value="{{ $type }}">{{ $display_name }}</option>
          @endforeach
          @endif
        </select>
      </div>
      <span class="help-block">Choose this item first</span>
    </div>

    @if (config('app.use_product_category'))
    <div class="form-group">
      <label for="form-tile-category" class="col-sm-3 control-label">Category</label>
      <div class="col-sm-6">
        <select name="category" id="form-tile-category" class="form-control">
          @if (count($product_categories_tree) > 0)
          <option></option>
          @foreach ($product_categories_tree as $category)
            <option value="{{ $category->id }}" data-surface="{{ $category->surface }}">{{ $category->name }}</option>
            @if (count($category->children) > 0)
            @foreach ($category->children as $sub_category)
              <option value="{{ $sub_category->id }}" data-surface="{{ $category->surface }}">&nbsp;&nbsp;&nbsp; - {{ $sub_category->name }}</option>
            @endforeach
            @endif
          @endforeach
          @endif
        </select>
      </div>
      <span class="help-block">Choose this item first</span>
    </div>
    @endif

    <div class="form-group">
      <label for="form-tile-shape" class="col-sm-3 control-label">Shape</label>
      <div class="col-sm-6">
        <select name="shape" id="form-tile-shape" class="form-control">
          <option value="square" selected>Square</option>
          <option value="rectangle">Rectangle</option>
          <option value="hexagon">Hexagon</option>
          <option value="diamond">Diamond</option>
          <option value="quadSet">Quad Set</option>
          <!-- <option value="preparedSet">Prepared Set</option> -->
          <option value="notionHerringbon">Notion Herringbon</option>
          <option value="riverstoneRohmboid">Riverstone Rohmboid</option>
          <option value="rivertsoneChevron">Rivertsone Chevron</option>
          <option value="stoneSystemCombo">Stone System Combo</option>
        </select>
      </div>
    </div>

    <div class="form-group required">
      <label for="form-tile-width" class="col-sm-3 control-label">Size</label>
      <div class="col-sm-3">
        <input type="number" name="width" id="form-tile-width" class="form-control" placeholder="Width" required>
      </div>
      <div class="col-sm-3">
        <input type="number" name="height" id="form-tile-height" class="form-control" placeholder="Height" required>
      </div>
      <div class="col-sm-offset-3 col-sm-6" id="form-tile-sizes"></div>
    </div>

    <div class="form-group" id="form-tile-offset" style="display: none;">
      <label for="form-tile-width" class="col-sm-3 control-label">Offset</label>
      <div class="col-sm-3">
        <input type="number" id="form-tile-offset-width" class="form-control" placeholder="Offset Left">
      </div>
      <div class="col-sm-3">
        <input type="number" id="form-tile-offset-height" class="form-control" placeholder="Offset Top">
      </div>
    </div>

    @if (!$config_app_product_finish)
    <div class="form-group">
      <label for="form-tile-finish" class="col-sm-3 control-label">Finish</label>
      <div class="col-sm-6">
        <select name="finish" id="form-tile-finish" class="form-control">
          <option value="glossy" selected>Glossy</option>
          <option value="semi_polished">Semi polished</option>
          <option value="textured">Textured</option>
          <option value="matt">Matt</option>
        </select>
      </div>
    </div>
    @endif

    <div class="form-group required">
      <label for="form-tile-files" class="col-sm-3 control-label">Files</label>
      <div id="form-tile-file-img-box" class="col-sm-2" style="display: none;">
        <img id="form-tile-file-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigTileImageModal(window.$('#form-tile-name').val(), this.src)">
      </div>
      <div class="col-sm-4">
        <input type="file" name="files[]" id="form-tile-files" class="form-control" accept="image/*" required multiple>
      </div>
      @if (!config('app.unlimited_image_size'))
        <span class="col-sm-3 help-block">Image must be less than 2 MB and resolution less than 2048x2048 pixels.</span>
      @endif
    </div>

    @if (config('app.tile_set_different_icon'))
    <div class="form-group">
      <label for="form-tile-icon" class="col-sm-3 control-label">Tile Icon</label>
      <div id="form-tile-icon-img-box" class="col-sm-2" style="display: none;">
        <img id="form-tile-icon-img" src="" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px;cursor: pointer;" onclick="showBigTileImageModal(window.$('#form-tile-name').val(), this.src)">
      </div>
      <div class="col-sm-4">
        <input type="file" name="icon" id="form-tile-icon" class="form-control" accept="image/*">
      </div>
      <span class="col-sm-3 help-block">Image must be less than 512 KB and resolution less than 512x512 pixels.</span>
    </div>
    @endif

    <div class="form-group">
      <div class="col-sm-3"></div>
      <div id="form-tile-grout-box" class="col-sm-2" style="display: none;">
        <label class="control-label">
          <input type="checkbox" name="grout" id="form-tile-grout" value="1">
          Use grout
        </label>
      </div>

      @if (config('app.tiles_builders_range'))
      <div class="col-sm-2">
        <label class="control-label">
          <input type="checkbox" id="form-tile-buildersRange" value="1">
          Builders Range
        </label>
      </div>
      @endIf
    </div>

    <div class="form-group" id="form-tile-url-box" style="display: none;">
      <label for="form-tile-url" class="col-sm-3 control-label">Url</label>
      <div class="col-sm-6">
        <input type="url" name="url" id="form-tile-url" class="form-control" placeholder="Url to product page">
      </div>
    </div>

    <div class="form-group" id="form-tile-price-box" style="display: none;">
      <label for="form-tile-price" class="col-sm-3 control-label">Price</label>
      <div class="col-sm-6">
        <input type="text" name="price" id="form-tile-price" class="form-control" placeholder="Price" pattern="^[0-9]*[.]?[0-9]+$">
      </div>
    </div>

    <div class="form-group">
      <label for="form-tile-rotoPrintSetName" class="col-sm-3 control-label">Variant Set</label>
      <div class="col-sm-6">
        <input type="text" name="rotoPrintSetName" id="form-tile-rotoPrintSetName" class="form-control" placeholder="Variant Set Name">
      </div>
      <span class="col-sm-3 help-block">If field not empty, all added tiles will be uploaded as one tile set.</span>
    </div>

    @if (config('app.tiles_access_level'))
    <div class="form-group">
      <label for="form-tile-accessLevel" class="col-sm-3 control-label">Show for users</label>
      <div class="col-sm-6">
        <select name="accessLevel" id="form-tile-accessLevel" class="form-control">
          <option value="0" selected>All</option>
          <option value="1">Guests</option>
          <option value="2">Registered</option>
          <option value="3">Editors</option>
          <option value="4">Administrators</option>
        </select>
      </div>
    </div>
    @endif

    <div class="form-group">
      <label for="form-tile-expProps" class="col-sm-3 control-label">Filters</label>
      <div class="col-sm-6">
        <input type="hidden" name="expProps" id="form-tile-expProps" class="form-control" placeholder="Expandable Properties" readonly>
        <div id="form-tile-options"></div>
        <div class="form-group">
          <div class="col-sm-4">
            <button type="button" class="btn btn-default btn-xs" onclick="formTileAddOption()">Add option</button>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-6">
        <span id="form-tile-removeTile" style="display: none;">
          <button type="button" class="btn btn-default" onclick="deleteTile(window.$('#form-tile-id').val());" title="Remove Tile"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        </span>
        <span class="pull-right">
          <input type="hidden" name="dataAction" id="form-tile-dataAction" value="update">
          <button type="submit" class="btn btn-primary" id="form-tile-submit" onclick="window.$('#form-tile-dataAction').val('update');">Add tile</button>
          <button type="submit" class="btn btn-default" id="form-tile-saveAsCopy" onclick="window.$('#form-tile-dataAction').val('copy');" style="display: none;">Save As Copy</button>
          <button type="button" class="btn btn-default" onclick="tileFormReset();">Cancel</button>
        </span>
      </div>
    </div>

  </form>
</div>


<form id="tilesForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="tilesFormInput" type="hidden" name="selectedTiles" value="">
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
        <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#tilesForm').submit();">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="bigTileImageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="bigTileImageModalHeader" class="modal-title">Tile image</h4>
      </div>
      <div class="modal-body" style="text-align: center;">
        <img id="bigTileImageModalImg" src="" alt="" style="max-width: 512px; max-height: 512px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="batchProcess" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form name="batchProcessForm" action="/tiles/batch" method="POST" class="form-horizontal" onsubmit="return batchProcessFormOnSubmit()">
        {{ csrf_field() }}
        <input type="hidden" id="allTileIds" name="allTileIds" value="@if (isset($tileIds)) [{{ implode(",", $tileIds) }}] @endif">
        <input type="hidden" id="selectAllFiltered" name="selectAllFiltered">
        <input type="hidden" id="batchProcessSelectedTiles" name="selectedTiles">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Batch process selected tiles</h4>
        </div>
        <div class="modal-body">
          <!-- <div>
            <h5>Enabled</h5>
            <select name="enabled" class="form-control">
              <option value="original" selected>- Keep original Status -</option>
              <option value="enabled">Enabled</option>
              <option value="disabled">Disabled</option>
            </select>
          </div> -->
          <div>
            <h5>Surface</h5>
            <select name="surface" class="form-control" onchange="showFilters();" required>
              <option value="original">- Keep same surface -</option>
              @if (count($surfaceTypes) > 0)
              @foreach ($surfaceTypes as $type => $display_name)
                <option value="{{ $type }}">{{ $display_name }}</option>
              @endforeach
              @endif
            </select>
          </div>

          @if (!$config_app_product_finish)
          <div>
            <h5>Finish</h5>
            <select name="finish" class="form-control">
              <option value="original" selected>- Keep same finish -</option>
              <option value="glossy">Glossy</option>
              <option value="semi_polished">Semi polished</option>
              <option value="textured">Textured</option>
              <option value="matt">Matt</option>
            </select>
          </div>
          @endif

          @if (config('app.tiles_access_level'))
          <div>
            <h5>Show for users</h5>
            <select name="accessLevel" class="form-control">
              <option value="original" selected>- Keep same access level -</option>
              <option value="0">All</option>
              <option value="1">Guests</option>
              <option value="2">Registered</option>
              <option value="3">Editors</option>
              <option value="4">Administrators</option>
            </select>
          </div>
          @endif
          <!-- <div>
            <h5>Variant Set</h5>
            <input type="checkbox" name="updatedRotoPrintSetName" onchange="window.$('#batchRotoPrintSetName').prop('disabled', !this.checked);">
            <input type="text" id="batchRotoPrintSetName" name="rotoPrintSetName" placeholder="Variant Set Name" disabled>
          </div> -->
        </div>
        <div class="modal-footer">
          <span style="margin-right: 24px;">
            <label><input type="radio" name="radioBatchProcess" value="move" checked>
            Move</label>
          </span>
          <span style="margin-right: 24px;">
            <label><input type="radio" name="radioBatchProcess" value="copy">
            Copy</label>
          </span>
          <button type="submit" class="btn btn-primary">Process</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="pull-right">
    <button class="btn btn-default btn-sm" onclick="addTiles();">+ Add tile</button>
    <button class="btn btn-default btn-sm" onclick="window.$('#batchProcess').modal();">Batch</button>
    <!-- <label class="btn btn-default btn-sm" style="padding-top: 3px; padding-bottom: 3px;">
      <input type="checkbox" id="selectAllItemsOnCurrentPages" onchange="selectAllItemsOnCurrentPages(this.checked);" title="Select all Tiles on this page">
      Select All
    </label> -->
    <label class="btn btn-default btn-sm" style="padding-top: 3px; padding-bottom: 3px;">
      <input type="checkbox" id="selectAllItemsOnAllPages" onchange="selectAllItemsOnAllPages(this.checked);" title="Select All items on all pages">
      Select All items on all pages
    </label>
    <span class="dropdown">
      <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
        With selected
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#" onclick="enableSelectedTiles();">Enable</a></li>
        <li><a href="#" onclick="disableSelectedTiles();">Disable</a></li>
        <!-- <li class="divider"></li>
        <li><a href="#" onclick="copySelectedTiles();">Make Copies</a></li> -->
        <li class="divider"></li>
        <li><a href="#" onclick="deleteSelectedTiles();">Remove</a></li>
      </ul>
    </span>
  </div>

  <h3 class="panel-heading">Tiles list</h3>

  <form id="tilesFilterForm" action="/tiles" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}
  </form>

  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>
            <input type="checkbox" id="selectAllItemsOnCurrentPages" onchange="selectAllItemsOnCurrentPages(this.checked);" title="Select all Tiles on this page">
          </th>
          <th>Tile</th>
          <th>Name</th>
          <th>Shape</th>
          <th>Size</th>
          <th>Surface</th>
          @if (!$config_app_product_finish)<th>Finish</th>@endif
          <th>Url</th>
          <th>Price</th>
          <th>Variant Set</th>
          <th>Expandable Properties</th>
          @if (config('app.tiles_access_level'))<th>Access Level</th>@endif
          <th>Enabled</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        <tr style="white-space: nowrap;">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>
            <input form="tilesFilterForm" type="text" name="filterTileName" id="filterTileName" @if (isset($filter)) value="{{ $filter->filterTileName }}" @endif class="tiles-filter-input" style="width: 100%;">
          </td>
          <td>
            <select form="tilesFilterForm" name="filterTileShape" id="filterTileShape" @if (isset($filter)) value="{{ $filter->filterTileShape }}" @endif style="height: 26px; width: 100%;" class="tiles-filter-input">
              <option value="">All</option>
              <option value="square">Square</option>
              <option value="rectangle">Rectangle</option>
              <option value="hexagon">Hexagon</option>
              <option value="diamond">Diamond</option>
              <option value="quadSet">Quad Set</option>
              <!-- <option value="preparedSet">Prepared Set</option> -->
              <option value="notionHerringbon">Notion Herringbon</option>
              <option value="riverstoneRohmboid">Riverstone Rohmboid</option>
              <option value="rivertsoneChevron">Rivertsone Chevron</option>
              <option value="stoneSystemCombo">Stone System Combo</option>
            </select>
          </td>
          <td>
            <input form="tilesFilterForm" type="text" name="filterTileWidth" id="filterTileWidth" @if (isset($filter)) value="{{ $filter->filterTileWidth }}" @endif size="2" class="tiles-filter-input">x<input form="tilesFilterForm" type="text" name="filterTileHeight" id="filterTileHeight" @if (isset($filter)) value="{{ $filter->filterTileHeight }}" @endif size="2" class="tiles-filter-input">
          </td>
          <td>
            <select form="tilesFilterForm" name="filterTileSurface" id="filterTileSurface" @if (isset($filter)) value="{{ $filter->filterTileSurface }}" @endif style="height: 26px; width: 100%;" class="tiles-filter-input">
              <option value="" selected>All</option>
              @if (count($surfaceTypes) > 0)
              @foreach ($surfaceTypes as $type => $display_name)
                <option value="{{ $type }}">{{ $display_name }}</option>
              @endforeach
              @endif
            </select>
          </td>
          @if (!$config_app_product_finish)
          <td>
            <select form="tilesFilterForm" name="filterTileFinish" id="filterTileFinish" @if (isset($filter)) value="{{ $filter->filterTileFinish }}" @endif style="height: 26px; width: 100%;" class="tiles-filter-input">
              <option value="" selected>All</option>
              <option value="glossy">Glossy</option>
              <option value="semi_polished">Semi polished</option>
              <option value="textured">Textured</option>
              <option value="matt">Matt</option>
            </select>
          </td>
          @endif
          <td>
            <input form="tilesFilterForm" type="text" name="filterTileUrl" id="filterTileUrl" @if (isset($filter)) value="{{ $filter->filterTileUrl }}" @endif class="tiles-filter-input" style="width: 100%;">
          </td>
          <td>
            <input form="tilesFilterForm" type="text" name="filterTilePrice" id="filterTilePrice" @if (isset($filter)) value="{{ $filter->filterTilePrice }}" @endif class="tiles-filter-input" style="width: 100%;">
          </td>
          <td>
            <input form="tilesFilterForm" type="text" name="filterTileRotoPrintSetName" id="filterTileRotoPrintSetName" @if (isset($filter)) value="{{ $filter->filterTileRotoPrintSetName }}" @endif class="tiles-filter-input" style="width: 100%;">
          </td>
          <td>
            <input form="tilesFilterForm" type="text" name="filterTileExpProps" id="filterTileExpProps" @if (isset($filter)) value="{{ $filter->filterTileExpProps }}" @endif class="tiles-filter-input" style="width: 100%;">
          </td>
          @if (config('app.tiles_access_level'))<td>&nbsp;</td>@endif
          <td>
            <select form="tilesFilterForm" name="filterTileEnabled" id="filterTileEnabled" @if (isset($filter)) value="{{ $filter->filterTileEnabled }}" @endif style="height: 26px; width: 100%;" class="tiles-filter-input">
              <option value="" selected>All</option>
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </td>
          <td colspan="3">
            <button form="tilesFilterForm" type="submit" class="btn btn-primary btn-xs" title="Apply filter">Filter</button>
            <button form="tilesFilterForm" type="submit" class="btn btn-default btn-xs" onclick="clearTilesFilterForm();" title="Clear filter and show all data">Show All</button>
          </td>
        </tr>

        @if (count($tiles) > 0)
        @foreach ($tiles as $tile)
        <tr @if (!$tile->enabled) style="opacity: 0.5;" @endif>
          <td class="table-text">
            <input type="checkbox" value="{{ $tile->id }}" onchange="selectItem(this.checked)" class="tiles-list-checkbox">
          </td>
          <td class="table-text">
            <img src="{{ $tile->icon }}" alt="" class="img-thumbnail" style="max-width: 64px; max-height: 64px; cursor: pointer;" onclick="showBigTileImageModal('{{ $tile->name }}', '{{ $tile->file }}')">
          </td>
          <td class="table-text bold"><a href="#" onclick="editTile( {{ $tile->id }} )" title="Edit Tile">{{ $tile->name }}</a></td>
          <td class="table-text">{{ $tile->shape }}</td>
          <td class="table-text">{{ $tile->width . 'x' . $tile->height }}</td>
          <td class="table-text">@if (isset($surfaceTypes[$tile->surface])) {{ $surfaceTypes[$tile->surface] }} @else {{ $tile->surface }} @endif</td>
          @if (!$config_app_product_finish)
          <td class="table-text">{{ $tile->finish }}</td>
          @endif
          <td class="table-text" title="{{ $tile->url }}">@if ($tile->url) {{ substr($tile->url, 0, 8) }} @if (mb_strlen($tile->url) > 8) ... @endif @endif</td>
          <td class="table-text">{{ $tile->price }}</td>
          <td class="table-text" title="{{ $tile->rotoPrintSetName }}">@if ($tile->rotoPrintSetName) {{ substr($tile->rotoPrintSetName, 0, 8) }} @if (mb_strlen($tile->rotoPrintSetName) > 8) ... @endif @endif</td>
          <td class="table-text" title="{{ $tile->expProps }}">@if ($tile->expProps) {{ substr($tile->expProps, 0, 8) }} @if (mb_strlen($tile->expProps) > 8) ... @endif @endif</td>

          <?php if (config('app.tiles_access_level') && isset($tile->access_level)) {
              $roles = ['All', 'Guests', 'Registered', 'Editors', 'Administrators'];
              $access_level = $roles[$tile->access_level];
              echo "<td class=\"table-text\">$access_level</td>";
          } ?>

          <td class="table-text">@if ($tile->enabled) Yes @else <strong>No</strong> @endif</td>
          <td class="table-text">
            <button type="button" class="close" onclick="deleteTile({{ $tile->id }})" title="Remove Tile">&times;</button>
          </td>
        </tr>
        @endforeach
        @else
          No one tile found. Check filter.
        @endif
      </tbody>
    </table>
    <div class="page-links" style="text-align: center;">{{ $tiles->links() }}</div>
  </div>
</div>

@endsection
