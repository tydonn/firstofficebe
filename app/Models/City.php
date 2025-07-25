<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Support\Str;

class City extends Model
{
    //
    use Hasfactory, softDeletes;
    protected $fillable = [
        'name',
        'slug',
        'photo',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function OfficeSpaces(): HasMany
    {
        return $this->hasMany(OfficeSpace::class);
    }
}
