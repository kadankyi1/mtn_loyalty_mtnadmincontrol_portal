<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Claim extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'claim_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'claim_id', 
        'claim_amount', 
        'paid_status',
        'claim_flagged',
        'merchant_id',
        'merchant_fullname',
        'merchant_phone_number',
        'admin_fullname',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id_or_phone_or_email', 'remember_token',
    ];

}
