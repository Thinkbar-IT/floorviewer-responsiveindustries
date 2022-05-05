<div id="bottomPanelMenu">
    <button id="bottomMenuRoomSelect" class="bottom-menu-text" title="@lang('Select Room')" onclick="window.$('#dialogRoomSelect').modal('show');">
        @lang('Select Room')
    </button>

    @if (count(config('app.locales')) > 1)
    <span class="dropup" style="position: static;">
        <button title="Switch Language" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ config('app.locale') }}
        </button>
            <ul id="bottomDropdownMenuMapsSize" class="dropdown-menu" aria-labelledby="bottomMenuMapsSize" style="left: unset; right: 0;">
            @foreach (config('app.locales') as $locale)
                <li><a href="/lang/{{ $locale }}" @if ($locale == config('app.locale')) class="selected" @endif >
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    {{ $locale }}
                </a></li>
            @endforeach
            </ul>
    </span>
    @endif

    <button id="bottomMenuRoomInfo" title="@lang('Room Info')">
        <img src="/img/icons/info.png" alt="">
    </button>
    <button id="bottomMenuCapture" title="@lang('Capture')" onclick="window.$('#dialogSaveModalBox').modal('show');">
        <img src="/img/icons/capture.png" alt="">
    </button>
    @if (config('app.tiles_designer'))
    <button id="bottomMenuTilesDesigner" title="Tiles Designer" onclick="window.$('#tilesDesigner').modal('show');">
        <img src="/img/icons/brush.png" alt="">
    </button>
    @endif
  
    <button id="bottomMenuFullScreen" title="@lang('Full Screen')">
        <img id="bottomMenuFullScreenImg" src="/img/icons/fullscreen.png" alt="">
        <img id="bottomMenuCancelFullScreenImg" src="/img/icons/normalscreen.png" alt="">
    </button>
</div>
