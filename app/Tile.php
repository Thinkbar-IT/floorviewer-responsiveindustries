<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Image;

class Tile extends Model
{
    protected $appends = ['icon'];

    public function getFileAttribute($value)
    {
        if ($value) return Storage::url($value);

        return Storage::url('tiles/nofile.png');
    }

    public function getAccessLevelAttribute($value)
    {
        if (isset($value)) return $value;

        return 0;
    }

    public function getUrlAttribute($value)
    {
        if (isset($value)) return $value;

        $default_url = config('app.product_info_default_url');
        if ($default_url) return $default_url;
    }

    public function getIconFileName($file_name)
    {
        $path_parts = pathinfo($file_name);
        return $path_parts['dirname'] . '/icons/' . $path_parts['basename'];
    }

    public function makeIcon($file_name) {
        $direct_file_path = '../storage/app/public/' . ltrim($file_name, '/');
        $icon = Image::make($direct_file_path);

        $width = 100;
        $height = 100;
        $icon->width() < $icon->height() ? $width = null : $height = null;
        $icon->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $icon_file_name = $this->getIconFileName($direct_file_path);
        $icon->save(ltrim($icon_file_name, '/'));

        return '/' . $icon_file_name;
    }

    public function getIconAttribute()
    {
        $original_file = $this->getOriginal('file');
        $stored_file = $this->file;
        if ($original_file) {
            return $this->getIconFileName($stored_file);
        } else {
            return $stored_file;
        }
    }

    public function saveFile($file) {
        $file_name = $file->store('tiles', 'public');
        $this->makeIcon($file_name); // todo use stored path
        // $this->makeIcon(Storage::url($file_name));
        return $file_name;
    }

    private function copyFile($source, $new_file_name) {
        $path_parts = pathinfo($source);
        $dest = $path_parts['dirname'] . '/' . $new_file_name . '.' . $path_parts['extension'];

        copy(ltrim($source, '/'), ltrim($dest, '/'));

        return $dest;
    }

    public function updateFile($file) {
        if ($file) {
            $this->deleteFile();
            return $this->saveFile($file);
        }
        return $this->file;
    }

    private function copyIcon($source, $new_file_name) {
        $path_parts = pathinfo($source);
        $source_file_name = $path_parts['dirname'] . '/icons/' . $path_parts['basename'];
        $dest_file_name = $path_parts['dirname'] . '/icons/' . $new_file_name . '.' . $path_parts['extension'];
        copy(ltrim($source_file_name, '/'), ltrim($dest_file_name, '/'));
    }

    public function deleteIcon() {
        $file = $this->getOriginal('file');
        $icon = $this->getIconFileName($file);
        Storage::disk('public')->delete($icon);
    }

    public function saveIcon($icon, $file_name = null) { // todo fix
        if (!$file_name) $file_name = $this->getOriginal('file');
        $icon_file_name = $this->getIconFileName($file_name);

        Storage::disk('public')->putFileAs('', $icon, $icon_file_name);
        return $icon_file_name;
    }

    public function updateIcon($icon) {
        if ($icon) {
            $this->deleteIcon();
            return $this->saveIcon($icon);
        }
    }

    public function copyImage() {
        $new_file_name = uniqid('', true);
        $new_full_name = $this->copyFile($this->file, $new_file_name);

        $this->copyIcon($this->file, $new_file_name);

        return str_ireplace('/storage/', '', $new_full_name);
    }

    public function deleteFile() {
        $file = $this->getOriginal('file');
        if ($file && strpos($file, 'http://') === false && strpos($file, 'https://') === false) {
            Storage::disk('public')->delete($file);

            $this->deleteIcon();
            // $icon_file = $this->getIconFileName($file);
            // Storage::disk('public')->delete($icon_file);
        }
    }

    public function del() {
        $this->deleteFile();
        $this->delete();
    }

    public function setData($request, $name, $tile_url) {
        $this->name = $name;
        if ($request->shape) $this->shape = $request->shape;
        $this->width = $request->width;
        $this->height = $request->height;
        if ($request->surface) $this->surface = $request->surface;
        if ($request->finish) {
            $this->finish = $request->finish;
        } else if (config('app.product_finish')) {
            $this->finish = config('app.product_finish');
        }

        $this->file = $tile_url;

        $this->grout = 1;
        $this->rotoPrintSetName = $request->rotoPrintSetName;
        $this->expProps = $request->expProps;
        if (config('app.tiles_access_level')) $this->access_level = $request->accessLevel;
        $this->enabled = 0;
    }

    public static function getIds($filter = null) {
        if (isset($filter)) {
            $tiles = Tile::where($filter)->get();
        } else {
            $tiles = Tile::get();
        }
        $tileIds = [];
        foreach ($tiles as $tile) {
            array_push($tileIds, $tile->id);
        }
        return $tileIds;
    }
}
