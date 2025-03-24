<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;

class EditDocumentTemplate extends EditRecord
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['vars'] = array_merge(
            FilamentDocs::load()->pluck('label', 'key')->toArray(),
            [
                '$UUID' => trans('filament-docs::messages.vars.uuid'),
                '$RANDOM' => trans('filament-docs::messages.vars.random'),
                '$DAY' => trans('filament-docs::messages.vars.day'),
                '$DATE' => trans('filament-docs::messages.vars.date'),
                '$TIME' => trans('filament-docs::messages.vars.time'),
            ]
        );

        return $data;
    }

    public function afterSave(): void
    {
        FilamentDocs::create($this->getRecord());
    }
}
