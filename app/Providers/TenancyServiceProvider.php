<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Tenancy;
use Illuminate\Support\Facades\Artisan;

class TenancyServiceProvider extends ServiceProvider {
  public function register(): void {}
  public function boot(): void {
    Tenancy::eventListener(TenantCreated::class, function (TenantCreated $event) {
      $defaults = config('module_registry.defaults', []);
      $event->tenant->run(function () use ($defaults) {
        foreach ($defaults as $module) {
          $path = base_path("modules/{$module}/src/Infrastructure/Migrations");
          if (is_dir($path)) {
            Artisan::call('migrate', ['--path' => "modules/{$module}/src/Infrastructure/Migrations", '--force' => true]);
          }
        }
      });
    });
  }
}
