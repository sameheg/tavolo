<?php
namespace App\Console\Commands;
use Illuminate\Console\Command; use Illuminate\Filesystem\Filesystem; use Illuminate\Support\Str;
class CafeSaaSInstall extends Command {
  protected $signature = 'cafesaas:install'; protected $description = 'Enable Registry + Tenancy + Pennant (providers + migrations + tables)';
  public function handle(): int {
    $this->info('CafeSaaS Installer...');
    $this->appendProviders([
      'App\\Providers\\ModuleRegistryServiceProvider::class',
      'App\\Providers\\TenancyServiceProvider::class',
      'App\\Providers\\FeatureServiceProvider::class',
    ]);
    try { \Artisan::call('vendor:publish', ['--tag'=>'cafesaas-registry','--force'=>true]); } catch(\Throwable $e){}
    try { \Artisan::call('tenancy:install'); } catch(\Throwable $e){}
    try { \Artisan::call('pennant:table'); } catch(\Throwable $e){}
    try { \Artisan::call('migrate', ['--force'=>true]); } catch(\Throwable $e){}
    $this->info('Done.');
    return 0;
  }
  private function appendProviders(array $providers): void {
    $fs = new Filesystem(); $path = base_path('config/app.php'); if(!$fs->exists($path)) return;
    $c = $fs->get($path);
    foreach ($providers as $prov) {
      if (Str::contains($c, $prov)) continue;
      $c = preg_replace_callback('/(\'providers\'\s*=>\s*\[)(.*?)(\])/s', function($m) use ($prov){
        $inside = $m[2]; $injected = rtrim($inside)."\n        ".$prov.",\n    ";
        return $m[1].$injected.$m[3];
      }, $c, 1);
    }
    $fs->put($path, $c);
  }
}
