<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name : Module StudlyName}';
    protected $description = 'Scaffold a new Module (Domain/Application/Infrastructure + provider + composer + module.json)';
    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $slug = Str::kebab($name);
        $root = base_path("modules/{$slug}");

        $fs = new Filesystem();
        if ($fs->exists($root)) {
            $this->error("Module {$name} already exists.");
            return 1;
        }

        // Copy stubs
        $stubRoot = base_path('stubs/module');
        $fs->copyDirectory($stubRoot, $root);

        // Replace tokens in files
        $files = $fs->allFiles($root);
        foreach ($files as $file) {
            $contents = str_replace(
                ['{{Module}}','{{module}}','{{namespace}}'],
                [$name, $slug, "CafeSaaS\\{$name}"],
                $file->getContents()
            );
            file_put_contents($file->getPathname(), $contents);

            # Rename placeholders in paths
            $newPath = str_replace(['__Module__','__module__'], [$name, $slug], $file->getPathname());
            if ($newPath !== $file->getPathname()) {
                $fs->move($file->getPathname(), $newPath);
            }
        }

        $this->info("Module {$name} scaffolded at modules/{$slug}");
        $this->line("Add \"cafesaas/{$slug}\": \"*\" to root composer.json if not present, then run composer install.");
        return 0;
    }
}
