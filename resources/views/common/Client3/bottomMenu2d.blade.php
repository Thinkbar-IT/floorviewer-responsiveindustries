<div id="bottomPanelMenu">
    <button id="bottomMenuRoomInfo" class="bottom-menu-text" title="@lang('Product Information')">
        <img src="/img/Client3/product-information.png" alt="">
        <span>@lang('Product Information')</span>
    </button>
    <button id="bottomMenuRoomSelect" class="bottom-menu-text" title="@lang('Select Room')" onclick="window.$('#dialogRoomSelect').modal('show');">
        <img src="/img/Client3/select-room.png" alt="">
        <span>@lang('Select Room')</span>
    </button>
    <button id="bottomMenuCapture" title="@lang('Capture')" onclick="window.$('#dialogSaveModalBox').modal('show');">
        <img src="/img/icons/capture.png" alt="">
    </button>
    <button id="bottomMenuFullScreen" title="@lang('Full Screen')">
        <img id="bottomMenuFullScreenImg" src="/img/icons/fullscreen.png" alt="">
        <img id="bottomMenuCancelFullScreenImg" src="/img/icons/normalscreen.png" alt="">
    </button>
</div>
