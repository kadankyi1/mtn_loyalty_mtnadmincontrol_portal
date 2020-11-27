<?php

namespace App\Models\v1;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchant extends Authenticatable
{

    use HasApiTokens, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchants';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'merchant_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id', 
        'merchant_name', 
        'merchant_location', 
        'merchant_scope', 
        'merchant_phone_number',
        'merchant_email',
        'merchant_pin',
        'password',
        'merchant_flagged',
        'merchant_balance',
        'merchant_vcode_user_id',
        'merchant_vcode',
        'merchant_vcode_link',
        'merchant_unpaid_claims',
        'admin_fullname',
        'admin_id',
        'merchant_balance',
        'pts_to_1_cedis_hvc',
        'pts_to_1_cedis_nc',
        'creator_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'merchant_pin', 'remember_token',
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //
}
