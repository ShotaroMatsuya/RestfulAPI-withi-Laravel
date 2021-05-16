<?php

namespace App\Providers;

use App\User;
use App\Buyer;
use App\Seller;
use Carbon\Carbon;
use App\Policies\UserPolicy;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Product;
use App\Transaction;
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
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //gateを定義
        Gate::define('admin-action', function ($user) {
            return $user->isAdmin();
        });

        // Passport::routes(null, ['prefix' => 'api/oauth', 'namespace' => '\Laravel\Passport\Http\Controllers']);
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30)); //access-tokenの有効期限は30min
        // Passport::tokensExpireIn(Carbon::now()->addSeconds(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); //refresh-tokenの有効期限は30日
        Passport::enableImplicitGrant();

        //registered required scope (scope(s)ミドルウェアの引数にわたす値)
        Passport::tokensCan([
            'purchase-product' => 'Create a new transaction for a specific product',
            'manage-products' => 'Create ,read , update , and delete products (CRUD)',
            'manage-account' => 'Read your account data, id , name , email , if verified, and if Cannot delete your account',
            'read-general' => 'Read general information like purchasing categories, purchased products selling products, selling categories, your transactions (purchases and sales)',
        ]);
    }
}
