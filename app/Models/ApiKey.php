<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\softDeletes;

class ApiKey extends Model
{
    //
    use Hasfactory, softDeletes;
    protected $fillable = [
        'name',
        'key',
    ];
}
