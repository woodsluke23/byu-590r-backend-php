<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ChickenType extends Model
{
    use HasFactory;

    protected $fillable = ['chicken_type_name'];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}