<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;

class TenancyServiceProvider extends ServiceProvider {
  public function register(): void {}
  public function boot(): void {
    Event::listen(TenantCreated::class, function (TenantCreated $event) {
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
