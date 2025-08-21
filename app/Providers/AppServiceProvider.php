<?php

namespace App\Providers;

use App\Models\OrderItem;
use App\Models\OrderPackage;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
        // Register morph map for polymorphic relationships
        Relation::morphMap([
            'package' => OrderPackage::class,
            'item' => OrderItem::class,
        ]);
    }
}
