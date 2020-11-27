<?php

namespace App\Models\v1;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Redemption extends Model
{
    
    use HasApiTokens, Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'redemption_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'redemption_id', 
        'merchant_id', 
        'customer_id', 
        'customer_phone', 
        'points_to_one_cedi_rate_used',
        'redeemed_points',
        'redemption_cedi_equivalent_paid',
        'vendor_paid_fiat',
        'redemption_code', 
        'created_at',
        'updated_at',
    ];

    //
}
