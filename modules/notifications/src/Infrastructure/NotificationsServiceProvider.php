<?php
namespace CafeSaaS\Notifications\Infrastructure;
use Illuminate\Support\ServiceProvider;
final class NotificationsServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
