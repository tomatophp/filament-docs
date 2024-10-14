<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages;

use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentTemplate extends CreateRecord
{
    protected static string $resource = DocumentTemplateResource::class;


    /**
     * @return void
     */
    public function afterCreate(): void
    {
        FilamentDocs::create($this->getRecord());
    }
}
