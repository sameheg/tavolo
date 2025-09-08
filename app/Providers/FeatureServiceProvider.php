<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
class FeatureServiceProvider extends ServiceProvider {
  public function register(): void {}
  public function boot(): void {
    Feature::resolveScopeUsing(function () {
      if (function_exists('tenant') && tenant()) { return 'tenant:'.tenant('id'); }
      return null;
    });
  }
}
