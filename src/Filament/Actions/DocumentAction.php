<?php

namespace TomatoPHP\FilamentDocs\Filament\Actions;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Models\Document;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;

class DocumentAction extends Action
{
    protected array | \Closure $vars = [];

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->requiresConfirmation();
        $this->iconButton();
        $this->name('document');
        $this->icon('heroicon-m-document-text');
        $this->label(trans('filament-docs::messages.documents.actions.document.title'));
        $this->tooltip(trans('filament-docs::messages.documents.actions.document.title'));
        $this->color('success');
        $this->form([
            Forms\Components\Select::make('document_template_id')
                ->preload()
                ->label(trans('filament-docs::messages.documents.form.document_template_id'))
                ->searchable()
                ->options(DocumentTemplate::query()->where('is_active', 1)->pluck('name', 'id')->toArray())
                ->columnSpanFull()
                ->required(),
        ]);
        $this->action(function (array $data, $record) {
            $body = FilamentDocs::body($data['document_template_id'], $this->getVars());

            $document = Document::query()->create([
                'model_id' => $record->id,
                'model_type' => get_class($record),
                'document_template_id' => $data['document_template_id'],
                'body' => $body,
            ]);

            Notification::make()
                ->title(trans('filament-docs::messages.documents.actions.document.notification.title'))
                ->body(trans('filament-docs::messages.documents.actions.document.notification.body'))
                ->actions([
                    \Filament\Notifications\Actions\Action::make('document')
                        ->icon('heroicon-o-document-text')
                        ->label(trans('filament-docs::messages.documents.actions.document.notification.action'))
                        ->url(DocumentResource::getUrl('edit', ['record' => $document]))
                        ->openUrlInNewTab(),
                ])
                ->success()
                ->send();
        });
    }

    /**
     * @return $this
     */
    public function vars(array | \Closure $var): static
    {
        $this->vars = $var;

        return $this;
    }

    protected function getVars(): array
    {
        if ($this->vars instanceof \Closure) {
            return $this->evaluate($this->vars);
        }

        return $this->vars;
    }
}
