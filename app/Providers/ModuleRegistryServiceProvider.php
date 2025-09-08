<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class ModuleRegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $fs = new Filesystem();
        $patterns = config('module_registry.paths', []);

        foreach ($patterns as $pattern) {
            foreach (glob($pattern) as $moduleJson) {
                $meta = json_decode(file_get_contents($moduleJson), true);
                if (!is_array($meta)) { continue; }

                // Register providers
                foreach ($meta['providers'] ?? [] as $provider) {
                    try {
                        $this->app->register($provider);
                    } catch (\Throwable $e) {
                        logger()->warning('Module provider failed: '.$provider, ['error' => $e->getMessage()]);
                    }
                }
            }
        }

        // Publish config
        $this->publishes([
            __DIR__.'/../../config/module_registry.php' => config_path('module_registry.php'),
        ], 'cafesaas-registry');
    }
}
