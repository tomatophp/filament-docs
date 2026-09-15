<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Actions\Table\PrintAction;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages\CreateDocument;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages\EditDocument;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages\ListDocuments;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages\PrintDocument;
use TomatoPHP\FilamentDocs\Models\Document;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 2;

    public static function isScopedToTenant(): bool
    {
        return filament('filament-docs')::$isScopedToTenant;
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-docs::messages.documents.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-docs::messages.documents.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-docs::messages.documents.group');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-docs::messages.documents.title');
    }

    public static function form(Schema $form): Schema
    {
        $schema = [
            Select::make('document_template_id')
                ->preload()
                ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                ->searchable()
                ->relationship('documentTemplate', 'name')
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set, $record) {
                    if (! $record) {
                        $documentTemplate = DocumentTemplate::query()->find($get('document_template_id'));
                        if ($documentTemplate) {
                            $documentTemplateVars = $documentTemplate->documentTemplateVars;
                            $fields = [];
                            foreach ($documentTemplateVars as $var) {
                                $fields[] = [
                                    'var' => $var->var,
                                    'label' => FilamentDocs::load()->where('key', $var->var)->first()?->label,
                                    'key' => $var->value,
                                    'value' => '',
                                    'model' => FilamentDocs::load()->where('key', $var->var)->first()?->model,
                                ];
                            }

                            $collect = collect($fields)->sortBy('model')->toArray();
                            $set('body', $collect);
                        }
                    }
                })
                ->columnSpanFull()
                ->required(),
            Section::make(trans('filament-docs::messages.documents.form.document'))
                ->hidden(fn (Get $get) => (! $get('document_template_id')) || ! $get('body'))
                ->schema(function ($record) {
                    if ($record) {
                        return [
                            RichEditor::make('body')
                                ->label(trans('filament-docs::messages.documents.form.body'))
                                ->columnSpanFull()
                                ->required(),
                        ];
                    } else {
                        return [
                            Repeater::make('body')
                                ->hidden(fn ($record, Get $get) => $record || ! $get('body'))
                                ->schema([
                                    Hidden::make('var')->live(),
                                    Hidden::make('model')->live(),
                                    Hidden::make('key')->live(),
                                    TextInput::make('label')
                                        ->disabled()
                                        ->label(trans('filament-docs::messages.documents.form.var-label')),
                                    Select::make('value')
                                        ->label(trans('filament-docs::messages.documents.form.var-value'))
                                        ->searchable()
                                        ->options(function (Get $get) {
                                            if ($get('model') && $get('var')) {
                                                return $get('model')::query()->pluck(FilamentDocs::load()->where('key', $get('var'))->first()?->column, 'id')->toArray();
                                            } else {
                                                return [];
                                            }
                                        })
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            $body = [];
                                            $groups = [];
                                            foreach ($get('../../body') as $item) {
                                                if (! array_key_exists($item['model'], $groups)) {
                                                    if ($item['value']) {
                                                        $groups[$item['model']] = $item['value'];
                                                    } else {
                                                        $groups[$item['model']] = '';
                                                    }
                                                } else {
                                                    $item['value'] = $groups[$item['model']] ?? null;
                                                }

                                                $body[] = $item;
                                            }
                                            $set('../../body', $body);
                                        })
                                        ->required(),
                                ])
                                ->label(trans('filament-docs::messages.documents.form.values'))
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->columns(2)
                                ->columnSpanFull()
                                ->required(),
                        ];
                    }

                }),
            TextInput::make('ref')
                ->columnSpanFull()
                ->nullable(),
        ];

        if (filament('filament-docs')::$isScopedToTenant) {
            $schema[] = Select::make('team_id')
                ->label(trans('filament-docs::messages.documents.form.team_id'))
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
                TextColumn::make('id')
                    ->searchable()
                    ->prefix('#')
                    ->label(trans('filament-docs::messages.documents.form.id'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ref')
                    ->searchable()
                    ->label(trans('filament-docs::messages.documents.form.ref'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('documentTemplate.name')
                    ->badge()
                    ->color('warning')
                    ->icon(fn ($record) => $record->documentTemplate->icon)
                    ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                    ->url(fn ($record) => DocumentTemplateResource::getUrl('edit', ['record' => $record->documentTemplate->id]))
                    ->sortable(),
                TextColumn::make('model' . config('filament-docs.displayname_attribute'))
                    ->label(trans('filament-docs::messages.documents.form.model'))
                    ->badge()
                    ->color('info')
                    ->icon(function ($record) {
                        $resources = filament()->getCurrentOrDefaultPanel()->getResources();
                        foreach ($resources as $item) {
                            $resourceClass = app($item);
                            if ($resourceClass->getModel() === $record->model_type) {
                                return $resourceClass::getNavigationIcon();
                            }
                        }
                    })
                    ->url(function ($record) {
                        $resources = filament()->getCurrentOrDefaultPanel()->getResources();
                        foreach ($resources as $item) {
                            $resourceClass = app($item);
                            if ($resourceClass->getModel() === $record->model_type) {
                                try {
                                    return $resourceClass::getUrl('edit', ['record' => $record->model_id]);
                                } catch (Exception $e) {
                                    return '#';
                                }
                            }
                        }
                    })
                    ->sortable(),
                ToggleColumn::make('is_send')
                    ->label(trans('filament-docs::messages.documents.form.is_send')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('document_template_id')
                    ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                    ->searchable()
                    ->options(DocumentTemplate::query()->where('is_active', 1)->pluck('name', 'id')->toArray()),
            ])
            ->recordActions([
                Action::make('view')
                    ->color('info')
                    ->modalContent(fn ($record) => view('filament-docs::print', [
                        'record' => $record,
                    ]))
                    ->icon('heroicon-s-eye')
                    ->iconButton()
                    ->tooltip(__('filament-actions::view.single.label')),
                PrintAction::make('print')
                    ->icon('heroicon-s-printer')
                    ->title(fn ($record) => $record->documentTemplate->name . '#' . $record->id)
                    ->route(
                        fn ($record) => PrintDocument::getUrl(['record' => $record])
                    )
                    ->color('warning')
                    ->iconButton()
                    ->tooltip(trans('filament-docs::messages.documents.actions.print')),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
            'print' => PrintDocument::route('/{record}/print'),
        ];
    }
}
