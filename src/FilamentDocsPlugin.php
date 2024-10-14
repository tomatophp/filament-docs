<?php

namespace TomatoPHP\FilamentDocs;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use TomatoPHP\FilamentDocs\Filament\RelationManager\DocumentRelationManager;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

class FilamentDocsPlugin implements Plugin
{

    public static bool $isScopedToTenant = false;

    public function getId(): string
    {
        return 'filament-docs';
    }

    public function isScopedToTenant(bool $isScopedToTenant = true): static
    {
        static::$isScopedToTenant = $isScopedToTenant;
        return $this;
    }



    public function register(Panel $panel): void
    {
        $panel->resources([
           DocumentTemplateResource::class,
           DocumentResource::class
        ]);
    }

    public function boot(Panel $panel): void
    {
          FilamentAsset::register([
              Css::make('filament-docs', __DIR__.'/../publish/public/css/filament-docs.css')
          ]);
    }

    public static function make(): static
    {
        return new static();
    }
}
