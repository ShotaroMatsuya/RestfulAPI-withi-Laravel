<?php

namespace App\Providers;

use Carbon\Carbon;
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
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Passport::routes(null, ['prefix' => 'api/oauth', 'namespace' => '\Laravel\Passport\Http\Controllers']);
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30)); //access-tokenの有効期限は30min
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); //refresh-tokenの有効期限は30日

        //
    }
}
