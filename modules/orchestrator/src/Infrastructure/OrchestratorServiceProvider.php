<?php
namespace CafeSaaS\Orchestrator\Infrastructure;
use Illuminate\Support\ServiceProvider;
final class OrchestratorServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
