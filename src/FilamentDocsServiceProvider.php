<?php

namespace TomatoPHP\FilamentDocs;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentDocs\Filament\RelationManager\DocumentRelationManager;
use TomatoPHP\FilamentDocs\Services\FilamentDocsServices;


class FilamentDocsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentDocs\Console\FilamentDocsInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-docs.php', 'filament-docs');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-docs.php' => config_path('filament-docs.php'),
        ], 'filament-docs-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-docs-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-docs');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-docs'),
        ], 'filament-docs-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-docs');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-docs'),
        ], 'filament-docs-lang');

        $this->app->bind('filament-docs', function () {
            return new FilamentDocsServices();
        });

        Livewire::component('tomato-p-h-p.filament-docs.filament.relation-manager.document-relation-manager', DocumentRelationManager::class);
    }

    public function boot(): void
    {
        //you boot methods here
    }
}
