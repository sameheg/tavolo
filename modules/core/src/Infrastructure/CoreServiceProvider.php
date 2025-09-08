<?php
namespace CafeSaaS\Core\Infrastructure;
use Illuminate\Support\ServiceProvider;
final class CoreServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
