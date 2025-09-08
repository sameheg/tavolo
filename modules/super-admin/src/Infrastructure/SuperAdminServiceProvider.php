<?php
namespace CafeSaaS\SuperAdmin\Infrastructure;
use Illuminate\Support\ServiceProvider;
final class SuperAdminServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
