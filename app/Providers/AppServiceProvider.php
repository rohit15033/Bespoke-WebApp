<?php

namespace App\Providers;

use App\Models\OrderItem;
use App\Models\OrderPackage;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Schema::defaultStringLength(191);

        // Register morph map for polymorphic relationships
        Relation::morphMap([
            'package' => OrderPackage::class,
            'item' => OrderItem::class,
        ]);

        Gate::define('delete-inventory', function (User $user) {
            return $user->isMaster();
        });
    }
}
