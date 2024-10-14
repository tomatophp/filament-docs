<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages;

use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentTemplates extends ListRecords
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
