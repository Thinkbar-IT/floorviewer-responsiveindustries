@extends('layouts.app')

@section('content')

@include('common.alerts')
@include('common.errors')


<saved-rooms></saved-rooms>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <form id="savedRoomsForm" action="" method="POST">
              {{ csrf_field() }}
              <input id="savedRoomsFormInput" type="hidden" name="selectedSavedRooms" value="">
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
                    <button id="confirmDialogSubmit" type="submit" class="btn btn-primary" onclick="window.$('#savedRoomsForm').submit();">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-default">

              <div class="dropdown pull-right">
                <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                  With selected
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="#" onclick="HomePage.deleteSelectedSavedRooms();">Remove</a></li>
                </ul>
              </div>

              <h3 class="panel-heading">Saved rooms list</h3>

              @if (count($savedRooms) > 0)

              <div class="panel-body">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th colspan="2">Room</th>
                      <th>Url</th>
                      <th colspan="3">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach ($savedRooms as $savedRoom)
                      @if ($savedRoom->room)
                      <tr>
                        <td class="table-text">{{ $savedRoom->room->name }}</td>
                        <td class="table-text">
                          <a href="/room/url/{{ $savedRoom->urlWithParams }}">
                            <img src="@if (isset($savedRoom->image)) {{ $savedRoom->image }} @else {{ $savedRoom->room->iconfile }} @endif" alt="" style="max-width: 128px; max-height: 100px;">
                          </a>
                        </td>
                        <td class="table-text">
                          @if (!config('app.hide_engine_icon'))
                            <a href="/room/url/{{ $savedRoom->urlWithParams }}" title="/room/url/{{ $savedRoom->url }}">
                            @if (isset($savedRoom->engine) && $savedRoom->engine == '2d')
                              <img src="/img/icons/2d.png" alt="" width="32">
                            @else
                              <img src="/img/icons/3d.png" alt="" width="32">
                            @endif
                            </a>
                          @endif
                          <a href="/room/url/{{ $savedRoom->urlWithParams }}">{{ $savedRoom->url }}</a>
                        </td>
                        <td class="table-text">
                          <input type="checkbox" name="" value="{{ $savedRoom->id }}" onchange="HomePage.addCheckedSavedRoom(this.value, this.checked);">
                        </td>
                        <td class="table-text">
                          <button type="button" class="close" onclick="HomePage.deleteSavedRoom({{ $savedRoom->id }})" title="Remove Room">&times;</button>
                        </td>
                      </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
                <div class="page-links" style="text-align: center;">{{ $savedRooms->links() }}</div>
              </div>

              @endif
            </div>
        </div>
    </div>
</div>
@endsection
