@php
    $fallback_locale = config('app.fallback_locale');
    $default_lang_path = base_path() . '/resources/lang/' . $fallback_locale . '.json';

    if (file_exists($default_lang_path)) {
        $default_lang = file_get_contents($default_lang_path);
    } else {
        $default_lang = '{}';
    }

    $locale = config('app.locale');
    $current_lang_path = base_path() . '/resources/lang/' . $locale . '.json';

    if ($locale != $fallback_locale && file_exists($current_lang_path)) {
        $current_lang = file_get_contents($current_lang_path);
    } else {
        $current_lang = '{}';
    }
@endphp

<script>
    window.JsConstants = window.JsConstants || {};
    window.JsConstants.lang = window.JsConstants.lang || {};
    window.JsConstants.lang.default = {!! $default_lang !!};
    window.JsConstants.lang.current = {!! $current_lang !!};
</script>
