<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => env('APP_NAME', 'TileVisualizer'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),
    'locales' => explode(',', env('APP_LOCALES', '')),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        Laravel\Tinker\TinkerServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        // Other service providers...
        Laravel\Socialite\SocialiteServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'Image' => Intervention\Image\Facades\Image::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | 2D / 3D engines enabling / disabling
    |--------------------------------------------------------------------------
    |
    |
    */

    'engine_2d_enabled' => env('ENGINE_2D_ENABLED', false),
    'engine_3d_enabled' => env('ENGINE_3D_ENABLED', false),
    'engine_panorama_enabled' => env('ENGINE_PANORAMA_ENABLED', false),
    'engine_room_planner_enabled' => env('ENGINE_ROOM_PLANNER_ENABLED', false),
    'engine_blueprint3d_enabled' => env('ENGINE_BLUEPRINT3D_ENABLED', false),

    'api_user_rooms' => env('API_USER_ROOMS', false),

    'tiles_designer' => env('TILES_DESIGNER', false), // Also in JS config
    'tiles_designer_show_onload' => env('TILES_DESIGNER_SHOW_ONLOAD', false),
    'tiles_designer_show_logo' => env('TILES_DESIGNER_SHOW_LOGO', false),

    'js_as_module' => env('JS_AS_MODULE', false),

    'tiles_builders_range' => env('TILES_BUILDERS_RANGE', false),

    'room_font_family' => env('ROOM_FONT_FAMILY', ''),

    'tiles_extra_options' => env('TILES_EXTRA_OPTIONS', ''),

    'tiles_access_level' => env('TILES_ACCESS_LEVEL', false),
    'grout_colors' => explode(',', env('GROUT_COLORS', '')),
    'tiles_skew_sizes' => explode(',', env('TILES_SKEW_SIZES', '')),
    'js_pdf_lib' => env('JS_PDF_LIB', 'jsPDF'), // Also in JS config

    'needs_storage_link' => env('NEEDS_STORAGE_LINK', false),

    'copyright_text' => env('COPYRIGHT_TEXT', ''),
    'copyright_link' => env('COPYRIGHT_LINK', '#'),
    'copyright_app_developer_text' => env('COPYRIGHT_APP_DEVELOPER_TEXT', ''),
    'copyright_app_developer_link' => env('COPYRIGHT_APP_DEVELOPER_LINK', '#'),

    'sub_css' => env('SUB_CSS', null),
    'product_panel' => env('PRODUCT_PANEL', ''),
    'bottom_menu' => env('BOTTOM_MENU', ''),

    'progress_bar_style' => env('PROGRESS_BAR_STYLE', 'striped'),
    'progress_bar_gif' => env('PROGRESS_BAR_GIF', null),

    'share_button_email' => env('SHARE_BUTTON_EMAIL', false),
    'share_button_facebook' => env('SHARE_BUTTON_FACEBOOK', false),
    'share_button_twitter' => env('SHARE_BUTTON_TWITTER', false),
    'share_button_vkontakte' => env('SHARE_BUTTON_VKONTAKTE', false),
    'share_button_whatsapp' => env('SHARE_BUTTON_WHATSAPP', false),

    'product_info_default_url' => env('PRODUCT_INFO_DEFAULT_URL', null),

    'tile_set_different_icon' => env('TILE_SET_DIFFERENT_ICON', false),

    'hide_engine_icon' => env('HIDE_ENGINE_ICON', false),

    'use_product_category' => env('USE_PRODUCT_CATEGORY', false),

    'url_filters' => env('URL_FILTERS', false),

    'hide_top_panel_sort' => env('HIDE_TOP_PANEL_SORT', false),

    'unlimited_image_size' => env('UNLIMITED_IMAGE_SIZE', false),

    'product_finish' => env('PRODUCT_FINISH', null),
];
