<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public static $types = [
        0 => 'Unknown',
        1 => 'Product',
    ];
    public static $product_type = 1;

    private static $public_fields = ['id', 'parent_id', 'type', 'name', 'title', 'note', 'surface', 'enabled'];

    public static function getByType($type) {
        if (!config('app.use_product_category')) return null;

        return Category::where([['enabled', 1], ['type', $type]])
            ->get(Category::$public_fields);
    }

    public static function getTreeByType($type) {
        if (!config('app.use_product_category')) return null;

        $parent_categories = Category::where([['enabled', 1], ['type', $type], ['parent_id', null]])
            ->get(Category::$public_fields);

        return Category::addChildren($parent_categories);
    }

    public static function getTree() {
        $parent_categories = Category::where('parent_id', null)
            ->get(Category::$public_fields);

            return Category::addChildren($parent_categories);
    }

    public function unsetChildren() {
        Category::where('parent_id', $this->id)
            ->update(['parent_id' => $this->parent_id]);
    }

    public function disableChildren() {
        Category::where('parent_id', $this->id)
            ->update(['enabled' => 0]);
    }

    private static function getChildren($id) {
        return Category::where('parent_id', $id)
            ->get(Category::$public_fields);
    }

    private static function addChildren($parent_categories) {
        foreach ($parent_categories as $parent_category) {
            $parent_category['children'] = Category::where('parent_id', $parent_category->id)
                ->get(Category::$public_fields);
        }

        return $parent_categories;
    }

}
