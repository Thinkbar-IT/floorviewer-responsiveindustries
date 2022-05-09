<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App;
use App\Tile;
use App\Filter;
use App\SurfaceType;
use App\RoomType;
use App\User;
use App\Savedroom;
use App\Company;
use App\Category;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function changeLocale($locale) {
        if (in_array($locale, config('app.locales'))) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }


    public function roomUlr($url) {
        $saved_room = Savedroom::where('url', $url)->first();
        if (!$saved_room || !$saved_room->room) { abort(404); }

        if (isset($saved_room->engine)) {
            if ($saved_room->engine == '2d' && config('app.engine_2d_enabled')) {
                $controller = new Controller2d;
                return $controller->room(null, $url, $saved_room->image);
            }

            if ($saved_room->engine == '3d' && config('app.engine_3d_enabled')) {
                $controller = new Controller3d;
                return $controller->room(null, $url, $saved_room->image);
            }

            if ($saved_room->engine == 'panorama' && config('app.engine_panorama_enabled')) {
                $controller = new ControllerPanorama;
                return $controller->room(null, $url, $saved_room->image);
            }
        }

        abort(404);
    }


    ///////////////////////////////////////////////////////////


    public function userRoomsDelete(Request $request) {
        $user = Auth::user();

        $saved_rooms = Savedroom::find(json_decode($request->selectedSavedRooms));
        foreach ($saved_rooms as $saved_room) {
            if ($user->id == $saved_room->userid) {
                // Storage::disk('public')->delete($saved_room->getOriginal('image'));
                $saved_room->del();
            }
        }

        return redirect('/home');
    }


    ///////////////////////////////////////////////////////////


    public function tiles(Request $request) {
        $filterRequest = $this->parseFilter($request);
        $filterQuery = $this->getFilterQuery($filterRequest);

        $tiles = Tile::where($filterQuery)->orderBy('created_at', 'desc')->paginate(30);
        $surfaceTypes = SurfaceType::optionsAsArray();

        $tileIds = Tile::getIds($filterQuery);
        $categories = Category::getTreeByType(1);
        // dd($categories);
        // return $categories;
        $viewName = 'tiles';
        if ($request->old) {
            $viewName = 'tiles-v2';
        }
        return view($viewName, [
            'tiles' => $tiles,
            'filter' => $filterRequest,
            'surfaceTypes' => $surfaceTypes,
            'tileIds' => $tileIds,
            'product_categories_tree' => Category::getTreeByType(1),
        ]);
    }

    public function tilesFilter(Request $request) {
        $validator = Validator::make($request->all(), [
            'filterTileName' => 'nullable|string',
            'filterTileShape' => 'nullable|string',
            'filterTileWidth' => 'nullable|numeric',
            'filterTileHeight' => 'nullable|numeric',
            'filterTileSurface' => 'nullable|string',
            'filterTileFinish' => 'nullable|string',
            'filterTileUrl' => 'nullable|string',
            'filterTilePrice' => 'nullable|string',
            'filterTileRotoPrintSetName' => 'nullable|string',
            'filterTileExpProps' => 'nullable|string',
            'filterTileEnabled' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect('/tiles')->withInput()->withErrors($validator);
        }

        return $this->tiles($request);
    }

    private function parseFilter($request) {
        if ($request) {
            $filterRequest = [
                'filterTileName' => $request->filterTileName,
                'filterTileShape' => $request->filterTileShape,
                'filterTileWidth' => $request->filterTileWidth,
                'filterTileHeight' => $request->filterTileHeight,
                'filterTileSurface' => $request->filterTileSurface,
                'filterTileFinish' => $request->filterTileFinish,
                'filterTileUrl' => $request->filterTileUrl,
                'filterTilePrice' => $request->filterTilePrice,
                'filterTileRotoPrintSetName' => $request->filterTileRotoPrintSetName,
                'filterTileExpProps' => $request->filterTileExpProps,
                'filterTileEnabled' => $request->filterTileEnabled,
            ];
            session($filterRequest);
        } else {
            $filterRequest =  [
                'filterTileName' => session('filterTileName'),
                'filterTileShape' => session('filterTileShape'),
                'filterTileWidth' => session('filterTileWidth'),
                'filterTileHeight' => session('filterTileHeight'),
                'filterTileSurface' => session('filterTileSurface'),
                'filterTileFinish' => session('filterTileFinish'),
                'filterTileUrl' => session('filterTileUrl'),
                'filterTilePrice' => session('filterTilePrice'),
                'filterTileRotoPrintSetName' => session('filterTileRotoPrintSetName'),
                'filterTileExpProps' => session('filterTileExpProps'),
                'filterTileEnabled' => session('filterTileEnabled'),
            ];
        }
        return (object) $filterRequest;
    }

    private function getFilterQuery($request) {
        $filter = array();
        if ($request->filterTileName) $filter[] = ['name', 'like', '%' . $request->filterTileName . '%'];
        if ($request->filterTileShape) $filter[] = ['shape', '=', $request->filterTileShape];
        if ($request->filterTileWidth) $filter[] = ['width', '=', $request->filterTileWidth];
        if ($request->filterTileHeight) $filter[] = ['height', '=', $request->filterTileHeight];
        if ($request->filterTileSurface) $filter[] = ['surface', '=', $request->filterTileSurface];
        if ($request->filterTileFinish) $filter[] = ['finish', '=', $request->filterTileFinish];
        if ($request->filterTileUrl) $filter[] = ['url', 'like', '%' . $request->filterTileUrl . '%'];
        if ($request->filterTilePrice) $filter[] = ['price', '=', $request->filterTilePrice];
        if ($request->filterTileRotoPrintSetName) $filter[] = ['rotoPrintSetName', 'like', '%' . $request->filterTileRotoPrintSetName . '%'];
        if ($request->filterTileExpProps) $filter[] = ['expProps', 'like', '%' . $request->filterTileExpProps . '%'];
        if ($request->filterTileEnabled) $filter[] = ['enabled', '=', $request->filterTileEnabled];

        return $filter;
    }

    public function tilesUpload(Request $request) {
        $image_size_limit = config('app.unlimited_image_size') ? '' : '|max:2048|dimensions:max_width=2048,max_height=2048';

        $validator = Validator::make($request->all(), [
            'shape' => 'nullable|in:square,rectangle,hexagon,diamond,quadSet,preparedSet,notionHerringbon,riverstoneRohmboid,rivertsoneChevron,stoneSystemCombo',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'surface' => 'required|exists:surface_types,name', //in:wall,floor
            'finish' => 'nullable|in:glossy,matt,semi_polished,textured',
            'files.*' => 'required|image' . $image_size_limit,
            'icon' => 'nullable|image|max:512|dimensions:max_width=512,max_height=512',
            'rotoPrintSetName' => 'nullable|max:100|string',
            'expProps' => 'nullable|json',
            'accessLevel' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/tiles')->withInput()->withErrors($validator);
        }

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            $icons = [];
            $tile_files = [];

            $set_different_icon = config('app.tile_set_different_icon');
            if ($set_different_icon) {
                foreach($files as $file) {
                    $original_name = $file->getClientOriginalName();
                    $path_parts = pathinfo($original_name);
                    $file_name = mb_strtolower($path_parts['filename']);

                    $file_name_parts = pathinfo($file_name);
                    if (isset($file_name_parts['extension']) && $file_name_parts['extension'] == 'icon') {
                        $base_file_name = $file_name_parts['filename'];
                        $icons[$base_file_name] = $file;
                    } else {
                        $tile_files[] = $file;
                    }
                }

                if (count($tile_files) == 0) {
                    $error_messages = ['No one tile image uploaded. Only icons.'];
                    return redirect('/tiles')->withInput()->withErrors($error_messages);
                }
            } else {
                $tile_files = $files;
            }

            $uploadedTileIds = [];
            foreach($tile_files as $file) {
                $original_name = $file->getClientOriginalName();
                $file_name = pathinfo($original_name, PATHINFO_FILENAME);

                $tile = new Tile;

                $tile_url = $tile->saveFile($file);
                if ($set_different_icon) {
                    if (count($tile_files) == 1 && $request->hasFile('icon')) {
                        $tile->saveIcon($request->file('icon'), $tile_url);
                    } else if (isset($icons[mb_strtolower($file_name)])) {
                        $tile->saveIcon($icons[mb_strtolower($file_name)], $tile_url);
                    }
                }

                $tile->setData($request, $file_name, $tile_url);
                $tile->save();

                $uploadedTileIds[] = $tile->id;
            }

            $tiles = Tile::whereIn('id', $uploadedTileIds)->get();
            return view('tilesUploadConfirm', ['tiles' => $tiles]);
        }

        return redirect('/tiles');
    }

    public function tilesUploadConfirm(Request $request) {
        if (isset($request->id) && count($request->id) > 0) {
            $ids = array();
            $validators = array();
            foreach ($request->name as $id => $name) {
                $validator = Validator::make($request->all(), [
                    'id.' . $id => 'required|integer|exists:tiles,id',
                    'remove.' . $id => 'nullable|in:remove',
                ]);

                if ($validator->fails()) {
                    $validators[] = $validator;
                    $ids[] = $id;
                } else {
                    $tile = Tile::findOrFail($id);
                    if (isset($request->remove[$id]) && $request->remove[$id] == 'remove') {
                        $tile->deleteFile(); // Storage::disk('public')->delete($tile->getOriginal('file'));
                        Tile::destroy($id);
                    } else {
                        $subValidator = Validator::make($request->all(), [
                            'name.' . $id => 'required|max:255|string',
                            'width.' . $id => 'required|numeric',
                            'height.' . $id => 'required|numeric',
                            'grout.' . $id => 'nullable|boolean',
                            'url.' . $id => 'nullable|max:1000|string',
                            'price.' . $id => 'nullable|numeric',
                            'enabled.' . $id => 'nullable|boolean',
                        ]);

                        if ($subValidator->fails()) {
                            $validators = $subValidator;
                            $ids[] = $id;
                        } else {
                            $tile->name = $request->name[$id];
                            $tile->width = $request->width[$id];
                            $tile->height = $request->height[$id];
                            if (isset($request->grout[$id])) { $tile->grout = 1; } else { $tile->grout = 0; }
                            $tile->url = $request->url[$id];
                            $tile->price = $request->price[$id];
                            if (isset($request->enabled[$id])) { $tile->enabled = 1; } else { $tile->enabled = 0; }
                            $tile->save();
                        }
                    }
                }
            }

            if (count($validators) > 0) {
                $tiles = Tile::whereIn('id', $ids)->get();
                return view('tilesUploadConfirm', ['tiles' => $tiles])->withErrors($validators);
            }
        }

        return redirect('/tiles');
    }

    public function tileUpdate(Request $request) {
        $image_size_limit = config('app.unlimited_image_size') ? '' : '|max:2048|dimensions:max_width=2048,max_height=2048';

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tiles,id',
            'name' => 'required|max:255|string',
            'shape' => 'nullable|in:square,rectangle,hexagon,diamond,quadSet,preparedSet,notionHerringbon,riverstoneRohmboid,rivertsoneChevron,stoneSystemCombo',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'surface' => 'required|exists:surface_types,name', //in:wall,floor
            'finish' => 'nullable|in:glossy,matt,semi_polished,textured',
            'files.*' => 'nullable|image' . $image_size_limit,
            'icon' => 'nullable|image|max:512|dimensions:max_width=512,max_height=512',
            'grout' => 'nullable|boolean',
            'url' => 'nullable|max:1000|string',
            'price' => 'nullable|numeric',
            'rotoPrintSetName' => 'nullable|max:100|string',
            'expProps' => 'nullable|json',
            'accessLevel' => 'nullable|numeric',
            'dataAction' => 'nullable|in:update,copy',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/tiles')->withInput()->withErrors($validator);
        }

        $tile = Tile::findOrFail($request->id);

        if ($request->dataAction == 'copy') {
            $tile = $tile->replicate();
            $tile->save();

            if (!$request->hasFile('files')) $tile->file = $tile->copyImage();
            $tile->enabled = 0;
        } else {
            if (isset($request->enabled)) { $tile->enabled = 1; } else { $tile->enabled = 0; }
        }

        if ($request->hasFile('files')) {
            $tile->file = $tile->updateFile($request->file('files')[0]);
        }
        if ($request->hasFile('icon')) { // todo Add remove icon option
            // todo fix icon double creation
            // todo fix file and icon extension mismatch
            $icon = $tile->updateIcon($request->file('icon'));
        }

        $tile->name = $request->name;
        $tile->shape = $request->shape;
        $tile->width = $request->width;
        $tile->height = $request->height;
        $tile->surface = $request->surface;
        if (isset($request->finish)) $tile->finish = $request->finish;

        if (isset($request->grout)) { $tile->grout = 1; } else { $tile->grout = 0; }
        $tile->url = $request->url;
        $tile->price = $request->price;
        $tile->rotoPrintSetName = $request->rotoPrintSetName;
        $tile->expProps = $request->expProps;
        if (config('app.tiles_access_level')) $tile->access_level = $request->accessLevel;
        $tile->save();

        return redirect('/tiles');
    }

    public function tilesDelete(Request $request) {
        $tiles = Tile::find(json_decode($request->selectedTiles));
        foreach ($tiles as $tile) {
            $tile->del();
        }

        return redirect('/tiles');
    }

    public function tilesEnable(Request $request) {
        Tile::whereIn('id', json_decode($request->selectedTiles))->update(['enabled' => 1]);
        return redirect('/tiles');
    }

    public function tilesDisable(Request $request) {
        Tile::whereIn('id', json_decode($request->selectedTiles))->update(['enabled' => 0]);
        return redirect('/tiles');
    }

    // public function tilesCopy(Request $request) {
    //     $tiles = Tile::find(json_decode($request->selectedTiles));
    //     foreach ($tiles as $tile) {
    //         $tile_copy = $tile->replicate();
    //         $tile_copy->enabled = 0;
    //         $tile_copy->file = $tile_copy->copyImage();
    //         $tile_copy->save();
    //     }
    //     return redirect('/tiles');
    // }

    public function tilesBatch(Request $request) {
        if ($request->selectedTiles) {
            $tiles_id = json_decode($request->selectedTiles);

            // $validator = Validator::make($request->all(), [
            //     'surface' => 'nullable|exists:surface_types,name',
            //     'finish' => 'nullable|in:glossy,matt,semi_polished,textured',
            //     'accessLevel' => 'nullable|numeric',
            // ]);

            // if ($validator->fails()) {
            //     return redirect('/tiles')->withInput()->withErrors($validator);
            // }

            if (isset($request->selectAllFiltered) && $request->selectAllFiltered == 'true') {
                $tiles_id = json_decode($request->allTileIds);
            }

            if (isset($request->radioBatchProcess) && $request->radioBatchProcess == 'copy') {
                $new_tiles_id = [];
                $tiles = Tile::find($tiles_id);
                foreach ($tiles as $tile) {
                    $tile_copy = $tile->replicate();
                    $tile_copy->file = $tile_copy->copyImage();
                    $tile_copy->save();

                    array_push($new_tiles_id, $tile_copy->id);
                }
                $tiles_id = $new_tiles_id;
            }

            if (isset($request->surface) && $request->surface != 'original') {
                Tile::whereIn('id', $tiles_id)->update(['surface' => $request->surface]);
            }

            if (isset($request->finish) && $request->finish != 'original') {
                Tile::whereIn('id', $tiles_id)->update(['finish' => $request->finish]);
            }

            if (config('app.tiles_access_level')
                && isset($request->accessLevel) && $request->accessLevel != 'original') {
                Tile::whereIn('id', $tiles_id)->update(['access_level' => $request->accessLevel]);
            }
        }

        return redirect('/tiles');
    }


    ///////////////////////////////////////////////////////////


    public function filters() {
        $filters = Filter::paginate(20);
        $surfaceTypes = SurfaceType::optionsAsArray();
        return view('filters', ['filters' => $filters, 'surfaceTypes' => $surfaceTypes]);
    }

    public function filterAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:100|string',
            'field' => 'required|max:32|string',
            'surface' => 'required|exists:surface_types,name', //in:wall,floor
            'type' => 'required|in:checkbox,slider,accordion',
            'values' => 'nullable|max:4096|string',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/filters')->withInput()->withErrors($validator);
        }

        $filter = new Filter;
        $filter->name = $request->name;
        $filter->field = mb_strtolower($request->field);
        $filter->surface = $request->surface;
        $filter->type = $request->type;
        $filter->values = $request->values;
        $filter->enabled = 1;
        $filter->save();

        return redirect('/filters');
    }

    public function filterUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:filters,id',
            'name' => 'nullable|max:100|string',
            'field' => 'required|max:32|string',
            'surface' => 'required|exists:surface_types,name', //in:wall,floor
            'type' => 'required|in:checkbox,slider,accordion',
            'values' => 'nullable|max:4096|string',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/filters')->withInput()->withErrors($validator);
        }

        $filter = Filter::findOrFail($request->id);
        $filter->name = $request->name;
        $filter->field = mb_strtolower($request->field);
        $filter->surface = $request->surface;
        $filter->type = $request->type;
        $filter->values = $request->values;
        if (isset($request->enabled)) { $filter->enabled = 1; } else { $filter->enabled = 0; }
        $filter->save();

        return redirect('/filters');
    }

    public function filtersDelete(Request $request) {
        Filter::destroy(json_decode($request->selectedFilters));
        return redirect('/filters');
    }

    public function filtersEnable(Request $request) {
        Filter::whereIn('id', json_decode($request->selectedFilters))->update(['enabled' => 1]);
        return redirect('/filters');
    }

    public function filtersDisable(Request $request) {
        Filter::whereIn('id', json_decode($request->selectedFilters))->update(['enabled' => 0]);
        return redirect('/filters');
    }


    ///////////////////////////////////////////////////////////


    public function surfaceTypes() {
        $types = SurfaceType::paginate(20);
        return view('surfacetypes', ['types' => $types]);
    }

    public function surfaceTypeAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|string|unique:surface_types',
            'display_name' => 'nullable|max:100|string|unique:surface_types',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/surfacetypes')->withInput()->withErrors($validator);
        }

        $type = new SurfaceType;
        $type->name = $request->name;
        $type->display_name = $request->display_name;
        $type->enabled = 1;
        $type->save();

        return redirect('/surfacetypes');
    }

    public function surfaceTypeUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:surface_types',
            'name' => 'required|max:100|string|unique:surface_types,name,'.$request->id,
            'display_name' => 'nullable|max:100|string|unique:surface_types,display_name,'.$request->id,
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/surfacetypes')->withInput()->withErrors($validator);
        }

        $type = SurfaceType::findOrFail($request->id);
        $type->name = $request->name;
        $type->display_name = $request->display_name;
        $type->enabled = $request->enabled;
        $type->save();

        return redirect('/surfacetypes');
    }

    public function surfaceTypesDelete(Request $request) {
        SurfaceType::destroy(json_decode($request->selectedTypes));
        return redirect('/surfacetypes');
    }

    public function surfaceTypesEnable(Request $request) {
        SurfaceType::whereIn('id', json_decode($request->selectedTypes))->update(['enabled' => 1]);
        return redirect('/surfacetypes');
    }

    public function surfaceTypesDisable(Request $request) {
        SurfaceType::whereIn('id', json_decode($request->selectedTypes))->update(['enabled' => 0]);
        return redirect('/surfacetypes');
    }


    ///////////////////////////////////////////////////////////


    public function roomTypes() {
        $types = RoomType::paginate(20);
        return view('roomtypes', ['types' => $types]);
    }

    public function roomTypeAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|string|unique:room_types',
            'display_name' => 'nullable|max:100|string|unique:room_types',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/roomtypes')->withInput()->withErrors($validator);
        }

        $type = new RoomType;
        $type->name = $request->name;
        $type->display_name = $request->display_name;
        $type->enabled = 1;
        $type->save();

        return redirect('/roomtypes');
    }

    public function roomTypeUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:room_types',
            'name' => 'required|max:100|string|unique:room_types,name,'.$request->id,
            'display_name' => 'nullable|max:100|string|unique:room_types,display_name,'.$request->id,
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/roomtypes')->withInput()->withErrors($validator);
        }

        $type = RoomType::findOrFail($request->id);
        $type->name = $request->name;
        $type->display_name = $request->display_name;
        $type->enabled = $request->enabled;
        $type->save();

        return redirect('/roomtypes');
    }

    public function roomTypesDelete(Request $request) {
        RoomType::destroy(json_decode($request->selectedTypes));
        return redirect('/roomtypes');
    }

    public function roomTypesEnable(Request $request) {
        RoomType::whereIn('id', json_decode($request->selectedTypes))->update(['enabled' => 1]);
        return redirect('/roomtypes');
    }

    public function roomTypesDisable(Request $request) {
        RoomType::whereIn('id', json_decode($request->selectedTypes))->update(['enabled' => 0]);
        return redirect('/roomtypes');
    }


    ///////////////////////////////////////////////////////////


    public function profile() {
        return view('profile', array('user' => Auth::user()));
    }

    public function userUpdateName(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|string',
        ]);

        if ($validator->fails()) {
            return redirect('/profile')->withInput()->withErrors($validator);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return view('profile', array('user' => Auth::user()) );
    }

    public function userUpdateAvatar(Request $request) {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|max:1024|dimensions:max_width=1024,max_height=1024',
        ]);

        if ($validator->fails()) {
            return redirect('/profile')->withInput()->withErrors($validator);
        }

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $user->deleteAvatarFile();

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
            // $filename = time() . '.' . $user->avatar->getClientOriginalExtension();
            // Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );
            // $user->avatar = $filename;

            $user->save();
        }

        return view('profile', array('user' => $user) );
    }

    public function userUpdatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'nullable|max:255|string',
            'newPassword' => 'required|min:8|max:255|string|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/profile')->withInput()->withErrors($validator);
        }

        $user = Auth::user();

        if ($request->oldPassword) {
            $passwordErrorMessage = array();
            if (!Hash::check($request->oldPassword, $user->password)) {
                array_push($passwordErrorMessage, 'Wrong old password!');
            }
            if (Hash::check($request->newPassword, $user->password)) {
                array_push($passwordErrorMessage, 'Old and new passwords are same!');
            }
            if (count($passwordErrorMessage) > 0) {
                return redirect('/profile')->withInput()->withErrors($passwordErrorMessage);
            }

            $user->password = Hash::make($request->newPassword);
            $user->save();
        } else {
            if (!$user->password) {
                $user->password = Hash::make($request->newPassword);
                $user->save();
            }
        }

        return view('profile', array('user' => $user));
    }


    ///////////////////////////////////////////////////////////


    public function users() {
        $users = User::paginate(20);
        return view('users', ['users' => $users]);
    }

    public function userUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|max:255|string',
            'email' => 'required|email|max:100|unique:users,email,'.$request->id,
            'avatar' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
            'role' => 'required|in:guest,registered,editor,administrator',
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/users')->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            $user->deleteAvatarFile();
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
            // $user->save();
        }

        $user->role = $request->role;
        if (isset($request->enabled)) { $user->enabled = 1; } else { $user->enabled = 0; }
        $user->save();

        return redirect('/users');
    }

    public function userResetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
            'password' => 'required|min:8|max:255|string|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/users')->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/users');
    }

    public function userDelete($id) {
        $user = User::findOrFail($id);
        $user->deleteAvatarFile();
        $user->delete();
        return redirect('/users');
    }

    public function usersDelete(Request $request) {
        $selectedUsers = json_decode($request->selectedUsers);
        foreach ($selectedUsers as $userId) {
            Controller::userDelete($userId);
        }
        return redirect('/users');
    }

    public function usersEnable(Request $request) {
        User::whereIn('id', json_decode($request->selectedUsers))->update(['enabled' => 1]);
        return redirect('/users');
    }

    public function usersDisable(Request $request) {
        User::whereIn('id', json_decode($request->selectedUsers))->update(['enabled' => 0]);
        return redirect('/users');
    }


    ///////////////////////////////////////////////////////////


    public function appSettings() {
        $company = Company::findOrFail(1);
        return view('appsettings', ['company' => $company]);
    }

    public function appSettingsUpdateLogo(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:999|string',
            'email' => 'nullable|email|max:100',
            'logo' => 'nullable|image|max:1024|dimensions:max_width=1024,max_height=1024',
        ]);

        if ($validator->fails()) {
            return redirect('/appsettings')->withInput()->withErrors($validator);
        }

        $company = Company::findOrFail(1);
        $needsUpdate = false;

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($company->getOriginal('logo'));
            $company->logo = $request->file('logo')->store('company', 'public');
            $needsUpdate = true;
        }

        if (isset($request->name)) {
            $company->name = $request->name;
            $needsUpdate = true;
        }

        if (isset($request->email)) {
            $company->email = $request->email;
            $needsUpdate = true;
        }

        if ($needsUpdate) {
            $company->save();
        }

        return redirect('/appsettings');
    }


    ///////////////////////////////////////////////////////////


    // public function texttttttt()
    // {
    //     return ['option1' => '$option1'];
    // }

    // public function test(Request $request) {
    // // public function test($option1 = 0, $option2 = null) { //
    // //     // return $request->session()->all();
    // //     // return $request;
    // //     // Storage::disk('public')->delete('tiles//j8DM9JExUdMbnEiGt9gI7sF1ms40yoCPFrp1tyqq.png');

    // //     // if (Auth::user()->hasRole('administrator')) return 'administrator';
    // //     // if (!Auth::user()->hasRole('administrator')) return '! administrator';

    // //     // $tiles = Tile::get();

    // //     // return view('tiles', ['tiles' => $tiles]);

    // //      // $tile = new Tile;
    // //     // return Storage::url('');

    // //     // $room = Room::find(5);
    // //     // $rooms = Room::paginate(20);
    // //     // $rooms = Room::paginate(10);
    // //     // return $rooms;
    // //     // return response()->json($room);

    // //     // $surfaceTypes = SurfaceType::where('enabled', 1)->get();
    // //     // return response()->json($surfaceTypes);

    // //     // $roomsByType = Room2d::roomsByType();
    // //     // return response()->json($roomsByType);

    // //     // if (isset($option2)) return response()->json(['option1' => $option1]);
    // //     // return response()->json(['option1' => $option1, 'option2' => $option2]); //

    // //     return response()->json($this->texttttttt());

    //     // return response()->json([
    //     //     'engine_2d_enabled' => config('app.engine_2d_enabled'),
    //     //     'engine_3d_enabled' => config('app.engine_3d_enabled'),
    //     // ]);

    //     return response($_SERVER['SERVER_NAME']);
    // }
}
