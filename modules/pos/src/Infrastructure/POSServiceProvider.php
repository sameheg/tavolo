<?php
namespace CafeSaaS\POS\Infrastructure;

use Illuminate\Support\ServiceProvider;

final class POSServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
