<?php
namespace CafeSaaS\Integrations\Infrastructure;
use Illuminate\Support\ServiceProvider;
final class IntegrationsServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
