<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_name',
        'restaurant_description',
        'favorite_meal',
        'file',
        'sauce_id',
        'chicken_type_id',
    ];

    public function sauce()
    {
        return $this->belongsTo(Sauce::class);
    }

    public function chickenType()
    {
        return $this->belongsTo(ChickenType::class);
    }
}
