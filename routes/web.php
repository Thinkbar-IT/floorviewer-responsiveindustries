<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

View::composer('*', function($view){
    View::share('view_name', $view->getName());
});


$engine_2d_enabled = config('app.engine_2d_enabled');
$engine_3d_enabled = config('app.engine_3d_enabled');
$engine_panorama_enabled = config('app.engine_panorama_enabled');

if ($engine_2d_enabled && !$engine_3d_enabled) {
    Route::get('/', function () { return redirect('/room2d'); });
} else {
    Route::get('/', function () { return redirect('/room3d'); });
}


Route::get('/home', 'HomeController@index');
Route::get('/admin', 'HomeController@index');

Route::get('login/{authProvider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{authProvider}/callback', 'Auth\LoginController@handleProviderCallback');


Route::get('/room/url/{url}', 'Controller@roomUlr');
Route::get('/get/room/url/{url}', 'AjaxController@getSavedRoomByUrl');


Route::get('/get/tiles', 'AjaxController@getTiles');
Route::get('/get/filters', 'AjaxController@getFilters');


Route::get('/lang/{locale}', 'Controller@changeLocale');


Route::post('/userRoom/save', 'AjaxController@saveUserRoom');
Route::group(['middleware' => 'role:guest'], function () {
    Route::post('/home/rooms/delete', 'Controller@userRoomsDelete');
});


if ($engine_2d_enabled) {
    Route::get('/room2d', 'Controller2d@roomDefault');
    Route::get('/room2d/{id}', 'Controller2d@room');
    Route::get('/get/room2d/{id}', 'Controller2d@getRoom');
}

if ($engine_panorama_enabled) {
    Route::get('/panorama', 'ControllerPanorama@roomDefault');
    Route::get('/panorama/{id}', 'ControllerPanorama@room');
    Route::get('/get/panorama/{id}', 'ControllerPanorama@getRoom');
}

if ($engine_3d_enabled) {
    Route::get('/room3d', 'Controller3d@roomDefault');
    Route::get('/room3d/{id}', 'Controller3d@room');
    Route::get('/get/room/{id}', 'Controller3d@getRoom');
}

$engine_room_planner_enabled = config('app.engine_room_planner_enabled');
if ($engine_room_planner_enabled) {
    Route::get('/room-planner', 'ControllerRoomPlanner@roomDefault');
}

$engine_blueprint3d_enabled = config('app.engine_blueprint3d_enabled');
if ($engine_blueprint3d_enabled) {
    Route::get('/blueprint3d', 'ControllerBlueprint3d@roomDefault');
}



Route::group(['middleware' => 'role:registered'], function () {
    Route::get('/profile', 'Controller@profile');
    Route::post('/profile/update/name', 'Controller@userUpdateName');
    Route::post('/profile/update/avatar', 'Controller@userUpdateAvatar');
    Route::post('/profile/update/password', 'Controller@userUpdatePassword');
});


Route::group(['middleware' => 'role:editor'], function () {
    Route::get('/get/tile/{id}', 'AjaxController@getTile');
    Route::get('/get/filter/{id}', 'AjaxController@getFilter');

    Route::get('/tiles', 'Controller@tiles');
    Route::post('/tiles', 'Controller@tilesFilter');
    Route::post('/tiles/upload', 'Controller@tilesUpload');
    Route::post('/tiles/upload/confirm', 'Controller@tilesUploadConfirm');
    Route::post('/tile/update', 'Controller@tileUpdate');
    Route::post('/tiles/delete', 'Controller@tilesDelete');
    Route::post('/tiles/enable', 'Controller@tilesEnable');
    Route::post('/tiles/disable', 'Controller@tilesDisable');
    // Route::post('/tiles/copy', 'Controller@tilesCopy');
    Route::post('/tiles/batch', 'Controller@tilesBatch');

    Route::get('/filters', 'Controller@filters');
    Route::post('/filter/add', 'Controller@filterAdd');
    Route::post('/filter/update', 'Controller@filterUpdate');
    Route::post('/filters/delete', 'Controller@filtersDelete');
    Route::post('/filters/enable', 'Controller@filtersEnable');
    Route::post('/filters/disable', 'Controller@filtersDisable');

    if (config('app.engine_2d_enabled')) {
        Route::get('/rooms2d', 'Controller2d@rooms');
        Route::post('/room2d/add', 'Controller2d@roomAdd');
        Route::post('/room2d/update', 'Controller2d@roomUpdate');
        Route::post('/rooms2d/delete', 'Controller2d@roomsDelete');
        Route::post('/rooms2d/enable', 'Controller2d@roomsEnable');
        Route::post('/rooms2d/disable', 'Controller2d@roomsDisable');

        Route::get('/room2d/{id}/surfaces', 'Controller2d@roomSurfaces');
        Route::post('/room2d/surfaces/update', 'Controller2d@roomSurfacesUpdate');
    }

    if (config('app.engine_panorama_enabled')) {
        Route::get('/panoramas', 'ControllerPanorama@rooms');
        Route::post('/panorama/add', 'ControllerPanorama@roomAdd');
        Route::post('/panorama/update', 'ControllerPanorama@roomUpdate');
        Route::post('/panoramas/delete', 'ControllerPanorama@roomsDelete');
        Route::post('/panoramas/enable', 'ControllerPanorama@roomsEnable');
        Route::post('/panoramas/disable', 'ControllerPanorama@roomsDisable');

//        Route::get('/panorama/{id}/surfaces', 'ControllerPanorama@roomSurfaces');
//        Route::post('/panorama/surfaces/update', 'ControllerPanorama@roomSurfacesUpdate');
    }

    if (config('app.engine_3d_enabled')) {
        Route::get('/rooms', 'Controller3d@rooms');
        Route::post('/room/add', 'Controller3d@roomAdd');
        Route::post('/room/update', 'Controller3d@roomUpdate');
        Route::post('/rooms/delete', 'Controller3d@roomsDelete');
        Route::post('/rooms/enable', 'Controller3d@roomsEnable');
        Route::post('/rooms/disable', 'Controller3d@roomsDisable');
    }

    if (config('app.use_product_category')) {
        Route::get('/get/category/{id}', 'ControllerCategory@getById');
        Route::get('/categories', 'ControllerCategory@categories');
        Route::post('/category/add', 'ControllerCategory@add');
        Route::post('/category/update', 'ControllerCategory@update');
        Route::post('/categories/delete', 'ControllerCategory@delete');
        Route::post('/categories/enable', 'ControllerCategory@enable');
        Route::post('/categories/disable', 'ControllerCategory@disable');
    }
});



