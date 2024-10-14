<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources;

use FilamentTiptapEditor\TiptapEditor;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\RelationManagers;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use TomatoPHP\FilamentIcons\Components\IconColumn;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 3;

    /**
     * @return bool
     */
    public static function isScopedToTenant(): bool
    {
        return filament('filament-docs')::$isScopedToTenant;
    }


    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-docs::messages.document-templates.title');
    }

    /**
     * @return string|null
     */
    public static function getLabel(): ?string
    {
        return trans('filament-docs::messages.document-templates.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-docs::messages.document-templates.group');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-docs::messages.document-templates.title');
    }

    public static function form(Form $form): Form
    {

        $keys = array_merge(
            FilamentDocs::load()->pluck('label', 'key')->toArray(),
            [
                '$UUID' => trans('filament-docs::messages.vars.uuid'),
                '$RANDOM' => trans('filament-docs::messages.vars.random'),
                '$DAY' => trans('filament-docs::messages.vars.day'),
                '$DATE' => trans('filament-docs::messages.vars.date'),
                '$TIME' => trans('filament-docs::messages.vars.time'),
            ]
        );

        $schema = [
            Forms\Components\TextInput::make('name')
                ->label(trans('filament-docs::messages.document-templates.form.name'))
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            Forms\Components\KeyValue::make('vars')
                ->disabled()
                ->valueLabel(trans('filament-docs::messages.document-templates.form.vars-label'))
                ->keyLabel(trans('filament-docs::messages.document-templates.form.vars-key'))
                ->label(trans('filament-docs::messages.document-templates.form.vars'))
                ->columnSpanFull()
                ->default($keys),
            TiptapEditor::make('body')
                ->label(trans('filament-docs::messages.document-templates.form.body'))
                ->required()
                ->columnSpanFull(),
            IconPicker::make('icon')
                ->label(trans('filament-docs::messages.document-templates.form.icon')),
            Forms\Components\ColorPicker::make('color')
                ->label(trans('filament-docs::messages.document-templates.form.color')),
            Forms\Components\Toggle::make('is_active')
                ->label(trans('filament-docs::messages.document-templates.form.is_active')),

        ];

        if(filament('filament-docs')::$isScopedToTenant){
            $schema[] = Forms\Components\Select::make('team_id')
                ->label(trans('filament-docs::messages.document-templates.form.team_id'))
                ->visible(fn(Forms\Get $get) => $get('team_id') === null)
                ->default(filament()->getTenant()?->id)
                ->relationship('team', 'name');
        }

        return $form
            ->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-docs::messages.document-templates.form.name'))
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(trans('filament-docs::messages.document-templates.form.is_active')),
                IconColumn::make('icon')
                    ->label(trans('filament-docs::messages.document-templates.form.icon'))
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(trans('filament-docs::messages.document-templates.form.color'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::view.single.label')),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::edit.single.label')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::delete.single.label')),
                Tables\Actions\ReplicateAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::replicate.single.label')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentTemplates::route('/'),
            'create' => Pages\CreateDocumentTemplate::route('/create'),
            'edit' => Pages\EditDocumentTemplate::route('/{record}/edit'),
        ];
    }
}
