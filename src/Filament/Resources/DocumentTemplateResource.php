<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages\CreateDocumentTemplate;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages\EditDocumentTemplate;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentTemplateResource\Pages\ListDocumentTemplates;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;
use TomatoPHP\FilamentIcons\Components\IconColumn;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 3;

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

    public static function form(Schema $form): Schema
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
            TextInput::make('name')
                ->label(trans('filament-docs::messages.document-templates.form.name'))
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            KeyValue::make('vars')
                ->disabled()
                ->valueLabel(trans('filament-docs::messages.document-templates.form.vars-label'))
                ->keyLabel(trans('filament-docs::messages.document-templates.form.vars-key'))
                ->label(trans('filament-docs::messages.document-templates.form.vars'))
                ->columnSpanFull()
                ->default($keys),
            RichEditor::make('body')
                ->label(trans('filament-docs::messages.document-templates.form.body'))
                ->required()
                ->columnSpanFull(),
            IconPicker::make('icon')
                ->label(trans('filament-docs::messages.document-templates.form.icon')),
            ColorPicker::make('color')
                ->label(trans('filament-docs::messages.document-templates.form.color')),
            Toggle::make('is_active')
                ->label(trans('filament-docs::messages.document-templates.form.is_active')),

        ];

        if (filament('filament-docs')::$isScopedToTenant) {
            $schema[] = Select::make('team_id')
                ->label(trans('filament-docs::messages.document-templates.form.team_id'))
                ->visible(fn (Get $get) => $get('team_id') === null)
                ->default(filament()->getTenant()?->id)
                ->relationship('team', 'name');
        }

        return $form
            ->components($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-docs::messages.document-templates.form.name'))
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label(trans('filament-docs::messages.document-templates.form.is_active')),
                IconColumn::make('icon')
                    ->label(trans('filament-docs::messages.document-templates.form.icon'))
                    ->searchable(),
                ColorColumn::make('color')
                    ->label(trans('filament-docs::messages.document-templates.form.color'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::view.single.label')),
                EditAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::edit.single.label')),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::delete.single.label')),
                ReplicateAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::replicate.single.label')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListDocumentTemplates::route('/'),
            'create' => CreateDocumentTemplate::route('/create'),
            'edit' => EditDocumentTemplate::route('/{record}/edit'),
        ];
    }
}
