<?php
namespace {{namespace}}\Infrastructure; use Illuminate\Support\ServiceProvider;
final class {{Module}}ServiceProvider extends ServiceProvider {
  public function boot(): void { $this->loadRoutesFrom(__DIR__.'/Routes/api.php'); $this->loadMigrationsFrom(__DIR__.'/Migrations'); $this->mergeConfigFrom(__DIR__.'/Config/{{module}}.php', '{{module}}'); }
}
