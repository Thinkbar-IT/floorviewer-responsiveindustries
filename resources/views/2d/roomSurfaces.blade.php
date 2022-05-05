@extends('layouts.app')

@section('content')

@include('common.alerts')
@include('common.errors')


@include('js_constants.lang')
@include('js_constants.ConfigTileVisualizer')

<form  id="updateSurfacesForm" action="/room2d/surfaces/update" method="POST">
  {{ csrf_field() }}
  <input type="hidden" name="roomId" id="roomId" @if (isset($roomId)) value="{{ $roomId }}" @endif>
  <input type="hidden" name="surfaces" id="roomTiledSurfacesData">
</form>

<div class="panel panel-default">
    <h3 class="panel-heading">2D Room Tiled Surfaces</h3>

    <div id="container" class="room-canvas-container" style="margin: 16px; padding: 0;">
        <canvas id="roomCanvas" class="room-canvas"></canvas>
        <canvas id="pointsCanvas" class="room-canvas" style="position: absolute;"></canvas>

        <div id="loadAnimationContainer" style="display: none;">
            <p>Applying Tiles</p>
            <div class="circles marginLeft">
                <span class="circle_1 circle"></span>
                <span class="circle_2 circle"></span>
                <span class="circle_3 circle"></span>
            </div>
        </div>

        <div id="roomEditPanel" class="top-panel" style="position: absolute; top: 0; max-height: 100%; cursor: e-resize;">

            <div class="form-group text-right edit-panel-group-margin">
                <button type="button" id="btnUpdateRoom" class="btn btn-primary btn-sm">Update and Close</button>
                <button type="button" id="btnUpdateSurfaces" class="btn btn-default btn-sm" title="Update surfaces in view"><span class="glyphicon glyphicon-refresh"   aria-hidden="true"></span></button>

                <div class="top-panel-box">
                    <div class="input-group">
                        <span class="input-group-addon" title="Camera Field of View (degree)">FoV</span>
                        <input type="number" id="cameraFov" value="30" max="170" min="1" class="form-control input-sm" title="Camera Field of View (degree)" style="min-width: 60px;">
                        <span class="input-group-addon" title="View Horizontal Offset (pixels)">Offset: X</span>
                        <input type="number" id="viewHorizontalOffset" value="0" class="form-control input-sm" title="View Horizontal Offset (pixels)">
                        <span class="input-group-addon" title="View Vertical Offset (pixels)">Y</span>
                        <input type="number" id="viewVerticalOffset" value="0" class="form-control input-sm" title="View Vertical Offset (pixels)">
                    </div>
                </div>
            </div>

            <div id="roomTiledSurfacesOptions" class="top-panel-box overflow-y"></div>

            <div class="form-group text-right edit-panel-group-margin">
                <button type="button" id="btnAddTiledSurface" class="btn btn-default btn-sm">Add Tiled Surface</button>
            </div>
        </div>

        <div id="sourceLoadProgressBarContainer">
          <div class="progress">
            <div id="sourceLoadProgressBar" class="progress-bar progress-bar-striped active" role="progressbar"
            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
              10%
            </div>
          </div>
        </div>
    </div>
</div>

<div id="dialogSelectTile" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Select Tile</h3>
                <h5>Loaded only 16 first tiles</h5>
            </div>
            <div id="tilesList" class="modal-body" style="max-height: 400px; overflow-x: auto;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="/js/room/three.min.js"></script>

@if (config('app.js_as_module'))
<script type="module" src="/js/src/2d/interior2dEdit.js"></script>
@else
<script src="/js/room/2dEdit.min.js"></script>
@endif

@endsection
