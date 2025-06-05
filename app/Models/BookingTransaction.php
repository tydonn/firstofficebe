<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTransaction extends Model
{
    //
    use Hasfactory, softDeletes;
    protected $fillable = [
        'name',
        'phone_number',
        'booking_trx_id',
        'is_paid',
        'started_at',
        'total_amount',
        'duration',
        'ended_at',
        'office_space_id',
    ];

    public static function generateUnixTrxId()
    {
        $prefix = 'FO';
        do {
            $randomstring = $prefix . mt_rand(1000, 9999);
        } while (self::where('booking_trx_id', $randomstring)->exists());
 
        return $randomstring;
    }

    public function officeSpace(): BelongsTo
    {
        return $this->belongsTo(OfficeSpace::class, 'office_space_id');
    }


}
