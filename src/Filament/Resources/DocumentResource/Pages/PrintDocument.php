<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;


class PrintDocument extends Page
{
    use InteractsWithRecord;

    protected static string $resource = DocumentResource::class;

    protected static string $layout = 'filament-docs::layout';
    protected static string $view = 'filament-docs::print';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
