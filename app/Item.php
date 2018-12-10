<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable=['name', 'price', 'image_url', 'menu_id'];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}
