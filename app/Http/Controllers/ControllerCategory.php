<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Validator;

use App;
use App\Category;
use App\SurfaceType;

class ControllerCategory extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getById($id) {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function categories() {
        return view('categories', [
            // 'categories' => Category::paginate(20),
            'categories_tree' => Category::getTree(),
            'category_types' => Category::$types,
            'surface_types' => SurfaceType::optionsAsArray(),
        ]);
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|integer|exists:categories,id',
            'type' => 'required|integer',
            'name' => 'required|max:100|string',
            'title' => 'nullable|max:255|string',
            'note' => 'nullable|max:255|string',
            'surface' => 'required|exists:surface_types,name', //in:wall,floor
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/categories')->withInput()->withErrors($validator);
        }

        $category = new Category;
        $category->parent_id = $request->parent_id;
        $category->type = $request->type;
        $category->name = $request->name;
        $category->title = $request->title;
        $category->note = $request->note;
        $category->surface = $request->surface;
        $category->enabled = 1;
        $category->save();

        return redirect('/categories');
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:categories,id',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'type' => 'required|integer',
            'name' => 'required|max:100|string',
            'title' => 'nullable|max:255|string',
            'note' => 'nullable|max:255|string',
            'surface' => 'required|exists:surface_types,name', //in:wall,floor
            'enabled' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect('/categories')->withInput()->withErrors($validator);
        }

        $category = Category::findOrFail($request->id);
        // $category->parent_id = $request->parent_id == $request->id ? null : $request->parent_id;
        $category->type = $request->type;
        $category->name = $request->name;
        $category->title = $request->title;
        $category->note = $request->note;
        $category->surface = $request->surface;
        if (isset($request->enabled)) { $category->enabled = 1; } else { $category->enabled = 0; }

        if ($request->parent_id != $request->id && is_numeric($request->parent_id) && $request->parent_id > 0) {
            $category->parent_id = $request->parent_id;
        } else {
            $category->parent_id = null;
        }

        $category->save();

        if (isset($category->parent_id)) {
            $category->unsetChildren();
        }

        if ($category->enabled != 1) {
            $category->disableChildren();
        }

        return redirect('/categories');
    }

    public function delete(Request $request) {
        $ids = json_decode($request->selectedCategories);
        foreach (Category::find($ids) as $category) {
            $category->unsetChildren();
        }

        Category::destroy($ids);

        return redirect('/categories');
    }

    public function enable(Request $request) {
        Category::whereIn('id', json_decode($request->selectedCategories))->update(['enabled' => 1]);

        return redirect('/categories');
    }

    public function disable(Request $request) {
        $ids = json_decode($request->selectedCategories);
        foreach (Category::find($ids) as $category) {
            $category->disableChildren();
        }

        Category::whereIn('id', json_decode($ids))->update(['enabled' => 0]);

        return redirect('/categories');
    }

}
