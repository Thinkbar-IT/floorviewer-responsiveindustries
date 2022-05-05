<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Image;

use App\CustomTile;

class ControllerCustomTile extends Controller
{

    /**
     * AJAX
     *
     * @return json
     */
    public function getBlankTiles()
    {
        $directories = Storage::disk('public')->directories('tilesdesigner');

        $blank_tiles = [];
        foreach ($directories as $directory) {
            $sub_directories = Storage::disk('public')->directories($directory);
            $sub_dirs = [];
            foreach ($sub_directories as $sub_directory) {
                $sub_files = Storage::disk('public')->files($sub_directory);

                $suggestions = [];
                foreach ($sub_files as $sub_file) {
                    if (!preg_match('/icon\.png$/i', $sub_file)) {
                        $suggestions[] = Storage::url($sub_file);
                    }
                }
                $sub_dirs[mb_strtolower(basename($sub_directory))] = $suggestions;
            }

            $files = Storage::disk('public')->files($directory);
            foreach ($files as $file) {
                if (preg_match('/\.png$/i', $file) && !preg_match('/\.icon\.png$/i', $file)) {
                    $blank_tile = [
                        'shape' => basename($directory),
                        'file' => Storage::url($file),
                    ];

                    $icon_file = preg_replace('/\.png$/i', '.icon.png', $file);
                    if (Storage::disk('public')->exists($icon_file)) {
                        $blank_tile['icon'] = Storage::url($icon_file);
                    } else {
                        $icon_file = preg_replace('/\.png$/i', '/icon.png', $file);
                        if (Storage::disk('public')->exists($icon_file)) {
                            $blank_tile['icon'] = Storage::url($icon_file);
                        }
                    }

                    $file_basename = mb_strtolower(basename($file, '.png'));
                    if (array_key_exists($file_basename, $sub_dirs)) {
                        $blank_tile['suggestions'] = $sub_dirs[$file_basename];
                    }

                    $blank_tiles[] = $blank_tile;
                }
            }
        }
        return response()->json($blank_tiles);
    }

    public function getUserTiles() {
        $custom_tiles = [];
        $fields = ['id', 'name', 'shape', 'file', 'width', 'height', 'settings'];
        if (Auth::id()) {
            $custom_tiles = CustomTile::where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('session_token', session('_token'));
            })->get($fields);
        } else {
            $custom_tiles = CustomTile::where('session_token', session('_token'))->get($fields);
        }

        return response()->json($custom_tiles);
    }

    public function getTilesById(Request $request) {
        $validator = Validator::make($request->all(), [
            'roomTileIds' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $fields = ['id', 'name', 'shape', 'file', 'width', 'height', 'settings'];
        $custom_tiles = CustomTile::select($fields)->find($request->roomTileIds);
        return response()->json($custom_tiles);
    }

    private function generateFileName($dir, $ext) {
        $file_name = md5(uniqid('', true));
        while (Storage::disk('public')->exists($dir . $file_name . $ext)) {
            $file_name = md5(uniqid('', true));
        }
        return $file_name;
    }

    public function saveSuggestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'index' => 'required|max:10|numeric',
            'image' => 'required|string',
            'shape' => 'required|max:100|string',
            'baseTileUrl' => 'required|max:1000|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (Auth::check() && Auth::user()->hasRole('editor')) {
            $file_basename = basename($request->baseTileUrl, '.png');
            $path = 'tilesdesigner/' . $request->shape . '/' . $file_basename . '/';
            $file_name = $path . $this->generateFileName($path, '.png') . '.png';

            $image = Image::make($request->image);
            Storage::disk('public')->put($file_name,  $image->encode('png'));

            return response()->json(['file' => Storage::url($file_name)]);
        }
    }

    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'shape' => 'required|max:100|string',
            'image' => 'required|string',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'settings' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $customTile = new CustomTile;

        $image = Image::make($request->image);
        $file_name = '';
        if (isset($image)) {
            $file_name = 'customtiles/' . $this->generateFileName('customtiles/', '.png') . '.png';
            Storage::disk('public')->put($file_name,  $image->encode('png'));
            $customTile->file = $file_name;
        }
        $customTile->shape = $request->shape;
        $customTile->width = $request->width;
        $customTile->height = $request->height;
        $customTile->settings = $request->settings;

        $user_id = Auth::id();
        if ($user_id) {
            $customTile->user_id = $user_id;
        } else {
            $customTile->session_token = session('_token');
        }
        $customTile->save();

        return response()->json([
            'id' => $customTile->id,
            'file' => $customTile->file,
            'shape' => $customTile->shape,
            'width' => $customTile->width,
            'height' => $customTile->height,
            'settings' => $customTile->settings,
        ]);
    }

    public function remove($id) {
        $customTile = CustomTile::findOrFail($id);
        if ($customTile
            && (($customTile->user_id && $customTile->user_id == Auth::id())
                || ($customTile->session_token && $customTile->session_token == session('_token')))) {
            $file = $customTile->getOriginal('file');
            Storage::disk('public')->delete($file);
            $customTile->delete();
            return response()->json(['removed' => true, 'file' => $file]);
        }
        return response()->json(['removed' => false]);
    }
}
