<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\softDeletes; 

class OfficeSpaceBenefit extends Model
{
    //
    use Hasfactory, softDeletes;
    protected $fillable = [
        'name',
        'office_space_id',
    ];
}

