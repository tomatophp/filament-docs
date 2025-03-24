<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentDocs\FilamentDocsPlugin;

it('registers plugin', function () {
    $panel = Filament::getCurrentPanel();

    $panel->plugins([
        FilamentDocsPlugin::make(),
    ]);

    expect($panel->getPlugin('filament-docs'))
        ->not()
        ->toThrow(Exception::class);
});
