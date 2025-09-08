<?php
namespace App\Providers; use Illuminate\Support\ServiceProvider;
class ModuleRegistryServiceProvider extends ServiceProvider {
  public function register(): void {}
  public function boot(): void {
    foreach (config('module_registry.paths',[]) as $pattern) {
      foreach (glob($pattern) as $file) {
        $meta = json_decode(file_get_contents($file), true); if(!is_array($meta)) continue;
        foreach(($meta['providers']??[]) as $p){ try{$this->app->register($p);}catch(\Throwable $e){logger()->warning('Module provider failed',['p'=>$p,'e'=>$e->getMessage()]);}}
      }
    }
    $this->publishes([ __DIR__.'/../../config/module_registry.php' => config_path('module_registry.php') ], 'cafesaas-registry');
  }
}
