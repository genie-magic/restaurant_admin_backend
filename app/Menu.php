<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'restaurant_id', 'menu_id', 'image_url'];

    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
