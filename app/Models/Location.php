<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['lat', 'lng'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
