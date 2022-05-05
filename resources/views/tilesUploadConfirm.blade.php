@extends('layouts.app')

@section('content')

@include('common.alerts')
@include('common.errors')

<script type="text/javascript" charset="utf-8" async defer>
/*jslint browser: true */

function removeTile(id) {
    'use strict';
    window.$('#rowTile' + id).fadeOut();
    window.$('#removeTile' + id).val('remove');
}

function removeAllTiles() {
    'use strict';
    window.$('.remove-tiles').val('remove');
    window.$('#formTilesUploadConfirm').submit();

}

function showBigTileImageModal(name, image) {
    'use strict';
    if (name && image) {
        window.$('#bigTileImageModalHeader').text('Tile: ' + name);
        window.$('#bigTileImageModalImg').attr('src', image);
        window.$('#bigTileImageModal').modal('show');
    }
}
</script>


<form id="tilesForm" action="" method="POST">
  {{ csrf_field() }}
  <input id="tilesFormInput" type="hidden" name="" value="">
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

<div class="panel panel-default">

  <h3 class="panel-heading">Added Tiles List</h3>

  <div class="panel-body">
    <form action="/tiles/upload/confirm" method="POST" id="formTilesUploadConfirm">
      {{ csrf_field() }}
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Tile</th>
            <th>Name</th>
            <th>Width</th>
            <th>Height</th>
            <th>Use grout</th>
            <th>Url</th>
            <th>Price</th>
            <th>Enabled</th>
            <th>Remove</th>
          </tr>
        </thead>

        <tbody>
          @if (count($tiles) > 0)
          @foreach ($tiles as $tile)
          <tr id="rowTile{{ $tile->id }}">
            <td class="table-text">
              <img src="{{ $tile->file }}" alt="" class="img-thumbnail" style="max-width: 128px; max-height: 128px; cursor: pointer;" onclick="showBigTileImageModal('{{ $tile->name }}', this.src)">
            </td>
            <td class="table-text">
              <input type="text" name="name[{{ $tile->id }}]" value="{{ $tile->name }}" placeholder="Tile name" required style="width: 100%; min-width: 140px; max-width: 200px;">
            </td>
            <td class="table-text">
              <input type="number" name="width[{{ $tile->id }}]" value="{{ $tile->width }}" placeholder="Width" required style="width: 64px">
            </td>
            <td class="table-text">
              <input type="number" name="height[{{ $tile->id }}]" value="{{ $tile->height }}" placeholder="Height" required style="width: 64px">
            </td>
            <td class="table-text">
              <input type="checkbox" name="grout[{{ $tile->id }}]" value="1" checked>
            </td>
            <td class="table-text">
              <input type="url" name="url[{{ $tile->id }}]" value="" placeholder="Url" style="width: 100%; min-width: 140px;">
            </td>
            <td class="table-text">
              <input type="text" name="price[{{ $tile->id }}]" value="" placeholder="Price" style="width: 80px" pattern="^[0-9]*[.]?[0-9]+$">
            </td>
            <td class="table-text">
              <input type="checkbox" name="enabled[{{ $tile->id }}]" value="1" checked>
            </td>
            <td class="table-text">
              <input type="hidden" name="id[{{ $tile->id }}]" value="{{ $tile->id }}">
              <input type="hidden" name="remove[{{ $tile->id }}]" id="removeTile{{ $tile->id }}" class="remove-tiles" value="">
              <button type="button" class="close" onclick="removeTile({{ $tile->id }});" title="Remove Tile">&times;</button>
            </td>
          </tr>
          @endforeach
          @endif
        </tbody>
      </table>

      <div class="pull-right">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" onclick="removeAllTiles()">Do not save tiles</button>
      </div>
    </form>
  </div>
</div>

@endsection