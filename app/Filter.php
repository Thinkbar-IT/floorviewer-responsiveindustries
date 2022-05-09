<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $fillable = [
        'name',
        'field',
        'surface',
        'type',
        'values',
        'enabled',
    ];

    function getOptionsAttribute() {
        if (!$this->values) {
            return null;
        }
        return explode(',', $this->values);
    }

    function scopeField($builder, $fieldName) {
        return $builder->where('field', $fieldName);
    }
}
