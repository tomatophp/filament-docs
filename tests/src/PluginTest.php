<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;
use TomatoPHP\FilamentDocs\FilamentDocsPlugin;
use TomatoPHP\FilamentDocs\Services\FilamentDocsServices;

it('registers plugin', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getPlugin('filament-docs'))->toBeInstanceOf(FilamentDocsPlugin::class)
        ->and($panel->getResources())->toContain(DocumentResource::class, DocumentTemplateResource::class);
});

it('boots the service provider', function () {
    expect(config('filament-docs.views.layout'))->toBe('filament-docs::layout')
        ->and(app('filament-docs'))->toBeInstanceOf(FilamentDocsServices::class)
        ->and(view()->exists('filament-docs::print'))->toBeTrue();
});
