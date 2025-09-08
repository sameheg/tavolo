<?php
namespace CafeSaaS\Security\Infrastructure;
use Illuminate\Support\ServiceProvider;
final class SecurityServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
