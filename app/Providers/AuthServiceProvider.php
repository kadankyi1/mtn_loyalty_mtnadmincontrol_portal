<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Passport::routes();

        Passport::tokensCan([

            //ADMIN SCOPE
            'admin_add_admin' => 'Add admin',
            'admin_update_admin' => 'Update admin',
            'admin_view_admins' => 'View admins',

            'admin_add_merchant' => 'Add merchant',
            'admin_update_merchant' => 'Update merchant',
            'admin_view_merchant' => 'View merchants',

            'admin_view_redemptions' => 'View redemptions',

            //admin_add_admin admin_update_admin admin_view_admins admin_add_merchant admin_update_merchant admin_view_merchant admin_view_redemptions
            
            //MERCHANT SCOPES
            'merchant_view_redemptions' => 'View redemptions',
            'merchant_accept_redemptions' => 'Accept redemptions',
            //merchant_view_redemptions merchant_accept_redemptions
        ]);
        //
    }
}
