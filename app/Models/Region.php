<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['locations', 'name'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($region) {
            $region->name = 'Region '.($region->id + 1);
        });
    }
}
