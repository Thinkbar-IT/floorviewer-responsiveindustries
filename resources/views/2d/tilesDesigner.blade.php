
<div id="tilesDesigner" class="modal fade">
    <div class="td-panel">
        <div id="tilesDesignerCloseBtn" class="td-x-close-btn">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="td-blank-tiles-block">
            @if (config('app.tiles_designer_show_logo'))
                <div class="td-logo">
                    <div class="td-logo-helper"></div>
                    <a href="#" title="Logo">
                        <img src="{{ App\Company::findOrFail(1)->logo }}" alt="" class="td-logo-img">
                    </a>
                </div>
            @endif
            <h4>Select Category:</h4>
            <select class="form-control" id="category"></select>
            <div id="td-blank-tiles" @if (config('app.tiles_designer_show_logo')) style="max-height: 420px;" @endif></div>
        </div>

        <div class="td-paint-block">
            <!-- <canvas id="editWrapCanvas" style="display: none;"></canvas> -->
            <div id="edit-wrap">
                <canvas id="td-custom-tile"> </canvas>
            </div>

            <div id="colors-container"></div>
        </div>

        <div class="td-buttons-block">
            <button id="tilesDesignerSaveBtn" disabled>Save</button>
            <button id="tilesDesignerDownloadBtn" disabled>Download</button>
            <button id="tilesDesignerVisualizeBtn" data-dismiss="modal" disabled>Visualize</button>
            <div class="td-tile-info td-tile-info-header">Pattern Name:</div>
            <div id="tilesDesignerPatternName" class="td-tile-info">-</div>
            <div class="td-tile-info td-tile-info-header">Color Combination:</div>
            <div id="tilesDesignerUsedColors" class="td-tile-info">-</div>
        </div>

        <div class="td-grid-block">
            <canvas id="tilesGrid"></canvas>
        </div>

        <div class="td-saved-tiles-block">
            <img src="/img/icons/arrowLeft.png" alt="Left arrow" class="td-saved-tiles-left-arrow">
            <div id="tilesDesignerSavedTiles"></div>
            <img src="/img/icons/arrowRight.png" alt="Right arrow" class="td-saved-tiles-right-arrow">
        </div>
    </div>
</div>

