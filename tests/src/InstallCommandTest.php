<?php

use function Pest\Laravel\artisan;

it('runs the install command', function () {
    artisan('filament-docs:install')
        ->expectsOutput('Filament Docs installed successfully.')
        ->assertSuccessful();
});
