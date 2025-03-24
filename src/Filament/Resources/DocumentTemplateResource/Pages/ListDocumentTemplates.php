<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;

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
