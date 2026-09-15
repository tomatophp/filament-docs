<?php

namespace TomatoPHP\FilamentDocs\Tests;

use Filament\Actions\Action;
use TomatoPHP\FilamentDocs\Filament\Actions\DocumentAction;
use TomatoPHP\FilamentDocs\Filament\Actions\Notifications\PrintAction as NotificationPrintAction;
use TomatoPHP\FilamentDocs\Filament\Actions\PrintAction;
use TomatoPHP\FilamentDocs\Filament\Actions\Table\PrintAction as TablePrintAction;
use TomatoPHP\FilamentDocs\Filament\RelationManager\DocumentRelationManager;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;
use TomatoPHP\FilamentDocs\Tests\Models\Document;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs($this->user = User::factory()->create());
});

it('ships the document and print actions as filament actions', function (string $class, string $name) {
    $action = $class::make();

    expect($action)->toBeInstanceOf(Action::class)
        ->and($action->getName())->toBe($name);
})->with([
    'document action' => [DocumentAction::class, 'document'],
    'page print action' => [PrintAction::class, 'print'],
    'table print action' => [TablePrintAction::class, 'print'],
    'notification print action' => [NotificationPrintAction::class, 'print'],
]);

it('evaluates the print action route and title', function (string $class) {
    $action = $class::make()
        ->route(fn () => 'https://example.test/print/1')
        ->title('Contract #1');

    expect($action->getRoute())->toBe('https://example.test/print/1')
        ->and($action->getTitle())->toBe('Contract #1');
})->with([
    PrintAction::class,
    TablePrintAction::class,
    NotificationPrintAction::class,
]);

it('can call the print action on the edit document page', function () {
    $document = Document::factory()->withId(DocumentTemplate::factory()->create()->id)->create();

    livewire(Pages\EditDocument::class, ['record' => $document->getRouteKey()])
        ->assertActionExists('print')
        ->callAction('print')
        ->assertHasNoActionErrors();
});

it('can call the print and view table actions on the documents list', function () {
    $document = Document::factory()->withId(DocumentTemplate::factory()->create()->id)->create();

    livewire(Pages\ListDocuments::class)
        ->assertTableActionExists('print', record: $document)
        ->callTableAction('print', $document)
        ->assertHasNoTableActionErrors()
        ->mountTableAction('view', $document)
        ->assertSuccessful();
});

it('renders the document relation manager for a model using InteractsWithDocs', function () {
    $template = DocumentTemplate::factory()->create();

    $documents = Document::factory()->count(3)->withId($template->id)->create([
        'model_type' => User::class,
        'model_id' => $this->user->id,
    ]);

    livewire(DocumentRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => Pages\EditDocument::class,
    ])
        ->assertSuccessful()
        ->loadTable()
        ->assertCanSeeTableRecords($documents);
});