<script>
    window.JsConstants = window.JsConstants || {};
    window.JsConstants.config = window.JsConstants.config || {};
    window.JsConstants.config.tilesDesignerSingleApp = Boolean({!! env('TILES_DESIGNER_SINGLE_APP', false) !!})
    window.JsConstants.config.tilesDesignerShowOnload = Boolean({!! config('app.tiles_designer_show_onload') !!})

    var TilesDesignerColorsCatalog = [
        // pantoneCode - COLOR PANTONE CODE UNCOATED
        // hex - HEX COLOR REFERENCE
        // mosaicoNumber - MOSAICO COLOR NUMBER REFERENCE
        // name - MOSAICO COLOR NAME REFERENCE
        // group - COLOR GROUP (examapmle 1, 2, will be shown as Color 1, Color 2 in dropdown)
        { 'pantoneCode': '', 'hex': 'FEFEFE', 'mosaicoNumber': '01', 'name': 'CHALK', 'group': 1 },
        { 'pantoneCode': '', 'hex': '010101', 'mosaicoNumber': '01', 'name': 'BLACK' },
        { 'pantoneCode': '2006', 'hex': 'F1C068', 'mosaicoNumber': '02', 'name': 'SAFFRON' },
        { 'pantoneCode': '2404', 'hex': 'A0B49B', 'mosaicoNumber': '03', 'name': 'LIMUN' },
        { 'pantoneCode': '2411', 'hex': '4B624B', 'mosaicoNumber': '04', 'name': 'CARAFE' },
        { 'pantoneCode': '7461', 'hex': '4492C6', 'mosaicoNumber': '05', 'name': 'NIGELLA' },
        { 'pantoneCode': '7407', 'hex': 'CCA36E', 'mosaicoNumber': '06', 'name': 'CARAMEL' },
        { 'pantoneCode': '536', 'hex': 'A4B5CB', 'mosaicoNumber': '07', 'name': 'NAVY' },
        { 'pantoneCode': '2274', 'hex': 'D5E1B5', 'mosaicoNumber': '08', 'name': 'APPLE GREEN' },
        { 'pantoneCode': '5425', 'hex': '7993A5', 'mosaicoNumber': '09', 'name': 'NIAGARA' },
        { 'pantoneCode': '2473', 'hex': 'C7BABF', 'mosaicoNumber': '10', 'name': 'ORCHID' },
        { 'pantoneCode': '3547', 'hex': 'BF8E51', 'mosaicoNumber': '11', 'name': 'CINNAMON' },
        { 'pantoneCode': '2470', 'hex': '9E8A77', 'mosaicoNumber': '12', 'name': 'CLOVE', 'group': 1 },
        { 'pantoneCode': '2945', 'hex': '29588C', 'mosaicoNumber': '13', 'name': 'MIDNIGHT', 'group': 1 },
        { 'pantoneCode': '2205', 'hex': '5F9EA0', 'mosaicoNumber': '14', 'name': 'CELADON', 'group': 1 },
        { 'pantoneCode': '438', 'hex': '817275', 'mosaicoNumber': '15', 'name': 'AUBERGINE', 'group': 1 },
        { 'pantoneCode': '7618', 'hex': 'D08A77', 'mosaicoNumber': '16', 'name': 'GUAVA', 'group': 1 },
        { 'pantoneCode': '7594', 'hex': '8B645A', 'mosaicoNumber': '17', 'name': 'COCOA', 'group': 1 },
        { 'pantoneCode': '7619', 'hex': 'C87164', 'mosaicoNumber': '18', 'name': 'CERISE', 'group': 1 },
        { 'pantoneCode': '7415', 'hex': 'EBB5A5', 'mosaicoNumber': '19', 'name': 'CORAL', 'group': 1 },
        { 'pantoneCode': '2383', 'hex': '5074A2', 'mosaicoNumber': '20', 'name': 'INDIGO', 'group': 1 },
        { 'pantoneCode': '437', 'hex': '8D7F83', 'mosaicoNumber': '21', 'name': 'BERRY', 'group': 1 },
        { 'pantoneCode': '7528', 'hex': 'D5CCBF', 'mosaicoNumber': '22', 'name': 'NUTMEG', 'group': 1 },
        { 'pantoneCode': '649', 'hex': 'E1E7EE', 'mosaicoNumber': '23', 'name': 'SNOW', 'group': 1 },
        { 'pantoneCode': '7720', 'hex': '477675', 'mosaicoNumber': '24', 'name': 'SAGE', 'group': 1 },
        { 'pantoneCode': '5235', 'hex': 'D6C3CC', 'mosaicoNumber': '25', 'name': 'ROSEWATER', 'group': "Crackle" },
        { 'pantoneCode': '2153', 'hex': '4E6C88', 'mosaicoNumber': '26', 'name': 'NIGHTFALL', 'group': "Crackle" },
        { 'pantoneCode': '5U C GREY', 'hex': 'ADAEB0', 'mosaicoNumber': '27', 'name': 'SLATE', 'group': "Crackle" },
        { 'pantoneCode': '7U BLACK', 'hex': '6C6864', 'mosaicoNumber': '28', 'name': 'PITCH', 'group': "Crackle" },
        { 'pantoneCode': '7624', 'hex': '8D5A54', 'mosaicoNumber': '29', 'name': 'CHESTNUT', 'group': "Crackle" },
        { 'pantoneCode': '110', 'hex': 'CC9F26', 'mosaicoNumber': '30', 'name': 'MUSTARD', 'group': "Crackle" },
        { 'pantoneCode': '7620', 'hex': 'C0615B', 'mosaicoNumber': '31', 'name': 'CRIMSON', 'group': "Crackle" },
        { 'pantoneCode': '445', 'hex': '6B7173', 'mosaicoNumber': '32', 'name': 'CHARCOAL', 'group': "Crackle" },
        { 'pantoneCode': '4U WARM GREY', 'hex': 'B4ACA6', 'mosaicoNumber': '33', 'name': 'LAVANDER', 'group': "Crackle" },
        { 'pantoneCode': '1U COOL GREY', 'hex': 'DAD9D6', 'mosaicoNumber': '34', 'name': 'BONE', 'group': "Crackle" },
        { 'pantoneCode': '2U COOL GREY', 'hex': 'CACAC8', 'mosaicoNumber': '35', 'name': 'DOVE', 'group': "Crackle" },
        { 'pantoneCode': '2022', 'hex': 'FFA98F', 'mosaicoNumber': '36', 'name': 'MANDARIN', 'group': "Crackle" },
        { 'pantoneCode': '7499', 'hex': 'F4F3EE', 'mosaicoNumber': '37', 'name': 'MILK', 'group': "Crackle" },
        { 'pantoneCode': '406', 'hex': 'C7BEBA', 'mosaicoNumber': '38', 'name': 'GREIGE', 'group': "Crackle" },
    ];
</script>

<script type="text/javascript" src='/js/room/TilesDesigner/fabric.js'></script>
@if (config('app.js_as_module'))
<script type="module" src="/js/src/TilesDesigner/TilesDesigner.js"></script>
@else
<script src="/js/room/TilesDesigner/tilesDesigner.min.js"></script>
@endif
