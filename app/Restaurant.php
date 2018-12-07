<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'image_url'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
