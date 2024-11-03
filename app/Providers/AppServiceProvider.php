<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\FornecedorRepositoryInterface;
use App\Repositories\FornecedorRepository;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FornecedorRepositoryInterface::class, FornecedorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
