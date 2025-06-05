<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\softDeletes; 

class OfficeSpacePhoto extends Model
{
    //
    use Hasfactory, softDeletes;
    protected $fillable = [
        'photo',
        'office_space_id',
    ];
}
