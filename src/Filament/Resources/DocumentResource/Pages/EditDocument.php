<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentDocs\Filament\Actions\PrintAction;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            PrintAction::make()
                ->title($this->getRecord()->ref ?: $this->getRecord()->documentTemplate->name)
                ->route(PrintDocument::getUrl(['record' => $this->getRecord()])),
        ];
    }
}
