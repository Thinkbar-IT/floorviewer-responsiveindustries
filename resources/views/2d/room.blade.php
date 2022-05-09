@extends(config('view.visualizer_layout') .'layouts.room')

@section('content')

<div id="container" class="room-canvas-container">
    <canvas id="roomCanvas" class="room-canvas"></canvas>
</div>

@if (config('view.visualizer_layout') != 'iorena.')
    @include('common.' . config('app.bottom_menu') . 'bottomMenu2d')
@endif

<script src="/vendor/jquery.min.js"></script>
<script src="/js/room/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xhook/1.4.9/xhook.min.js" integrity="sha512-0o1RIUfURa5IHcPtNt8ixdfy6EP5WOoVhiD2ilvjRMyfcxN3JeQEVjfbuwBMhaYREuaHujx/aFlwkPuOuP+3AA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@if (config('app.js_as_module'))
<script type="module" src="/js/src/2d/interior2d.js"></script>
@else
<script src="/js/room/2d.min.js?v={{ rand() }}"></script>
@endif

<script>
function toggleSubCatFilters() {
    console.log('Cl 1')
    console.log($('.filter-block .-header'))
    Array.from($('.filter-block .-header')).map(r => {
        if (( $(r).text().includes('SPF Flooring') || $(r).text().includes('LVT Flooring') )) {
            console.log(r)
            $(r).parent().parent().hide()
        }
    })
    
}
function changeToggleFilters() {
    console.log('Cl 2')
    $('.filter-item-checkbox input').click(({ target }) => {
        if (target.checked) {
            const label = $(target).closest('.filter-item-checkbox').text()
            console.log({ label })
            if (label) {
                Array.from($('.filter-block .-header')).map(r => {
                    if (( $(r).text().includes(label) )) {
                        $(r).parent().parent().show()
                    }
                })
            }
        } else {
            const label = $(target).closest('.filter-item-checkbox').text()
            Array.from($('.filter-block .-header')).map(r => {
                if (( $(r).text().includes(label) )) {
                    $(r).parent().parent().hide()
                }
            })
        }
        console.log({ target })
    })
}
$(document).ready(() => {

    xhook.after(function(request, response) {
    if(request.url.match(/get\/filters$/))
        setTimeout(() => {
            toggleSubCatFilters()
            changeToggleFilters()
        }, 1000)
        
    });
})
</script>

@endsection
