<?php

namespace TomatoPHP\FilamentDocs\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Actions\Table\PrintAction;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;
use TomatoPHP\FilamentDocs\Models\Document;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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

    public static function form(Form $form): Form
    {
        $schema = [
            Forms\Components\Select::make('document_template_id')
                ->preload()
                ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                ->searchable()
                ->relationship('documentTemplate', 'name')
                ->live()
                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $record) {
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
            Forms\Components\Section::make(trans('filament-docs::messages.documents.form.document'))
                ->hidden(fn (Forms\Get $get) => (! $get('document_template_id')) || ! $get('body'))
                ->schema(function ($record) {
                    if ($record) {
                        return [
                            TiptapEditor::make('body')
                                ->label(trans('filament-docs::messages.documents.form.body'))
                                ->required(),
                        ];
                    } else {
                        return [
                            Forms\Components\Repeater::make('body')
                                ->hidden(fn ($record, Forms\Get $get) => $record || ! $get('body'))
                                ->schema([
                                    Forms\Components\Hidden::make('var')->live(),
                                    Forms\Components\Hidden::make('model')->live(),
                                    Forms\Components\Hidden::make('key')->live(),
                                    Forms\Components\TextInput::make('label')
                                        ->disabled()
                                        ->label(trans('filament-docs::messages.documents.form.var-label')),
                                    Forms\Components\Select::make('value')
                                        ->label(trans('filament-docs::messages.documents.form.var-value'))
                                        ->searchable()
                                        ->options(function (Forms\Get $get) {
                                            if ($get('model') && $get('var')) {
                                                return $get('model')::query()->pluck(FilamentDocs::load()->where('key', $get('var'))->first()?->column, 'id')->toArray();
                                            } else {
                                                return [];
                                            }
                                        })
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
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
            Forms\Components\TextInput::make('ref')
                ->columnSpanFull()
                ->nullable(),
        ];

        if (filament('filament-docs')::$isScopedToTenant) {
            $schema[] = Forms\Components\Select::make('team_id')
                ->label(trans('filament-docs::messages.documents.form.team_id'))
                ->visible(fn (Forms\Get $get) => $get('team_id') === null)
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
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->prefix('#')
                    ->label(trans('filament-docs::messages.documents.form.id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ref')
                    ->searchable()
                    ->label(trans('filament-docs::messages.documents.form.ref'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('documentTemplate.name')
                    ->badge()
                    ->color('warning')
                    ->icon(fn ($record) => $record->documentTemplate->icon)
                    ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                    ->url(fn ($record) => DocumentTemplateResource::getUrl('edit', ['record' => $record->documentTemplate->id]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('model' . config('filament-docs.displayname_attribute'))
                    ->label(trans('filament-docs::messages.documents.form.model'))
                    ->badge()
                    ->color('info')
                    ->icon(function ($record) {
                        $resources = filament()->getCurrentPanel()->getResources();
                        foreach ($resources as $item) {
                            $resourceClass = app($item);
                            if ($resourceClass->getModel() === $record->model_type) {
                                return $resourceClass::getNavigationIcon();
                            }
                        }
                    })
                    ->url(function ($record) {
                        $resources = filament()->getCurrentPanel()->getResources();
                        foreach ($resources as $item) {
                            $resourceClass = app($item);
                            if ($resourceClass->getModel() === $record->model_type) {
                                try {
                                    return $resourceClass::getUrl('edit', ['record' => $record->model_id]);
                                } catch (\Exception $e) {
                                    return '#';
                                }
                            }
                        }
                    })
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_send')
                    ->label(trans('filament-docs::messages.documents.form.is_send')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('document_template_id')
                    ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                    ->searchable()
                    ->options(DocumentTemplate::query()->where('is_active', 1)->pluck('name', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
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
                        fn ($record) => Pages\PrintDocument::getUrl(['record' => $record])
                    )
                    ->color('warning')
                    ->iconButton()
                    ->tooltip(trans('filament-docs::messages.documents.actions.print')),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'print' => Pages\PrintDocument::route('/{record}/print'),
        ];
    }
}
