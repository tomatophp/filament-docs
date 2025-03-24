<?php

namespace TomatoPHP\FilamentDocs\Filament\RelationManager;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;

class DocumentRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public static function getLabel(): ?string
    {
        return trans('filament-docs::messages.documents.title');
    }

    public function table(Table $table): Table
    {
        return DocumentResource::table($table);
    }

    public function form(Form $form): Form
    {
        return DocumentResource::form($form);
    }
}
