<?php

namespace TomatoPHP\FilamentDocs\Tests;

use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages;
use TomatoPHP\FilamentDocs\Tests\Models\Document;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can render document resource', function () {
    get(DocumentResource::getUrl())->assertSuccessful();
});

it('can list documents', function () {
    Document::query()->delete();
    $template = DocumentTemplate::factory()->create();
    $documents = Document::factory()->count(10)->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->loadTable()
        ->assertCanSeeTableRecords($documents)
        ->assertCountTableRecords(10);
});

it('can render document type/for/key column in table', function () {
    $template = DocumentTemplate::factory()->create();
    $documents = Document::factory()->count(10)->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->loadTable()
        ->assertCanRenderTableColumn('id')
        ->assertCanRenderTableColumn('ref')
        ->assertCanRenderTableColumn('is_send')
        ->assertCanRenderTableColumn('documentTemplate.name');
});

it('can render document list page', function () {
    livewire(Pages\ListDocuments::class)->assertSuccessful();
});

it('can render view document page', function () {
    $template = DocumentTemplate::factory()->create();

    livewire(Pages\ListDocuments::class, [
        'record' => Document::factory()->withId($template->id)->create(),
    ])
        ->mountAction('view')
        ->assertSuccessful();
});

it('can render document create page', function () {
    livewire(Pages\ListDocuments::class)
        ->mountAction('create')
        ->assertSuccessful();
});

it('can create new document', function () {
    $template = DocumentTemplate::factory()->create();
    $newData = Document::factory()->withId($template->id)->make();

    livewire(Pages\CreateDocument::class)
        ->fillForm([
            'document_template_id' => $template->id,
            'body' => [],
            'ref' => $newData->ref,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Document::class, [
        'body' => $template->body,
        'ref' => $newData->ref,
    ]);
});

it('can validate document input', function () {
    livewire(Pages\CreateDocument::class)
        ->fillForm([
            'document_template_id' => null,
            'body' => null,
            'ref' => null,
            'is_send' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'document_template_id' => 'required',
        ]);
});

it('can render document edit action', function () {
    $template = DocumentTemplate::factory()->create();

    livewire(Pages\ListDocuments::class, [
        'record' => Document::factory()->withId($template->id)->create(),
    ])
        ->mountAction('edit')
        ->assertSuccessful();
});

it('can render document edit page', function () {
    $template = DocumentTemplate::factory()->create();
    get(DocumentResource::getUrl('edit', [
        'record' => Document::factory()->withId($template->id)->create(),
    ]))->assertSuccessful();
});

it('can retrieve document data', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\EditDocument::class, [
        'record' => $document->getRouteKey(),
    ])
        ->assertFormSet([
            'document_template_id' => $document->document_template_id,
            'ref' => $document->ref,
        ]);
});

it('can validate edit document input', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\EditDocument::class, [
        'record' => $document->getRouteKey(),
    ])
        ->fillForm([
            'document_template_id' => null,
            'body' => null,
            'ref' => null,
            'is_send' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'document_template_id' => 'required',
        ]);
});

it('can save document data', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();
    $newData = Document::factory()->withId($template->id)->make();

    livewire(Pages\EditDocument::class, [
        'record' => $document->getRouteKey(),
    ])
        ->fillForm([
            'document_template_id' => $template->id,
            'body' => [],
            'ref' => $newData->ref,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($document->refresh())
        ->document_template_id->toBe($newData->document_template_id);
});

it('can delete document', function () {
    $template = DocumentTemplate::factory()->create();
    $document = Document::factory()->withId($template->id)->create();

    livewire(Pages\ListDocuments::class)
        ->callTableAction('delete', $document)
        ->assertHasNoTableActionErrors();

    assertModelMissing($document);
});
