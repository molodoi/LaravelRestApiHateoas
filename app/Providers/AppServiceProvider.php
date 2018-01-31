<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\User;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Mysql supporte 255 caractères en UTF8 et 191 en UTF8mb4
        // https://laravel-news.com/laravel-5-4-key-too-long-error
        Schema::defaultStringLength(191);

        // Send verification mail using event
        User::created(function($user) {
            // en cas d'erreur rééssai 5 fois d'envoyer le mail toute les 100ms 
            retry(5, function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        User::updated(function($user) {
            //check if email change - laravel verify only if email change
            if ($user->isDirty('email')) {
                retry(5, function() use ($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        // Event update : Vérifier/changer le statut du produit à chaque changement
        Product::updated(function($product) {
            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;
                $product->save();
            }
        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
