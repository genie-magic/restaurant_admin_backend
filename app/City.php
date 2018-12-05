<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'image_url'];

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
