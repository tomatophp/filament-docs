<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('templates')
                ->label(trans('filament-docs::messages.document-templates.title'))
                ->tooltip(trans('filament-docs::messages.document-templates.title'))
                ->hiddenLabel()
                ->icon('heroicon-o-document')
                ->color('info')
                ->url(DocumentTemplateResource::getUrl('index')),
        ];
    }
}
