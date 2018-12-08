<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable=['name', 'price', 'image_url'];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}
