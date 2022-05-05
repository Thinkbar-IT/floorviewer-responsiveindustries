<meta charset="utf-8">

<title>{{ App\Company::findOrFail(1)->name }}</title>

<link rel="shortcut icon" href="{{ App\Company::findOrFail(1)->logo }}" />

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:title" content="{{ App\Company::findOrFail(1)->name }}" />
<meta property="og:type" content="website" />
<meta property="og:description" content="Tile Visualizer" />
<meta property="og:image" content="{{ URL::to('/') . $room_icon }}" />

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
