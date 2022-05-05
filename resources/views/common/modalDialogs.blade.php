
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
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Cancel')</button>
            </div>
        </div>
    </div>
</div>

<div id="dialogSaveModalBox" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">@lang('Save design')</h3>
            </div>
            <div class="modal-body text-center">
                <button id="btnDialogSaveImage" class="dialog-modal-box-button">@lang('Save design as image')</button>
                <button id="btnDialogSavePdf" class="dialog-modal-box-button">@lang('Save with info as PDF')</button>
                <button id="btnDialogSaveScene" class="dialog-modal-box-button">@lang('Save design in account')</button>

                @include('common.share')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<div id="dialogSavedRoomUrl" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Room successfully saved</h3>
            </div>
            <div class="modal-body">
                <h4 >Url to your room</h4>
                <input type="text" id="dialogSavedRoomUrlInput" value="/room3d" class="form-control" onclick="window.$(this).select();" readonly>
                <div class="text-right">
                    <button id="bookmarkSavedRoomLink" type="button" class="btn btn-default">Bookmark link</button>
                    <a id="savedRoomGoToUrl" href="#" class="btn btn-default" role="button" style="display: none">Go to your room</a>
                    <a id="savedRoomLogin" href="/login" class="btn btn-primary" role="button" style="display: none">Login</a>
                </div>
                <h4>Share</h4>
                <div class="text-center">
                    @include('common.share')
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
