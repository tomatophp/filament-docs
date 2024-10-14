<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;

use Carbon\Carbon;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['body'] = FilamentDocs::body($data['document_template_id'], $data['body']??null);
        return $data;
    }
}
