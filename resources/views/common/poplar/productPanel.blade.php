<div id="topPanel" class="top-panel" style="display: none;">
    <div id="topPanelHideBtn" class="top-panel-hide-button">
        <span id="topPanelHideIcon" class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
    </div>

    <div class="top-panel-label -title">
        <span>Select Product</span>
    </div>
    <div id="topPanelCloseBtn" class="-close">
        <span>×</span>
    </div>

    <ul class="nav nav-tabs"></ul>

    <div class="top-panel-box">
        <input id="inputSearch" type="search" value="" placeholder="@lang('Type To Search')" class="input-search"><button id="btnSearchIcon" class="search-icon-button">
            <img src="/img/icons/search.png" alt="Search" class="search-icon-button-imqge">
        </button>
        <button id="btnRefine" class="top-panel-button" style="display: none;">@lang('Refine')</button>
        <div id="topPanelShowLayoutTools">@lang('Customize Layout')</div>
        <div id="topPanelSearchResult" style="display: none"></div>
    </div>

    <div class="top-panel-box top-panel-box-first" style="display: none;">
        <button id="btnProduct" class="top-panel-button">@lang('Product')</button>
        <button id="btnLayout" class="top-panel-button">@lang('Layout')</button>
        {{-- <button id="btnGrout" class="top-panel-button">@lang('Grout')</button> --}}
    </div>

    <div id="topPanelLayout" class="top-panel-option-box top-panel-box-overflow-y" style="display: none;">
        @if ($view_name == '3d.room')
        <div class="top-panel-box">
            <span class="top-panel-label stiled-checkbox-text">@lang('Surface Color')</span>
            <div id="surface-color-picker" class="top-panel-select-color" data-color="#ffffff" title="Surface Color"></div>
        </div>
       @endif

        <div id="topPanelContentFreeDesign" class="top-panel-box">
            <div>
                <label for="topPanelCheckFreeDesign" class="top-panel-label stiled-checkbox-text">@lang('Free Design')</label>
                <div class="stiled-checkbox">
                    <input type="checkbox" id="topPanelCheckFreeDesign" />
                    <label for="topPanelCheckFreeDesign"></label>
                </div>
            </div>
            <div id="topPanelCheckFreeDesignRotateBox">
                <label for="topPanelCheckFreeDesignRotate" class="top-panel-label stiled-checkbox-text">@lang('Rotate By Click')</label>
                <div class="stiled-checkbox">
                    <input type="checkbox" id="topPanelCheckFreeDesignRotate" />
                    <label for="topPanelCheckFreeDesignRotate"></label>
                </div>
            </div>
        </div>

        <div class="top-panel-box radio-surface-pattern">
            <input id="topPanelSurfacePattern_0" type="radio" name="radioSurfacePattern" value="0">
            <label for="topPanelSurfacePattern_0">
                <img src="/img/square.png" alt="" class="pattern-image-icon">
                <p>@lang('Standard')</p>
            </label>
            <input id="topPanelSurfacePattern_1" type="radio" name="radioSurfacePattern" value="1">
            <label for="topPanelSurfacePattern_1">
                <img src="/img/chess.png" alt="" class="pattern-image-icon">
                <p>@lang('Chess')</p>
            </label>
            <input id="topPanelSurfacePattern_2" type="radio" name="radioSurfacePattern" value="2">
            <label for="topPanelSurfacePattern_2">
                <img src="/img/skew.png" alt="" class="pattern-image-icon">
                <p>@lang('Horizontal Skew')</p>
            </label>
            <input id="topPanelSurfacePattern_3" type="radio" name="radioSurfacePattern" value="3">
            <label for="topPanelSurfacePattern_3">
                <img src="/img/skewVert.png" alt="" class="pattern-image-icon">
                <p>@lang('Vertical Skew')</p>
            </label>

            <?php
            $skew_sizes = config('app.tiles_skew_sizes');
            $skew_count = count($skew_sizes);
            if ($skew_count > 0 && $skew_sizes[0]) {
                echo '<div class="radio-skew-size">';
                for ($i = $skew_count - 1; $i >= 0; $i--) {
                    $size = explode('=', $skew_sizes[$i]);
                    echo "<input id=\"topPanelSurfacePatternSkewSize_$i\" type=\"radio\" name=\"radioSkewSize\" value=\"{$size[1]}\">",
                        "<label for=\"topPanelSurfacePatternSkewSize_$i\">{$size[0]}</label>";
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div id="topPanelGrout" class="top-panel-option-box" style="display: none;">
        <div id="topPanelContentSurfaceTabGroutSizeBody" class="top-panel-box">
            <span class="top-panel-label stiled-checkbox-text">@lang('Grout Size')</span>
            <input id="topPanelGroutSizeRange" type="range" min="0" max="24" value="4">
            <span id="topPanelGroutSizeText" class="top-panel-label stiled-checkbox-text">4 mm</span>
        </div>
        <div class="top-panel-box">
            <span class="top-panel-label stiled-checkbox-text">@lang('Grout Color')</span>
            <div id="grout-color-picker" class="top-panel-select-color" data-color="#ffffff" title="Grout Color"></div>

            <?php
            $grout_colors = config('app.grout_colors');
            if (count($grout_colors) > 0 && $grout_colors[0]) {
                echo '<div id="grout-predefined-color">';
                    foreach ($grout_colors as $color) {
                        echo "<button data-color=\"$color\" style=\"background-color: $color;\" class=\"-btn\"></button>";
                    }
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div id="topPanelRotation" class="top-panel-box radio-surface-rotation" style="display: none;">
        <span class="top-panel-label">@lang('Laying angle'):</span>
        <input id="topPanelSurfaceRotation_0" type="radio" name="radioSurfaceRotation" value="0" checked="checked">
            <label for="topPanelSurfaceRotation_0">0°</label>
        <input id="topPanelSurfaceRotation_45" type="radio" name="radioSurfaceRotation" value="45">
            <label id="topPanelSurfaceRotationLabel_45" for="topPanelSurfaceRotation_45">45°</label>
        <input id="topPanelSurfaceRotation_90" type="radio" name="radioSurfaceRotation" value="90">
            <label for="topPanelSurfaceRotation_90">90°</label>
        <input id="topPanelSurfaceRotation_135" type="radio" name="radioSurfaceRotation" value="135">
            <label id="topPanelSurfaceRotationLabel_135" for="topPanelSurfaceRotation_135">135°</label>
        <input id="topPanelSurfaceRotation_180" type="radio" name="radioSurfaceRotation" value="180">
            <label for="topPanelSurfaceRotation_180">180°</label>
    </div>

    <div id="topPanelFilter" class="top-panel-box top-panel-option-box top-panel-box-overflow-y"></div>

    <div class="top-panel-box dropdown-tiles-sort" style="display: none;">
        <span class="top-panel-label">@lang('Sort tiles'):</span>
        <select id="topPanelTilesSort" name="topPanelTilesSort">
            <option value="a-z">A-Z</option>
            <option value="z-a">Z-A</option>
            <option value="newest first">@lang('Newest first')</option>
            <option value="oldest first">@lang('Oldest first')</option>
        </select>
    </div>

    <div id="topPanelTilesListBox" class="top-panel-box tiles-list">
        <div id="loadTilesAnimationContainer">
            <p>Loading Tiles</p>
            <div class="circles marginLeft">
                <span class="circle_1 circle"></span>
                <span class="circle_2 circle"></span>
                <span class="circle_3 circle"></span>
            </div>
        </div>

        <ul id="topPanelTilesListUl"></ul>
    </div>
</div>

<script>
(function closure() { // TOOD move to JS files
    var topPanelLayout = document.getElementById('topPanelLayout');
    var topPanelRotation = document.getElementById('topPanelRotation');
    // var topPanelGrout = document.getElementById('topPanelGrout');

    var topPanelFilter = document.getElementById('topPanelFilter');
    var productList = document.getElementById('topPanelTilesListBox');

    var toolsVisible = false;

    function showTools() {
        toolsVisible = true;
        topPanelLayout.style.display = '';
        // topPanelGrout.style.display = '';
        topPanelRotation.style.display = '';

        topPanelFilter.style.display = 'none';
        productList.style.display = 'none';
    }

    function hideTools() {
        toolsVisible = false;
        topPanelLayout.style.display = 'none';
        // topPanelGrout.style.display = 'none';
        topPanelRotation.style.display = 'none';

        topPanelFilter.style.display = '';
        productList.style.display = '';
    }

    var toolsButton = document.getElementById('topPanelShowLayoutTools');
    toolsButton.addEventListener('click', function () {
        if (toolsVisible) {
            hideTools();
        } else {
            showTools();
        }
        
    });
    var tabs = document.querySelector('#topPanel ul.nav-tabs')
    tabs.addEventListener('click', hideTools);
})();

</script>
