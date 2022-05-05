<div id="sourceLoadProgressBarContainer">
    <div id="roomLoaderBackground">
        <img src="{{ null }}" alt="">
    </div>

    @if (config('app.progress_bar_gif') != null)
        <div class="resources-load-progress-gif">
            <img src="{{ config('app.progress_bar_gif') }}" alt="">
        </div>
    @else
        @if (config('app.progress_bar_style') == 'circular')
            <div class="resources-load-progress-circular-animation"></div>
            <div id="sourceLoadProgressBar" class="resources-load-progress-circular-text">10%</div>
        @else
            <div class="progress">
                <div id="sourceLoadProgressBar" class="progress-bar progress-bar-striped active" role="progressbar"
                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                    10%
                </div>
            </div>
        @endif
    @endif
</div>