Route::group(['middleware' => 'role:administrator'], function () {
    Route::get('/get/user/{id}', 'AjaxController@getUser');

    Route::get('/users', 'Controller@users');
    Route::post('/user/update', 'Controller@userUpdate');
    Route::post('/user/reset/password', 'Controller@userResetPassword');
    Route::delete('/user/delete/{id}', 'Controller@userDelete');
    Route::post('/users/delete', 'Controller@usersDelete');
    Route::post('/users/enable', 'Controller@usersEnable');
    Route::post('/users/disable', 'Controller@usersDisable');

    Route::get('/appsettings', 'AjaxController@appSettings');
    Route::post('/appsettings/update', 'AjaxController@appSettingsUpdateLogo');

    Route::get('/get/surfacetype/{id}', 'AjaxController@getSurfaceType');
    Route::get('/get/surfacetypes', 'AjaxController@getSurfaceTypes');
    Route::get('/surfacetypes', 'Controller@surfaceTypes');
    Route::post('/surfacetype/add', 'Controller@surfaceTypeAdd');
    Route::post('/surfacetype/update', 'Controller@surfaceTypeUpdate');
    Route::post('/surfacetypes/delete', 'Controller@surfaceTypesDelete');
    Route::post('/surfacetypes/enable', 'Controller@surfaceTypesEnable');
    Route::post('/surfacetypes/disable', 'Controller@surfaceTypesDisable');

    Route::get('/get/roomtype/{id}', 'AjaxController@getRoomType');
    Route::get('/roomtypes', 'Controller@roomTypes');
    Route::post('/roomtype/add', 'Controller@roomTypeAdd');
    Route::post('/roomtype/update', 'Controller@roomTypeUpdate');
    Route::post('/roomtypes/delete', 'Controller@roomTypesDelete');
    Route::post('/roomtypes/enable', 'Controller@roomTypesEnable');
    Route::post('/roomtypes/disable', 'Controller@roomTypesDisable');

    if (config('app.needs_storage_link')) {
        Route::get('/storage-link', 'ControllerSystem@storageLink');
    }
});



if (config('app.tiles_designer')) {
    Route::get('/tilesdesigner/blanktiles', 'ControllerCustomTile@getBlankTiles');
    Route::get('/customtiles', 'ControllerCustomTile@getUserTiles');
    Route::get('/customtile/remove/{id}', 'ControllerCustomTile@remove');
    Route::post('/customtile/save', 'ControllerCustomTile@save');
    Route::post('/customtile/save-suggestion', 'ControllerCustomTile@saveSuggestion');
    Route::get('/get/room-custom-tiles', 'ControllerCustomTile@getTilesById');
}


if (config('app.api_user_rooms')) {
    Route::get('/user/external-link', 'ControllerExternalUser@fromLink'); // TODO use different config option

    Route::get('/api/user/rooms', 'ControllerSavedRoom@getUserRooms');
}



// Route::get('/test', 'HomeController@index');
// Route::get('/test', function () {
//     return response($_SERVER['SERVER_NAME']);
// });
