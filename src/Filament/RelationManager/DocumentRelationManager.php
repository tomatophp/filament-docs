<?php

namespace TomatoPHP\FilamentDocs\Filament\RelationManager;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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

    public function form(Schema $schema): Schema
    {
        return DocumentResource::form($schema);
    }
}
