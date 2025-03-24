<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;

class PrintDocument extends Page
{
    use InteractsWithRecord;

    protected static string $resource = DocumentResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getLayout(): string
    {
        return config('filament-docs.views.layout') ?: 'filament-docs::layout';
    }

    public function getView(): string
    {
        return config('filament-docs.views.view') ?: 'filament-docs::print';
    }
}
