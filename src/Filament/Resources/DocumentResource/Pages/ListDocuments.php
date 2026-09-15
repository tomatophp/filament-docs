<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('templates')
                ->label(trans('filament-docs::messages.document-templates.title'))
                ->tooltip(trans('filament-docs::messages.document-templates.title'))
                ->hiddenLabel()
                ->icon('heroicon-o-document')
                ->color('info')
                ->url(DocumentTemplateResource::getUrl('index')),
        ];
    }
}
