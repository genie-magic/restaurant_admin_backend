<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name'];

    public function items() {
        return $this->hasMany(Item::class);
    }
}
